<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
// Maatwebsite Excel facade removed; we stream CSV directly to avoid legacy provider issues.

class ReportController extends Controller
{
    /**
     * Show the task report form
     */
    public function index()
    {
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();
        
        $statuses = Task::distinct()->pluck('status')->filter()->values();
        $priorities = Task::distinct()->pluck('priority')->filter()->values();
        
        return view('reports.index', compact('users', 'statuses', 'priorities'));
    }

    /**
     * Generate Task Report PDF
     */
    public function generateTaskReport(Request $request)
    {
        // Validate request
        $request->validate([
            'status' => 'nullable|string',
            'priority' => 'nullable|string',
            'assignee_id' => 'nullable|exists:users,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'limit' => 'nullable|integer|min:1|max:10000',
            'format' => 'required|in:pdf,html'
        ]);


        // Build query with filters
        $query = Task::with(['user', 'assignedUser', 'assignedUsers']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Enforce server-side scoping: users may only generate reports for their own tasks
        $user = Auth::user();

        // If an assignee_id is provided that doesn't match the current user, forbid the request.
        if ($request->filled('assignee_id') && (int) $request->assignee_id !== (int) $user->id) {
            abort(403, 'Forbidden - you can only generate reports for your own tasks.');
        }

        // Restrict query to tasks where the current user is the creator or an assignee
        $query->where(function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('assigned_user_id', $user->id)
              ->orWhereHas('assignedUsers', function($subQ) use ($user) {
                  $subQ->where('user_id', $user->id);
              });
        });

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Apply optional limit to avoid huge PDF generation
        $limit = (int) $request->get('limit', 0);

        // Get tasks and order by priority and due date
        $ordered = $query->orderByRaw("
            CASE priority 
                WHEN 'High' THEN 1 
                WHEN 'Medium' THEN 2 
                WHEN 'Low' THEN 3 
                ELSE 4 
            END
        ")->orderBy('due_date', 'asc');

        if ($limit > 0) {
            $tasks = $ordered->limit($limit)->get();
        } else {
            $tasks = $ordered->get();
        }

        // Use configured timezone (fallback to Asia/Colombo)
        $timezone = config('app.timezone', 'Asia/Colombo');
        $generatedAt = Carbon::now()->setTimezone($timezone);

        // Prepare data for PDF
        $data = [
            'tasks' => $tasks,
            'filters' => [
                'status' => $request->status,
                'priority' => $request->priority,
                'assignee_id' => $request->assignee_id,
                'assignee_name' => $request->assignee_id ? User::find($request->assignee_id)->name : null,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
            ],
            'generated_at' => $generatedAt,
            'generated_by' => Auth::user(),
            'total_tasks' => $tasks->count(),
            'summary' => $this->generateSummary($tasks)
        ];

        // Return HTML preview or PDF download
        if ($request->format === 'html') {
            return view('reports.task-report', $data);
        }

        // Generate PDF
        $pdf = Pdf::loadView('reports.task-report', $data)
                  ->setPaper('a4', 'portrait')
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isRemoteEnabled' => true,
                      'defaultFont' => 'DejaVu Sans'
                  ]);

    // Use the localized generated time for filename
    $filename = 'task-report-' . $generatedAt->format('Y-m-d-H-i-s') . '.pdf';

    return $pdf->download($filename);
    }

    /**
     * Generate summary statistics
     */
    private function generateSummary($tasks)
    {
        return [
            'by_status' => $tasks->groupBy('status')->map->count(),
            'by_priority' => $tasks->groupBy('priority')->map->count(),
            'overdue_count' => $tasks->filter(function($task) {
                return $task->isOverdue();
            })->count(),
            'due_today_count' => $tasks->filter(function($task) {
                return $task->isDueToday();
            })->count(),
            'completed_count' => $tasks->where('status', 'Completed')->count(),
            'pending_count' => $tasks->where('status', '!=', 'Completed')->count(),
        ];
    }

    /**
     * Get filter options for AJAX requests
     */
    public function getFilterOptions()
    {
        return response()->json([
            'statuses' => Task::distinct()->pluck('status')->filter()->values(),
            'priorities' => Task::distinct()->pluck('priority')->filter()->values(),
            'users' => User::select('id', 'name', 'email')->orderBy('name')->get()
        ]);
    }

    /**
     * Export tasks to Excel using the same filters
     */
    public function exportTaskReport(Request $request)
    {
        // Validate request (allow optional format param: pdf or csv/excel)
        $request->validate([
            'status' => 'nullable|string',
            'priority' => 'nullable|string',
            'assignee_id' => 'nullable|exists:users,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'limit' => 'nullable|integer|min:1|max:10000',
            'format' => 'nullable|in:pdf,csv,excel'
        ]);

        // Build query with filters (reuse logic)
        $query = Task::with(['user', 'assignedUser', 'assignedUsers']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Enforce server-side scoping for exports: only allow exporting tasks related to the authenticated user
        $user = Auth::user();

        if ($request->filled('assignee_id') && (int) $request->assignee_id !== (int) $user->id) {
            abort(403, 'Forbidden - you can only export reports for your own tasks.');
        }

        // Restrict to tasks where current user is creator or assignee
        $query->where(function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('assigned_user_id', $user->id)
              ->orWhereHas('assignedUsers', function($subQ) use ($user) {
                  $subQ->where('user_id', $user->id);
              });
        });

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $limit = (int) $request->get('limit', 0);

        $ordered = $query->orderByRaw("\n            CASE priority \n                WHEN 'High' THEN 1 \n                WHEN 'Medium' THEN 2 \n                WHEN 'Low' THEN 3 \n                ELSE 4 \n            END\n        ")->orderBy('due_date', 'asc');

        if ($limit > 0) {
            $tasks = $ordered->limit($limit)->get();
        } else {
            $tasks = $ordered->get();
        }

        // If PDF was requested, reuse the PDF generation logic (same layout as task-report)
        $format = $request->get('format', 'csv');

        if ($format === 'pdf') {
            $timezone = config('app.timezone', 'Asia/Colombo');
            $generatedAt = Carbon::now()->setTimezone($timezone);

            $data = [
                'tasks' => $tasks,
                'filters' => [
                    'status' => $request->status,
                    'priority' => $request->priority,
                    'assignee_id' => $request->assignee_id,
                    'assignee_name' => $request->assignee_id ? User::find($request->assignee_id)->name : null,
                    'date_from' => $request->date_from,
                    'date_to' => $request->date_to,
                ],
                'generated_at' => $generatedAt,
                'generated_by' => Auth::user(),
                'total_tasks' => $tasks->count(),
                'summary' => $this->generateSummary($tasks)
            ];

            $pdf = Pdf::loadView('reports.task-report', $data)
                      ->setPaper('a4', 'portrait')
                      ->setOptions([
                          'isHtml5ParserEnabled' => true,
                          'isRemoteEnabled' => true,
                          'defaultFont' => 'DejaVu Sans'
                      ]);

            $filename = 'task-report-' . $generatedAt->format('Y-m-d-H-i-s') . '.pdf';

            return $pdf->download($filename);
        }

        // Prepare rows for Excel/CSV
    $rows = [];
    // Header
    $rows[] = ['Title', 'Status', 'Due Date', 'Assignee', 'Priority', 'Creator'];

        foreach ($tasks as $task) {
            // Collect all assignee names: primary assigned_user plus any many-to-many assignedUsers
            $assigneeNames = collect();

            if ($task->assigned_user_id && $task->assignedUser) {
                $assigneeNames->push($task->assignedUser->name);
            }

            // Add names from the many-to-many relation (if any)
            $many = $task->assignedUsers->pluck('name')->filter();
            if ($many->count()) {
                $assigneeNames = $assigneeNames->merge($many);
            }

            // Unique and join by comma; null when empty
            $assigneeName = $assigneeNames->unique()->values()->implode(', ');
            if ($assigneeName === '') {
                $assigneeName = null;
            }

            $creatorName = $task->user ? $task->user->name : null;

            $rows[] = [
                $task->title,
                $task->status,
                $task->due_date ? $task->due_date->format('Y-m-d') : null,
                $assigneeName,
                $task->priority,
                $creatorName,
            ];
        }

        // Stream CSV so we don't rely on legacy packages (Excel can open CSV files)
        $timezone = config('app.timezone', 'Asia/Colombo');
        $generatedAt = Carbon::now()->setTimezone($timezone);
        $filename = 'task-report-' . $generatedAt->format('Y-m-d-H-i-s') . '.csv';

        $callback = function() use ($rows) {
            $FH = fopen('php://output', 'w');

            // Write UTF-8 BOM once so Excel recognizes UTF-8
            fwrite($FH, "\xEF\xBB\xBF");

            // Write rows (first row is header). No 'sep=' directive to avoid BOM-related artifacts.
            foreach ($rows as $row) {
                fputcsv($FH, $row, ',');
            }

            fclose($FH);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
 
    }
}
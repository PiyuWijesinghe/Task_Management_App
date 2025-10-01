<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get tasks by status with proper filtering
        $pending = $user->tasks()->where('status', 'Pending')->orderBy('created_at', 'desc')->get();
        $inProgress = $user->tasks()->where('status', 'In Progress')->orderBy('created_at', 'desc')->get();
        $completed = $user->tasks()->where('status', 'Completed')->orderBy('created_at', 'desc')->get();
        
        // If filtering by status (from sidebar links)
        if ($request->has('status')) {
            $filterStatus = $request->get('status');
            switch($filterStatus) {
                case 'Pending':
                    $inProgress = collect();
                    $completed = collect();
                    break;
                case 'In Progress':
                    $pending = collect();
                    $completed = collect();
                    break;
                case 'Completed':
                    $pending = collect();
                    $inProgress = collect();
                    break;
            }
        }
        
        return view('tasks.index', compact('pending', 'inProgress', 'completed'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:Pending,In Progress,Completed',
        ]);
        $validated['user_id'] = auth()->id();
        $task = Task::create($validated);
        return redirect()->route('dashboard')->with('success', 'Task "' . $task->title . '" created successfully and added to your dashboard!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
    $this->authorize('view', $task);
    return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
    $this->authorize('update', $task);
    return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:Pending,In Progress,Completed',
        ]);
        $task->update($validated);
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
    $this->authorize('delete', $task);
    $task->delete();
    return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    /**
     * Mark task as completed.
     */
    public function markAsCompleted(Task $task)
    {
        $this->authorize('update', $task);
        
        if ($task->status === 'Completed') {
            return redirect()->back()->with('info', 'Task is already completed!');
        }
        
        $task->update(['status' => 'Completed']);
        
        return redirect()->back()->with('success', 'Task "' . $task->title . '" marked as completed! 🎉');
    }
}

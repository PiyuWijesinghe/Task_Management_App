<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Postponement;
use App\Models\TaskComment;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskPostponedNotification;
use App\Notifications\CommentAddedNotification;

class TaskController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get priority filter
        $priorityFilter = $request->get('priority');
        $sortBy = $request->get('sort', 'due_date'); // Default sort by due_date
        
        // Base query for user's tasks
        $baseQuery = function($query) use ($user, $priorityFilter) {
            $query->where(function($subQuery) use ($user) {
                $subQuery->where('user_id', $user->id)
                         ->orWhere('assigned_user_id', $user->id)
                         ->orWhereHas('assignedUsers', function($q) use ($user) {
                             $q->where('user_id', $user->id);
                         });
            });
            
            // Apply priority filter if specified
            if ($priorityFilter && in_array($priorityFilter, ['High', 'Medium', 'Low'])) {
                $query->where('priority', $priorityFilter);
            }
            
            return $query->with(['assignedUsers', 'assignedUser']);
        };
        
        // Apply sorting based on user preference
        $orderQuery = function($query) use ($sortBy) {
            if ($sortBy === 'priority') {
                // Sort by priority: High -> Medium -> Low
                return $query->orderByRaw("CASE 
                    WHEN priority = 'High' THEN 1 
                    WHEN priority = 'Medium' THEN 2 
                    WHEN priority = 'Low' THEN 3 
                    ELSE 4 END")
                    ->orderBy('created_at', 'desc');
            } else {
                // Default: Sort by due date, then creation date
                return $query->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END')
                    ->orderBy('due_date', 'asc')
                    ->orderBy('created_at', 'desc');
            }
        };
        
        // Get tasks by status with filtering and sorting
        $pending = $orderQuery($baseQuery(Task::query())->where('status', 'Pending'))->get();
        $inProgress = $orderQuery($baseQuery(Task::query())->where('status', 'In Progress'))->get();
        $completed = $orderQuery($baseQuery(Task::query())->where('status', 'Completed'))->get();
        
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
        
        return view('tasks.index', compact('pending', 'inProgress', 'completed', 'priorityFilter', 'sortBy'));
    }

    /**
     * Display the dashboard with filtering and sorting capabilities.
     */
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        
        // Get priority filter and sort preference
        $priorityFilter = $request->get('priority');
        $sortBy = $request->get('sort', 'due_date'); // Default sort by due_date
        
        // Base query for user's tasks
        $baseQuery = function($query) use ($user, $priorityFilter) {
            $query->where(function($subQuery) use ($user) {
                $subQuery->where('user_id', $user->id)
                         ->orWhere('assigned_user_id', $user->id)
                         ->orWhereHas('assignedUsers', function($q) use ($user) {
                             $q->where('user_id', $user->id);
                         });
            });
            
            // Apply priority filter if specified
            if ($priorityFilter && in_array($priorityFilter, ['High', 'Medium', 'Low'])) {
                $query->where('priority', $priorityFilter);
            }
            
            return $query->with(['assignedUsers', 'assignedUser']);
        };
        
        // Apply sorting based on user preference
        $orderQuery = function($query) use ($sortBy) {
            if ($sortBy === 'priority') {
                // Sort by priority: High -> Medium -> Low
                return $query->orderByRaw("CASE 
                    WHEN priority = 'High' THEN 1 
                    WHEN priority = 'Medium' THEN 2 
                    WHEN priority = 'Low' THEN 3 
                    ELSE 4 END")
                    ->orderBy('created_at', 'desc');
            } else {
                // Default: Sort by due date, then creation date
                return $query->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END')
                    ->orderBy('due_date', 'asc')
                    ->orderBy('created_at', 'desc');
            }
        };
        
        // Get tasks by status with filtering and sorting
        $pending = $orderQuery($baseQuery(Task::query())->where('status', 'Pending'))->get();
        $inProgress = $orderQuery($baseQuery(Task::query())->where('status', 'In Progress'))->get();
        $completed = $orderQuery($baseQuery(Task::query())->where('status', 'Completed'))->get();
        
        return view('dashboard', compact('pending', 'inProgress', 'completed', 'priorityFilter', 'sortBy'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('id', '!=', auth()->id())->get(['id', 'name', 'email', 'username']);
        return view('tasks.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
            'status' => 'required|in:Pending,In Progress,Completed',
            'priority' => 'required|in:High,Medium,Low',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
            'assigned_to' => 'nullable|exists:users,id',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|max:10240|mimes:pdf,jpg,jpeg,png,gif,doc,docx,xls,xlsx,txt,ppt,pptx', // Max 10MB per file
        ], [
            'due_date.after_or_equal' => 'Due date cannot be in the past. Please select today or a future date.',
            'attachments.max' => 'You can upload maximum 5 files.',
            'attachments.*.max' => 'Each file must be less than 10MB.',
            'attachments.*.mimes' => 'Only PDF, images, Word, Excel, PowerPoint, and text files are allowed.',
        ]);
        
        $validated['user_id'] = auth()->user()->id;
        
        // Handle assigned_to field (single user assignment from frontend)
        if (isset($validated['assigned_to']) && $validated['assigned_to']) {
            $validated['assigned_user_id'] = $validated['assigned_to'];
        }
        
        // Remove fields not in fillable array
        $assignedUsers = $validated['assigned_users'] ?? [];
        $attachments = $validated['attachments'] ?? [];
        unset($validated['assigned_users'], $validated['assigned_to'], $validated['attachments']);
        
        $task = Task::create($validated);
        
        // Handle file attachments
        if (!empty($attachments)) {
            foreach ($attachments as $file) {
                $this->storeAttachment($task, $file);
            }
        }
        
        // Assign multiple users if provided
        if (!empty($assignedUsers)) {
            $task->assignedUsers()->sync($assignedUsers);
        }
        
        // Load the created task with attachments for response
        $task->load(['attachments', 'assignedUsers', 'assignedUser']);
        
        // Return JSON response for API
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'message' => 'Task created successfully!',
                'data' => $task
            ], 201);
        }
        
        // For web requests (if any)
        $assignedUserNames = [];
        if (!empty($assignedUsers)) {
            $assignedUserNames = User::whereIn('id', $assignedUsers)->pluck('name')->toArray();
        }
        
        $successMessage = 'Task "' . $task->title . '" created successfully';
        if (!empty($assignedUserNames)) {
            $successMessage .= ' and assigned to ' . implode(', ', $assignedUserNames);
        }
        $successMessage .= '!';
        
        return redirect()->route('dashboard')->with('success', $successMessage);
    }

    /**
     * Store a task attachment
     */
    private function storeAttachment(Task $task, $file)
    {
        // Generate unique filename
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $storedName = Str::uuid() . '.' . $extension;
        
        // Store file in task attachments directory
        $filePath = $file->storeAs('task-attachments', $storedName, 'local');
        
        // Create attachment record
        $task->attachments()->create([
            'original_name' => $originalName,
            'stored_name' => $storedName,
            'file_path' => $filePath,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'extension' => $extension,
            // Ensure we store the numeric user id (auth()->id() may be overridden to return username)
            'uploaded_by' => auth()->user() ? auth()->user()->id : null,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        
        // Load postponements, comments, attachments, and related data
        $task->load(['postponements.postponedBy', 'assignedUsers', 'assignedUser', 'comments.user', 'attachments.uploadedBy']);
        
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $users = User::where('id', '!=', auth()->id())->get(['id', 'name', 'email']);
        return view('tasks.edit', compact('task', 'users'));
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
            'due_date' => 'nullable|date|after_or_equal:today',
            'status' => 'required|in:Pending,In Progress,Completed',
            'priority' => 'required|in:High,Medium,Low',
            'assigned_user_id' => 'nullable|exists:users,id',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
        ], [
            'due_date.after_or_equal' => 'Due date cannot be in the past. Please select today or a future date.',
        ]);
        // Update core fields
        $task->update(Arr::only($validated, ['title','description','due_date','status','priority','assigned_user_id']));

        // Handle multiple assigned users
        $assignedUsers = $validated['assigned_users'] ?? [];
        if (!empty($assignedUsers)) {
            $task->assignedUsers()->sync($assignedUsers);
            // If no primary assigned_user_id set, set it to the first selected
            if (empty($validated['assigned_user_id'])) {
                $task->update(['assigned_user_id' => $assignedUsers[0]]);
            }
        } else {
            // No assigned users provided: detach and optionally clear primary
            $task->assignedUsers()->detach();
            if (empty($validated['assigned_user_id'])) {
                $task->update(['assigned_user_id' => null]);
            }
        }

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

    /**
     * Show the assign user page with all tasks created by the authenticated user.
     */
    public function assignPage()
    {
        $user = auth()->user();
        
        // Get all tasks created by the current user (not assigned tasks)
        $tasks = Task::where('user_id', $user->id)
            ->with(['assignedUsers', 'assignedUser'])
            ->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('due_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get all users except the current user for assignment
        $users = User::where('id', '!=', $user->id)->get(['id', 'name', 'email', 'username']);
        
        return view('tasks.assign', compact('tasks', 'users'));
    }

    /**
     * Assign multiple users to a task.
     */
    public function assignUser(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        
        $validated = $request->validate([
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
        ]);
        
        // Sync the many-to-many relationship
        if (isset($validated['assigned_users']) && !empty($validated['assigned_users'])) {
            $task->assignedUsers()->sync($validated['assigned_users']);

            // Notify newly assigned users
            $assignedUsers = User::whereIn('id', $validated['assigned_users'])->get();
            Notification::send($assignedUsers, new TaskAssignedNotification($task, auth()->user()));

            // Get names of assigned users for success message
            $assignedUserNames = $assignedUsers->pluck('name')->toArray();
            $userNames = implode(', ', $assignedUserNames);

            return redirect()->back()->with('success', 'Task "' . $task->title . '" assigned to ' . $userNames . ' successfully!');
        } else {
            // Remove all assignments
            $task->assignedUsers()->detach();
            return redirect()->back()->with('success', 'All assignments removed from task "' . $task->title . '" successfully!');
        }
    }

    /**
     * Postpone a task by updating its due date.
     */
    public function postpone(Request $request, Task $task)
    {
        // Check if user can postpone this task
        if (!$task->canBePostponedBy(auth()->user())) {
            return redirect()->back()->with('error', 'You are not authorized to postpone this task.');
        }

        // Validate the input
        $validated = $request->validate([
            'new_due_date' => 'required|date|after:today',
            'reason' => 'nullable|string|max:500',
        ], [
            'new_due_date.required' => 'Please select a new due date.',
            'new_due_date.after' => 'The new due date must be in the future.',
        ]);

        // Store the old due date
        $oldDueDate = $task->due_date;

        // Create postponement record
        Postponement::create([
            'task_id' => $task->id,
            'old_due_date' => $oldDueDate,
            'new_due_date' => $validated['new_due_date'],
            'reason' => $validated['reason'],
            // auth()->id() is configured to return username; use numeric id instead
            'postponed_by' => auth()->user() ? auth()->user()->id : null,
        ]);

        // Update the task's due date
        $task->update([
            'due_date' => $validated['new_due_date'],
        ]);

        // Notify the task owner and assigned users (excluding the actor)
        $recipients = collect();
        if ($task->user) {
            $recipients->push($task->user);
        }
        // include primary assigned user (assigned_user_id) and many-to-many assigned users
        if ($task->assignedUser) {
            $recipients->push($task->assignedUser);
        }
        $recipients = $recipients->merge($task->assignedUsers)->unique('id')->filter(function ($u) {
            return $u && $u->id !== auth()->id();
        });

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new TaskPostponedNotification($task, $oldDueDate, $validated['new_due_date'], auth()->user()));
        }

        return redirect()->back()->with('success', 'Task "' . $task->title . '" has been postponed successfully!');
    }

    /**
     * Display postponed tasks.
     */
    public function postponed()
    {
        return view('tasks.postponed');
    }

    /**
     * Store a comment for the specified task.
     */
    public function storeComment(Request $request, Task $task)
    {
        $this->authorize('view', $task);
        
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        TaskComment::create([
            'task_id' => $task->id,
            'user_id' => auth()->user()->id,
            'comment' => $validated['comment'],
        ]);

        // Notify task owner and assigned users (excluding commenter)
        $recipients = collect();
        if ($task->user) {
            $recipients->push($task->user);
        }
        $recipients = $recipients->merge($task->assignedUsers)->unique('id')->filter(function ($u) {
            return $u && $u->id !== auth()->id();
        });

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new CommentAddedNotification($task, auth()->user()));
        }

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    /**
     * Delete a comment.
     */
    public function deleteComment(TaskComment $comment)
    {
        // Only allow the comment author or task owner to delete
        if ($comment->user_id !== auth()->id() && $comment->task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized to delete this comment.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }

    /**
     * Store attachment for web routes
     */
    public function storeAttachmentWeb(Request $request, Task $task)
    {
        // Use policy to authorize attachment creation
        $this->authorize('create', [TaskAttachment::class, $task]);
        
        $validated = $request->validate([
            'attachment' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,gif,doc,docx,xls,xlsx,txt,ppt,pptx',
        ], [
            'attachment.max' => 'File must be less than 10MB.',
            'attachment.mimes' => 'Only PDF, images, Word, Excel, PowerPoint, and text files are allowed.',
        ]);

        $file = $request->file('attachment');
        $attachment = $this->storeAttachment($task, $file);
        
        // Log the upload for security audit
        \Log::info('Web attachment uploaded', [
            'user_id' => auth()->id(),
            'task_id' => $task->id,
            'attachment_id' => $attachment->id,
            'file_name' => $attachment->original_name
        ]);
        
        return redirect()->back()->with('success', 'File uploaded successfully!');
    }

    /**
     * Get task attachments
     */
    public function getAttachments(Task $task)
    {
        $this->authorize('view', $task);
        
        $attachments = $task->attachments()->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $attachments
        ]);
    }

    /**
     * Add a new attachment to task
     */
    public function storeAttachmentApi(Request $request, Task $task)
    {
        $this->authorize('view', $task);
        
        $validated = $request->validate([
            'attachment' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,gif,doc,docx,xls,xlsx,txt,ppt,pptx',
        ], [
            'attachment.max' => 'File must be less than 10MB.',
            'attachment.mimes' => 'Only PDF, images, Word, Excel, PowerPoint, and text files are allowed.',
        ]);

        $file = $request->file('attachment');
        $attachment = $this->storeAttachment($task, $file);
        
        // Get the created attachment
        $newAttachment = $task->attachments()->latest()->first();
        
        return response()->json([
            'status' => 'success',
            'message' => 'File uploaded successfully!',
            'data' => $newAttachment
        ], 201);
    }

    /**
     * Download task attachment
     */
    public function downloadAttachment(Task $task, TaskAttachment $attachment)
    {
        // First, verify the attachment belongs to the task
        if ($attachment->task_id !== $task->id) {
            abort(404, 'Attachment not found for this task.');
        }

        // Use policy to authorize download
        $this->authorize('download', $attachment);

        if (!Storage::exists($attachment->file_path)) {
            abort(404, 'File not found on server.');
        }

        // Log the download for security audit (optional)
        \Log::info('Web attachment downloaded', [
            'user_id' => auth()->id(),
            'task_id' => $task->id,
            'attachment_id' => $attachment->id,
            'file_name' => $attachment->original_name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return Storage::download($attachment->file_path, $attachment->original_name);
    }

    /**
     * Delete task attachment
     */
    public function deleteAttachment(Request $request, Task $task, TaskAttachment $attachment)
    {
        // First, verify the attachment belongs to the task
        if ($attachment->task_id !== $task->id) {
            abort(404, 'Attachment not found for this task.');
        }

        // Use policy to authorize deletion
        $this->authorize('delete', $attachment);

        // Delete physical file
        if (Storage::exists($attachment->file_path)) {
            Storage::delete($attachment->file_path);
        }

        // Log the deletion for security audit
        \Log::info('Web attachment deleted', [
            'user_id' => auth()->id(),
            'task_id' => $task->id,
            'attachment_id' => $attachment->id,
            'file_name' => $attachment->original_name,
            'uploaded_by' => $attachment->uploaded_by
        ]);

        // Delete database record
        $attachment->delete();

        // Return appropriate response based on request type
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'message' => 'Attachment deleted successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Attachment deleted successfully!');
    }
}

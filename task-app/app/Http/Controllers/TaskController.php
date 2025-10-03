<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Postponement;
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
        ], [
            'due_date.after_or_equal' => 'Due date cannot be in the past. Please select today or a future date.',
        ]);
        $validated['user_id'] = auth()->user()->id;
        
        // Remove assigned_users from validated data for task creation
        $assignedUsers = $validated['assigned_users'] ?? [];
        unset($validated['assigned_users']);
        
        $task = Task::create($validated);
        
        // Assign multiple users if provided
        if (!empty($assignedUsers)) {
            $task->assignedUsers()->sync($assignedUsers);
            
            // Get names of assigned users for success message
            $assignedUserNames = User::whereIn('id', $assignedUsers)->pluck('name')->toArray();
            $userNames = implode(', ', $assignedUserNames);
            
            return redirect()->route('dashboard')->with('success', 'Task "' . $task->title . '" created successfully and assigned to ' . $userNames . '!');
        }
        
        return redirect()->route('dashboard')->with('success', 'Task "' . $task->title . '" created successfully and added to your dashboard!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        
        // Load postponements with related data
        $task->load(['postponements.postponedBy', 'assignedUsers', 'assignedUser']);
        
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
        ], [
            'due_date.after_or_equal' => 'Due date cannot be in the past. Please select today or a future date.',
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
            
            // Get names of assigned users for success message
            $assignedUserNames = User::whereIn('id', $validated['assigned_users'])->pluck('name')->toArray();
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
            'postponed_by' => auth()->id(),
        ]);

        // Update the task's due date
        $task->update([
            'due_date' => $validated['new_due_date'],
        ]);

        return redirect()->back()->with('success', 'Task "' . $task->title . '" has been postponed successfully!');
    }

    /**
     * Display postponed tasks.
     */
    public function postponed()
    {
        return view('tasks.postponed');
    }
}

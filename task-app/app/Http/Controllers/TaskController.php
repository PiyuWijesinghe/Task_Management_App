<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
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
        
        // Get both created and assigned tasks by status, ordered by due date priority
        $pending = Task::where(function($query) use ($user) {
            $query->where('user_id', $user->id)->orWhere('assigned_user_id', $user->id);
        })->where('status', 'Pending')
        ->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END')
        ->orderBy('due_date', 'asc')
        ->orderBy('created_at', 'desc')->get();
        
        $inProgress = Task::where(function($query) use ($user) {
            $query->where('user_id', $user->id)->orWhere('assigned_user_id', $user->id);
        })->where('status', 'In Progress')
        ->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END')
        ->orderBy('due_date', 'asc')
        ->orderBy('created_at', 'desc')->get();
        
        $completed = Task::where(function($query) use ($user) {
            $query->where('user_id', $user->id)->orWhere('assigned_user_id', $user->id);
        })->where('status', 'Completed')->orderBy('created_at', 'desc')->get();
        
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
        $users = User::where('id', '!=', auth()->id())->get(['id', 'name', 'email']);
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
            'assigned_user_id' => 'nullable|exists:users,id',
        ], [
            'due_date.after_or_equal' => 'Due date cannot be in the past. Please select today or a future date.',
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
            ->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('due_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get all users except the current user for assignment
        $users = User::where('id', '!=', $user->id)->get(['id', 'name', 'email']);
        
        return view('tasks.assign', compact('tasks', 'users'));
    }

    /**
     * Assign a user to a task.
     */
    public function assignUser(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        
        $validated = $request->validate([
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);
        
        $task->update($validated);
        
        if ($validated['assigned_user_id']) {
            $assignedUser = User::find($validated['assigned_user_id']);
            return redirect()->back()->with('success', 'Task "' . $task->title . '" assigned to ' . $assignedUser->name . ' successfully!');
        } else {
            return redirect()->back()->with('success', 'Task "' . $task->title . '" assignment removed successfully!');
        }
    }
}

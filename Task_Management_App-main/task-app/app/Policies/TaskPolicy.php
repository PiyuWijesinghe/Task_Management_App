<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function view(User $user, Task $task)
    {
        // Allow creator, assigned user, and users in many-to-many relationship to view
        return $user->id === $task->user_id || 
               $user->id === $task->assigned_user_id ||
               $task->assignedUsers()->where('user_id', $user->id)->exists();
    }

    public function update(User $user, Task $task)
    {
        // Only creator can edit/update tasks - assigned users can only view
        return $user->id === $task->user_id;
    }

    public function delete(User $user, Task $task)
    {
        // Only creator can delete tasks
        return $user->id === $task->user_id;
    }
}

<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function view(User $user, Task $task)
    {
        // Allow both creator and assigned user to view
        return $user->id === $task->user_id || $user->id === $task->assigned_user_id;
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

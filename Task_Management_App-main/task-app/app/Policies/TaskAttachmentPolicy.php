<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Task;
use App\Models\TaskAttachment;

class TaskAttachmentPolicy
{
    /**
     * Determine whether the user can view any attachments for the task.
     */
    public function viewAny(User $user, Task $task): bool
    {
        // User can view attachments if they have access to the task
        return $this->hasTaskAccess($user, $task);
    }

    /**
     * Determine whether the user can view the attachment.
     */
    public function view(User $user, TaskAttachment $attachment): bool
    {
        // User can view attachment if they have access to the related task
        return $this->hasTaskAccess($user, $attachment->task);
    }

    /**
     * Determine whether the user can download the attachment.
     */
    public function download(User $user, TaskAttachment $attachment): bool
    {
        // User can download attachment if they have access to the related task
        return $this->hasTaskAccess($user, $attachment->task);
    }

    /**
     * Determine whether the user can create attachments for the task.
     */
    public function create(User $user, Task $task): bool
    {
        // User can create attachments if they have access to the task
        return $this->hasTaskAccess($user, $task);
    }

    /**
     * Determine whether the user can update the attachment.
     */
    public function update(User $user, TaskAttachment $attachment): bool
    {
        $task = $attachment->task;
        
        // Only task creator or attachment uploader can update/rename attachment
        return $user->id === $task->user_id || $user->id === $attachment->uploaded_by;
    }

    /**
     * Determine whether the user can delete the attachment.
     */
    public function delete(User $user, TaskAttachment $attachment): bool
    {
        $task = $attachment->task;
        
        // Only task creator or attachment uploader can delete attachment
        return $user->id === $task->user_id || $user->id === $attachment->uploaded_by;
    }

    /**
     * Check if user has access to the task (used by TaskPolicy logic).
     */
    private function hasTaskAccess(User $user, Task $task): bool
    {
        // User has access if they are:
        // 1. Task creator
        // 2. Assigned user (single assignment)
        // 3. One of the assigned users (many-to-many relationship)
        return $user->id === $task->user_id || 
               $user->id === $task->assigned_user_id ||
               $task->assignedUsers()->where('user_id', $user->id)->exists();
    }
}
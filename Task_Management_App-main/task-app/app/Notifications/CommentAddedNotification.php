<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Task;
use App\Models\User;

class CommentAddedNotification extends Notification
{
    use Queueable;

    protected Task $task;
    protected User $commentedBy;

    public function __construct(Task $task, User $commentedBy)
    {
        $this->task = $task;
        $this->commentedBy = $commentedBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'comment_added',
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'commented_by_id' => $this->commentedBy->id,
            'commented_by_name' => $this->commentedBy->name,
        ];
    }
}

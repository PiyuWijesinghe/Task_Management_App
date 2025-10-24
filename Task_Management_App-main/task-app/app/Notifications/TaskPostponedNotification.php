<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Task;
use App\Models\User;

class TaskPostponedNotification extends Notification
{
    use Queueable;

    protected Task $task;
    protected $oldDueDate;
    protected $newDueDate;
    protected User $postponedBy;

    public function __construct(Task $task, $oldDueDate, $newDueDate, User $postponedBy)
    {
        $this->task = $task;
        $this->oldDueDate = $oldDueDate;
        $this->newDueDate = $newDueDate;
        $this->postponedBy = $postponedBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'task_postponed',
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'old_due_date' => $this->oldDueDate,
            'new_due_date' => $this->newDueDate,
            'postponed_by_id' => $this->postponedBy->id,
            'postponed_by_name' => $this->postponedBy->name,
        ];
    }
}

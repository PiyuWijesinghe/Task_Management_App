<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Postponement extends Model
{
    protected $fillable = [
        'task_id',
        'old_due_date',
        'new_due_date',
        'reason',
        'postponed_by',
    ];

    protected $casts = [
        'old_due_date' => 'date',
        'new_due_date' => 'date',
    ];

    /**
     * Get the task that was postponed.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user who postponed the task.
     */
    public function postponedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'postponed_by');
    }
}

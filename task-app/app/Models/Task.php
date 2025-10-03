<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description', 
        'due_date',
        'status',
        'priority',
        'user_id',
        'assigned_user_id',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * Get the user that owns the task (creator).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that is assigned to the task.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    /**
     * Get all users assigned to this task (many-to-many).
     */
    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Check if the task is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               $this->status !== 'Completed';
    }

    /**
     * Check if the task is due today.
     */
    public function isDueToday(): bool
    {
        return $this->due_date && 
               $this->due_date->isToday() && 
               $this->status !== 'Completed';
    }

    /**
     * Get the postponements for the task.
     */
    public function postponements(): HasMany
    {
        return $this->hasMany(Postponement::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the comments for the task.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class)->with('user')->orderBy('created_at', 'asc');
    }

    /**
     * Check if the current user can postpone this task.
     */
    public function canBePostponedBy(User $user): bool
    {
        return $this->user_id === $user->id || 
               $this->assigned_user_id === $user->id ||
               $this->assignedUsers()->where('user_id', $user->id)->exists();
    }

    /**
     * Get priority badge classes for styling.
     */
    public function getPriorityBadgeClasses(): string
    {
        return match($this->priority) {
            'High' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400 border-red-200 dark:border-red-800',
            'Medium' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800',
            'Low' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400 border-green-200 dark:border-green-800',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400 border-gray-200 dark:border-gray-800',
        };
    }

    /**
     * Get priority icon for display.
     */
    public function getPriorityIcon(): string
    {
        return match($this->priority) {
            'High' => '🔥',
            'Medium' => '⚡',
            'Low' => '🟢',
            default => '⚪',
        };
    }

    /**
     * Get priority text with icon.
     */
    public function getPriorityText(): string
    {
        return $this->getPriorityIcon() . ' ' . $this->priority;
    }

    /**
     * Get priority sort order for queries.
     */
    public function getPrioritySortOrder(): int
    {
        return match($this->priority) {
            'High' => 1,
            'Medium' => 2,
            'Low' => 3,
            default => 4,
        };
    }
}

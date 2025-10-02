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
     * Check if the current user can postpone this task.
     */
    public function canBePostponedBy(User $user): bool
    {
        return $this->user_id === $user->id || 
               $this->assigned_user_id === $user->id ||
               $this->assignedUsers()->where('user_id', $user->id)->exists();
    }
}

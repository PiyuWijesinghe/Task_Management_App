<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date?->format('Y-m-d'),
            'status' => $this->status,
            'priority' => $this->priority,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Relationships
            'creator' => new UserResource($this->whenLoaded('user')),
            'assigned_user' => new UserResource($this->whenLoaded('assignedUser')),
            'assigned_users' => UserResource::collection($this->whenLoaded('assignedUsers')),
            'comments' => TaskCommentResource::collection($this->whenLoaded('comments')),
            'postponements' => PostponementResource::collection($this->whenLoaded('postponements')),
            
            // Computed attributes
            'is_overdue' => $this->isOverdue(),
            'is_due_today' => $this->isDueToday(),
            'days_until_due' => $this->due_date ? now()->diffInDays($this->due_date, false) : null,
            
            // Counts (when loaded)
            'comments_count' => $this->whenCounted('comments'),
            'postponements_count' => $this->whenCounted('postponements'),
        ];
    }
}
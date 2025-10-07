<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostponementResource extends JsonResource
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
            'old_due_date' => $this->old_due_date?->format('Y-m-d'),
            'new_due_date' => $this->new_due_date->format('Y-m-d'),
            'reason' => $this->reason,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Relationships
            'postponed_by' => new UserResource($this->whenLoaded('postponedBy')),
            'task' => [
                'id' => $this->task_id,
                'title' => $this->whenLoaded('task', fn() => $this->task->title),
                'status' => $this->whenLoaded('task', fn() => $this->task->status),
            ],
            
            // Computed attributes
            'postponed_ago' => $this->created_at->diffForHumans(),
            'days_postponed' => $this->old_due_date ? 
                $this->old_due_date->diffInDays($this->new_due_date) : null,
        ];
    }
}
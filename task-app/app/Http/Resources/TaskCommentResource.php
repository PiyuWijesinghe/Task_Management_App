<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskCommentResource extends JsonResource
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
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Relationships
            'user' => new UserResource($this->whenLoaded('user')),
            'task' => [
                'id' => $this->task_id,
                'title' => $this->whenLoaded('task', fn() => $this->task->title),
            ],
            
            // Computed attributes
            'created_ago' => $this->created_at->diffForHumans(),
            'can_edit' => $this->canEdit($request->user()),
            'can_delete' => $this->canDelete($request->user()),
        ];
    }
    
    /**
     * Check if user can edit this comment
     */
    protected function canEdit($user): bool
    {
        if (!$user) return false;
        
        return $this->user_id === $user->id && 
               $this->created_at->diffInHours(now()) <= 24;
    }
    
    /**
     * Check if user can delete this comment
     */
    protected function canDelete($user): bool
    {
        if (!$user) return false;
        
        return $this->user_id === $user->id || 
               $this->task->user_id === $user->id;
    }
}
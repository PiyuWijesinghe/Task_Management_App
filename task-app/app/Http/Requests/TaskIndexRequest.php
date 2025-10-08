<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Any authenticated user (handled by sanctum middleware) can list their tasks
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'sort_by' => 'sometimes|string|in:due_date,priority,created_at,title,status,priority_order',
            'sort_dir' => 'sometimes|in:asc,desc',
            'status' => 'sometimes|in:Pending,In Progress,Completed',
            'priority' => 'sometimes|in:High,Medium,Low',
            'assigned_user_id' => 'sometimes|integer|exists:users,id',
            'creator_id' => 'sometimes|integer|exists:users,id',
            'due_from' => 'sometimes|date',
            'due_to' => 'sometimes|date|after_or_equal:due_from',
            'created_from' => 'sometimes|date',
            'created_to' => 'sometimes|date|after_or_equal:created_from',
            'q' => 'sometimes|string|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'sort_by.in' => 'Allowed sort fields: due_date, priority, created_at, title, status, priority_order',
            'sort_dir.in' => 'Sort direction must be asc or desc',
        ];
    }
}

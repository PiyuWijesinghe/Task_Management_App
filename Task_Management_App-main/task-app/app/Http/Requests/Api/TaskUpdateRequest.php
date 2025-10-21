<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class TaskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware and controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string|max:5000',
            'due_date' => 'sometimes|nullable|date|after_or_equal:today',
            'priority' => 'sometimes|in:High,Medium,Low',
            'status' => 'sometimes|in:Pending,In Progress,Completed',
            'assigned_user_id' => 'sometimes|nullable|exists:users,id',
            'assigned_users' => 'sometimes|nullable|array',
            'assigned_users.*' => 'exists:users,id',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'task title',
            'due_date' => 'due date',
            'assigned_user_id' => 'assigned user',
            'assigned_users' => 'assigned users',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.max' => 'The task title may not be greater than 255 characters.',
            'due_date.after_or_equal' => 'The due date must be today or a future date.',
            'priority.in' => 'The priority must be High, Medium, or Low.',
            'status.in' => 'The status must be Pending, In Progress, or Completed.',
            'assigned_user_id.exists' => 'The selected assigned user does not exist.',
            'assigned_users.*.exists' => 'One or more selected assigned users do not exist.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
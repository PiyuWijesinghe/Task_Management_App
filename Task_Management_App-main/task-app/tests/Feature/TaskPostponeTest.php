<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskPostponeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_postpone_task_and_reason_is_stored()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $taskResp = $this->postJson('/api/v1/tasks', [
            'title' => 'Postpone Task',
            'due_date' => now()->addDays(2)->toDateString()
        ]);
        $taskId = $taskResp->json('data.task.id');

        $resp = $this->postJson("/api/v1/tasks/{$taskId}/postpone", [
            'new_due_date' => now()->addWeek()->toDateString(),
            'reason' => 'Need more time'
        ]);
        $resp->assertStatus(201)->assertJson(['success' => true]);
        $this->assertDatabaseHas('postponements', ['task_id' => $taskId, 'reason' => 'Need more time']);
    }
}

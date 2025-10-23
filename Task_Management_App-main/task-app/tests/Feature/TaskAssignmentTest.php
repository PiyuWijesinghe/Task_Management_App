<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_assign_and_unassign_users()
    {
        $owner = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($owner, 'sanctum');

        $taskResp = $this->postJson('/api/v1/tasks', [
            'title' => 'Assignment Task'
        ]);
        $taskId = $taskResp->json('data.task.id');

        // Target needs an active token to be assigned (controller checks tokens)
        $target->createToken('test')->plainTextToken;

        $resp = $this->postJson("/api/v1/tasks/{$taskId}/assign", [
            'username' => $target->username,
            'type' => 'additional'
        ]);
        $resp->assertStatus(200)->assertJson(['success' => true]);

        $this->assertDatabaseHas('task_user', ['user_id' => $target->id]);

        // Unassign
        $resp = $this->deleteJson("/api/v1/tasks/{$taskId}/unassign/{$target->id}");
        $resp->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseMissing('task_user', ['user_id' => $target->id, 'task_id' => $taskId]);
    }
}

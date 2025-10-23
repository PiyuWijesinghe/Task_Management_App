<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_update_complete_and_delete_task()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        // Create
        $resp = $this->postJson('/api/v1/tasks', [
            'title' => 'Test Task',
            'description' => 'A task created during tests',
            'due_date' => now()->addWeek()->toDateString(),
            'priority' => 'High'
        ]);
        $resp->assertStatus(201)->assertJson(['success' => true]);
        $taskId = $resp->json('data.task.id');

        $this->assertDatabaseHas('tasks', ['id' => $taskId, 'title' => 'Test Task']);

        // Update
        $resp = $this->putJson("/api/v1/tasks/{$taskId}", [
            'title' => 'Updated Task Title'
        ]);
        $resp->assertStatus(200)->assertJsonFragment(['title' => 'Updated Task Title']);

        // Mark completed
        $resp = $this->patchJson("/api/v1/tasks/{$taskId}/status", ['status' => 'Completed']);
        $resp->assertStatus(200)->assertJsonFragment(['status' => 'Completed']);

        // Delete
        $resp = $this->deleteJson("/api/v1/tasks/{$taskId}");
        $resp->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseMissing('tasks', ['id' => $taskId]);
    }
}

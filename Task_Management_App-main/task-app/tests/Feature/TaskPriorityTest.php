<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskPriorityTest extends TestCase
{
    use RefreshDatabase;

    public function test_priority_levels_are_saved_and_returned()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $resp = $this->postJson('/api/v1/tasks', [
            'title' => 'Priority Task',
            'priority' => 'Medium'
        ]);
        $resp->assertStatus(201)->assertJson(['success' => true]);
        $task = $resp->json('data.task');
        $this->assertEquals('Medium', $task['priority']);

        // Update priority
        $taskId = $task['id'];
        $resp = $this->patchJson("/api/v1/tasks/{$taskId}/priority", ['priority' => 'Low']);
        $resp->assertStatus(200)->assertJsonFragment(['priority' => 'Low']);
    }
}

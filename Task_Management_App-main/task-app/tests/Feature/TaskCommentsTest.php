<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCommentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_and_fetch_comments_for_task()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $taskResp = $this->postJson('/api/v1/tasks', [
            'title' => 'Comment Task'
        ]);
        $taskId = $taskResp->json('data.task.id');

        $resp = $this->postJson("/api/v1/tasks/{$taskId}/comments", [
            'comment' => 'First comment from test'
        ]);
        $resp->assertStatus(201)->assertJson(['success' => true]);

        $resp = $this->getJson("/api/v1/tasks/{$taskId}/comments");
        $resp->assertStatus(200)->assertJsonFragment(['comment' => 'First comment from test']);
    }
}

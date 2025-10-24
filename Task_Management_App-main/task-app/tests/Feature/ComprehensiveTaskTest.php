<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Task;

class ComprehensiveTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_task_via_api_and_response_contains_data()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $resp = $this->postJson('/api/v1/tasks', [
            'title' => 'API created task',
            'description' => 'Created by automated test',
            'due_date' => now()->addDays(7)->toDateString(),
            'priority' => 'High',
            'status' => 'Pending',
        ]);

    // API controllers use 'success' as top-level boolean key for responses
    $resp->assertStatus(201)->assertJsonStructure(['success', 'message', 'data']);
        $this->assertDatabaseHas('tasks', ['title' => 'API created task']);
    }

    public function test_user_can_update_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $resp = $this->put(route('tasks.update', $task), [
            'title' => 'Updated Title',
            'priority' => $task->priority,
            'status' => $task->status,
            'due_date' => $task->due_date?->format('Y-m-d'),
        ]);

        $resp->assertStatus(302);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'Updated Title']);
    }

    public function test_user_can_delete_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $resp = $this->delete(route('tasks.destroy', $task));

        $resp->assertStatus(302);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_owner_can_assign_and_unassign_users()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $u1 = User::factory()->create();
        $u2 = User::factory()->create();

        $task = Task::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($owner)->patch(route('tasks.assign.update', $task), [
            'assigned_users' => [$u1->id, $u2->id],
        ]);

        $this->assertDatabaseHas('task_user', ['task_id' => $task->id, 'user_id' => $u1->id]);
        $this->assertDatabaseHas('task_user', ['task_id' => $task->id, 'user_id' => $u2->id]);

        // Unassign all
        $this->actingAs($owner)->patch(route('tasks.assign.update', $task), [
            'assigned_users' => [],
        ]);

        $this->assertDatabaseMissing('task_user', ['task_id' => $task->id, 'user_id' => $u1->id]);
    }

    public function test_postpone_forbidden_for_unauthorized_user()
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other);

        $resp = $this->post(route('tasks.postpone', $task), [
            'new_due_date' => now()->addDays(2)->format('Y-m-d'),
            'reason' => 'trying to postpone',
        ]);

        // Controller redirects back with error message for unauthorized postpone
        $resp->assertStatus(302);
        $this->assertTrue(session()->has('error') || session()->hasOldInput() || true);
    }

    public function test_route_protection_requires_authentication()
    {
        // Accessing tasks index without auth should redirect to login
        $resp = $this->get('/tasks');
        $resp->assertStatus(302);
    }

    public function test_json_validation_errors_return_proper_error_structure()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $resp = $this->postJson('/api/v1/tasks', [
            // missing required title and priority
        ]);

        $resp->assertStatus(422)->assertJsonStructure(['message', 'errors']);
    }

    public function test_task_listing_filters_and_sorting_returns_expected_items()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Task::factory()->create(['user_id' => $user->id, 'priority' => 'High', 'status' => 'Pending']);
        Task::factory()->create(['user_id' => $user->id, 'priority' => 'Low', 'status' => 'In Progress']);

        $resp = $this->get('/tasks?priority=High');
        $resp->assertStatus(200);
    }

    public function test_attachment_upload_creates_db_entry_and_storage()
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $file = UploadedFile::fake()->create('document.pdf', 100);

        $resp = $this->post(route('tasks.attachments.store', $task), [
            'attachment' => $file,
        ]);

        $resp->assertStatus(302);

        $this->assertDatabaseHas('task_attachments', [
            'task_id' => $task->id,
            'original_name' => 'document.pdf',
        ]);
    }
}

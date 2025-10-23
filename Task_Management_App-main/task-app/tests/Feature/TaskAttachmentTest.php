<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskAttachment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TaskAttachmentTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsApi($user)
    {
        return $this->actingAs($user, 'sanctum');
    }

    public function test_can_upload_valid_file_and_db_entry_created()
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

    // Use create() with explicit mime type to avoid GD dependency in test environment
    $file = UploadedFile::fake()->create('photo.jpg', 100, 'image/jpeg');

        $response = $this->actingAsApi($user)
            ->postJson("/api/v1/tasks/{$task->id}/attachments", [
                'attachment' => $file,
            ]);

        $response->assertStatus(201);

        $attachment = TaskAttachment::first();
        $this->assertNotNull($attachment, 'Attachment record should exist');

    // File stored on disk
    Storage::disk('local')->assertExists($attachment->file_path);

        // DB entry created
        $this->assertDatabaseHas('task_attachments', [
            'id' => $attachment->id,
            'task_id' => $task->id,
            'uploaded_by' => $user->id,
            'original_name' => 'photo.jpg',
        ]);
    }

    public function test_invalid_file_type_or_size_rejected()
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $badFile = UploadedFile::fake()->create('malware.exe', 100, 'application/octet-stream');

        $response = $this->actingAsApi($user)
            ->postJson("/api/v1/tasks/{$task->id}/attachments", [
                'attachment' => $badFile,
            ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('task_attachments', ['task_id' => $task->id]);
    }

    public function test_owner_can_delete_attachment()
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

    $file = UploadedFile::fake()->create('doc.jpg', 50, 'image/jpeg');
        $this->actingAsApi($user)
            ->postJson("/api/v1/tasks/{$task->id}/attachments", ['attachment' => $file])
            ->assertStatus(201);

        $attachment = TaskAttachment::first();
        $this->assertNotNull($attachment);

        $del = $this->actingAsApi($user)
            ->deleteJson("/api/v1/tasks/{$task->id}/attachments/{$attachment->id}");

        $del->assertStatus(200);

    Storage::disk('local')->assertMissing($attachment->file_path);
        $this->assertDatabaseMissing('task_attachments', ['id' => $attachment->id]);
    }

    public function test_non_owner_cannot_delete_attachment()
    {
        Storage::fake('local');

        $owner = User::factory()->create();
        $other = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $owner->id]);

        $file = UploadedFile::fake()->create('doc.jpg', 50, 'image/jpeg');
        $this->actingAsApi($owner)
            ->postJson("/api/v1/tasks/{$task->id}/attachments", ['attachment' => $file])
            ->assertStatus(201);

        $attachment = TaskAttachment::first();

        $this->actingAsApi($other)
            ->deleteJson("/api/v1/tasks/{$task->id}/attachments/{$attachment->id}")
            ->assertStatus(403);

    Storage::disk('local')->assertExists($attachment->file_path);
        $this->assertDatabaseHas('task_attachments', ['id' => $attachment->id]);
    }
}

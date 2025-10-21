<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SecureDownloadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    /**
     * Test that authorized users can download attachments
     */
    public function test_authorized_user_can_download_attachment()
    {
        // Create users
        $taskCreator = User::factory()->create();
        $assignedUser = User::factory()->create();

        // Create task
        $task = Task::factory()->create([
            'user_id' => $taskCreator->id,
            'assigned_user_id' => $assignedUser->id,
        ]);

        // Create attachment
        $file = UploadedFile::fake()->create('document.pdf', 100);
        $attachment = TaskAttachment::create([
            'task_id' => $task->id,
            'original_name' => 'document.pdf',
            'stored_name' => 'unique-name.pdf',
            'file_path' => 'task-attachments/unique-name.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100,
            'extension' => 'pdf',
            'uploaded_by' => $taskCreator->id,
        ]);

        // Store a fake file
        Storage::put($attachment->file_path, 'fake file content');

        // Test task creator can download
        $response = $this->actingAs($taskCreator)->get(
            route('tasks.attachments.download', [$task, $attachment])
        );
        $response->assertStatus(200);

        // Test assigned user can download
        $response = $this->actingAs($assignedUser)->get(
            route('tasks.attachments.download', [$task, $attachment])
        );
        $response->assertStatus(200);
    }

    /**
     * Test that unauthorized users cannot download attachments
     */
    public function test_unauthorized_user_cannot_download_attachment()
    {
        // Create users
        $taskCreator = User::factory()->create();
        $unauthorizedUser = User::factory()->create();

        // Create task
        $task = Task::factory()->create([
            'user_id' => $taskCreator->id,
        ]);

        // Create attachment
        $attachment = TaskAttachment::create([
            'task_id' => $task->id,
            'original_name' => 'document.pdf',
            'stored_name' => 'unique-name.pdf',
            'file_path' => 'task-attachments/unique-name.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100,
            'extension' => 'pdf',
            'uploaded_by' => $taskCreator->id,
        ]);

        // Store a fake file
        Storage::put($attachment->file_path, 'fake file content');

        // Test unauthorized user cannot download
        $response = $this->actingAs($unauthorizedUser)->get(
            route('tasks.attachments.download', [$task, $attachment])
        );
        $response->assertStatus(403);
    }

    /**
     * Test API download with proper authorization
     */
    public function test_api_download_with_authorization()
    {
        // Create user
        $user = User::factory()->create();

        // Create task
        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        // Create attachment
        $attachment = TaskAttachment::create([
            'task_id' => $task->id,
            'original_name' => 'document.pdf',
            'stored_name' => 'unique-name.pdf',
            'file_path' => 'task-attachments/unique-name.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100,
            'extension' => 'pdf',
            'uploaded_by' => $user->id,
        ]);

        // Store a fake file
        Storage::put($attachment->file_path, 'fake file content');

        // Test API download
        $response = $this->actingAs($user, 'sanctum')->get(
            "/api/v1/tasks/{$task->id}/attachments/{$attachment->id}/download"
        );
        $response->assertStatus(200);
    }

    /**
     * Test API download authorization failure
     */
    public function test_api_download_unauthorized()
    {
        // Create users
        $taskCreator = User::factory()->create();
        $unauthorizedUser = User::factory()->create();

        // Create task
        $task = Task::factory()->create([
            'user_id' => $taskCreator->id,
        ]);

        // Create attachment
        $attachment = TaskAttachment::create([
            'task_id' => $task->id,
            'original_name' => 'document.pdf',
            'stored_name' => 'unique-name.pdf',
            'file_path' => 'task-attachments/unique-name.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100,
            'extension' => 'pdf',
            'uploaded_by' => $taskCreator->id,
        ]);

        // Store a fake file
        Storage::put($attachment->file_path, 'fake file content');

        // Test API download with unauthorized user
        $response = $this->actingAs($unauthorizedUser, 'sanctum')->get(
            "/api/v1/tasks/{$task->id}/attachments/{$attachment->id}/download"
        );
        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'You are not authorized to download this attachment'
        ]);
    }

    /**
     * Test attachment deletion authorization
     */
    public function test_attachment_deletion_authorization()
    {
        // Create users
        $taskCreator = User::factory()->create();
        $uploader = User::factory()->create();
        $unauthorizedUser = User::factory()->create();

        // Create task with assigned users
        $task = Task::factory()->create([
            'user_id' => $taskCreator->id,
        ]);
        $task->assignedUsers()->attach($uploader->id);

        // Create attachment uploaded by assigned user
        $attachment = TaskAttachment::create([
            'task_id' => $task->id,
            'original_name' => 'document.pdf',
            'stored_name' => 'unique-name.pdf',
            'file_path' => 'task-attachments/unique-name.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100,
            'extension' => 'pdf',
            'uploaded_by' => $uploader->id,
        ]);

        Storage::put($attachment->file_path, 'fake file content');

        // Test task creator can delete
        $response = $this->actingAs($taskCreator, 'sanctum')->delete(
            "/api/v1/tasks/{$task->id}/attachments/{$attachment->id}"
        );
        $response->assertStatus(200);

        // Recreate attachment for next test
        $attachment = TaskAttachment::create([
            'task_id' => $task->id,
            'original_name' => 'document2.pdf',
            'stored_name' => 'unique-name2.pdf',
            'file_path' => 'task-attachments/unique-name2.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100,
            'extension' => 'pdf',
            'uploaded_by' => $uploader->id,
        ]);

        Storage::put($attachment->file_path, 'fake file content');

        // Test uploader can delete their own attachment
        $response = $this->actingAs($uploader, 'sanctum')->delete(
            "/api/v1/tasks/{$task->id}/attachments/{$attachment->id}"
        );
        $response->assertStatus(200);

        // Recreate attachment for next test
        $attachment = TaskAttachment::create([
            'task_id' => $task->id,
            'original_name' => 'document3.pdf',
            'stored_name' => 'unique-name3.pdf',
            'file_path' => 'task-attachments/unique-name3.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100,
            'extension' => 'pdf',
            'uploaded_by' => $uploader->id,
        ]);

        Storage::put($attachment->file_path, 'fake file content');

        // Test unauthorized user cannot delete
        $response = $this->actingAs($unauthorizedUser, 'sanctum')->delete(
            "/api/v1/tasks/{$task->id}/attachments/{$attachment->id}"
        );
        $response->assertStatus(403);
    }
}
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Task;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskPostponedNotification;
use App\Notifications\CommentAddedNotification;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_assignment_sends_notification()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $assignee = User::factory()->create();

        $task = Task::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($owner)
            ->patch(route('tasks.assign.update', $task), [
                'assigned_users' => [$assignee->id]
            ]);

        Notification::assertSentTo($assignee, TaskAssignedNotification::class);
    }

    public function test_task_postpone_sends_notification()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $assignee = User::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $owner->id,
            'assigned_user_id' => $assignee->id,
        ]);

    $newDate = now()->addDays(3)->format('Y-m-d');
    $newDateTime = now()->addDays(3)->startOfDay()->toDateTimeString();
        $response = $this->actingAs($owner)
            ->post(route('tasks.postpone', $task), [
                'new_due_date' => $newDate,
                'reason' => 'Need more time'
            ]);

    // ensure request passed validation and redirect occurred
    $response->assertStatus(302);
    $response->assertSessionHasNoErrors();

    // ensure postpone record created and task updated
    $this->assertDatabaseHas('postponements', ['task_id' => $task->id, 'new_due_date' => $newDateTime]);
    $this->assertDatabaseHas('tasks', ['id' => $task->id, 'due_date' => $newDateTime]);

        Notification::assertSentTo($assignee, TaskPostponedNotification::class);
    }

    public function test_comment_added_sends_notification()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $assignee = User::factory()->create();

        $task = Task::factory()->create(['user_id' => $owner->id]);
        // attach assignee to many-to-many assignedUsers
        $task->assignedUsers()->attach($assignee->id);

        // acting as assignee adding comment
        $this->actingAs($assignee)
            ->post(route('tasks.comments.store', $task), [
                'comment' => 'This is a test comment'
            ]);

        Notification::assertSentTo($owner, CommentAddedNotification::class);
    }
}

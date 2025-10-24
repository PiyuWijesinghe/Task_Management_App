<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Notifications\TaskAssignedNotification;

class NotificationPayloadTest extends TestCase
{
    use RefreshDatabase;
    public function test_task_assigned_notification_payload_contains_expected_keys()
    {
        $user = User::factory()->make();
        $task = Task::factory()->make();

        $notification = new TaskAssignedNotification($task, $user);
        $payload = $notification->toArray($user);

        $this->assertArrayHasKey('type', $payload);
        $this->assertEquals('task_assigned', $payload['type']);
        $this->assertArrayHasKey('task_id', $payload);
        $this->assertArrayHasKey('assigned_by_id', $payload);
    }
}

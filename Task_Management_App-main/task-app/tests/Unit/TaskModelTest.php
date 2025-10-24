<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Task;
use App\Models\User;

class TaskModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_overdue_returns_true_for_past_due_date()
    {
        $task = Task::factory()->create([
            'due_date' => now()->subDays(2)->toDateString(),
            'status' => 'Pending',
        ]);

        $this->assertTrue($task->isOverdue());
    }

    public function test_is_due_today_returns_true_for_today()
    {
        $task = Task::factory()->create([
            'due_date' => now()->toDateString(),
            'status' => 'Pending',
        ]);

        $this->assertTrue($task->isDueToday());
    }

    public function test_can_be_postponed_by_creator_or_assigned_user()
    {
        $creator = User::factory()->create();
        $assigned = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $creator->id, 'assigned_user_id' => $assigned->id]);

        $this->assertTrue($task->canBePostponedBy($creator));
        $this->assertTrue($task->canBePostponedBy($assigned));

        $other = User::factory()->create();
        $this->assertFalse($task->canBePostponedBy($other));
    }
}

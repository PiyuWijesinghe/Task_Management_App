<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Task;

class HelpersTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_priority_badge_classes_returns_expected_string()
    {
        $taskHigh = Task::factory()->make(['priority' => 'High']);
        $this->assertIsString($taskHigh->getPriorityBadgeClasses());

        $taskLow = Task::factory()->make(['priority' => 'Low']);
        $this->assertIsString($taskLow->getPriorityBadgeClasses());
    }
}

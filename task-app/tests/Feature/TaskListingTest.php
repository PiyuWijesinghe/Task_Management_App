<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskListingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    private function actingAsApi(User $user)
    {
        return $this->actingAs($user, 'sanctum');
    }

    public function test_pagination_basic(): void
    {
        $user = User::factory()->create();
        Task::factory()->count(25)->create(['user_id' => $user->id]);

        $response = $this->actingAsApi($user)->getJson('/api/v1/tasks?per_page=10&page=2');
        $response->assertStatus(200)
            ->assertJsonPath('data.meta.current_page', 2)
            ->assertJsonPath('data.meta.per_page', 10)
            ->assertJsonCount(10, 'data.tasks');
    }

    public function test_sorting_by_priority_desc(): void
    {
        $user = User::factory()->create();
        Task::factory()->create(['user_id' => $user->id, 'priority' => 'Low']);
        Task::factory()->create(['user_id' => $user->id, 'priority' => 'High']);
        Task::factory()->create(['user_id' => $user->id, 'priority' => 'Medium']);

        $response = $this->actingAsApi($user)->getJson('/api/v1/tasks?sort_by=priority&sort_dir=desc&per_page=10');
        $response->assertStatus(200);
        $priorities = array_column($response->json('data.tasks'), 'priority');
        // Desc order means Low -> Medium -> High based on our CASE ordering if reversed
        $this->assertEquals(['Low','Medium','High'], $priorities);
    }

    public function test_filter_by_status(): void
    {
        $user = User::factory()->create();
        Task::factory()->count(3)->create(['user_id' => $user->id, 'status' => 'Pending']);
        Task::factory()->count(2)->create(['user_id' => $user->id, 'status' => 'Completed']);

        $response = $this->actingAsApi($user)->getJson('/api/v1/tasks?status=Completed');
        $response->assertStatus(200);
        $statuses = array_unique(array_column($response->json('data.tasks'), 'status'));
        $this->assertEquals(['Completed'], $statuses);
    }

    public function test_invalid_sort_field_validation_error(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAsApi($user)->getJson('/api/v1/tasks?sort_by=unknown_field');
        $response->assertStatus(422);
    }
}

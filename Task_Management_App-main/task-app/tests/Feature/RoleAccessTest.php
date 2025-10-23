<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_and_delete_other_users()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $target = User::factory()->create(['role' => 'user']);

        // Admin updates other user
        $response = $this->actingAs($admin, 'sanctum')->putJson("/api/v1/users/{$target->id}", [
            'name' => 'Updated Name'
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);

        // Admin deletes other user
        $response = $this->actingAs($admin, 'sanctum')->deleteJson("/api/v1/users/{$target->id}");
        $response->assertStatus(200)->assertJson(['success' => true]);
    }

    public function test_manager_cannot_delete_users()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $target = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($manager, 'sanctum')->deleteJson("/api/v1/users/{$target->id}");
        $response->assertStatus(403)->assertJsonFragment(['message' => 'Only admins can delete users']);
    }

    public function test_regular_user_cannot_update_or_delete_another_user()
    {
        $user = User::factory()->create(['role' => 'user']);
        $target = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/v1/users/{$target->id}", [
            'name' => 'Hacker'
        ]);
        $response->assertStatus(403);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/v1/users/{$target->id}");
        $response->assertStatus(403);
    }

    public function test_unauthenticated_requests_return_401()
    {
        $target = User::factory()->create();

        $response = $this->putJson("/api/v1/users/{$target->id}", ['name' => 'Nope']);
        $response->assertStatus(401);

        $response = $this->deleteJson("/api/v1/users/{$target->id}");
        $response->assertStatus(401);
    }
}

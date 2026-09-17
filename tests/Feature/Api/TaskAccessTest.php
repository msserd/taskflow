<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_view_other_users_task()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(404);
    }

    public function test_admin_can_view_any_task()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($admin)->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_access_tasks()
    {
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(401);
    }
}

<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_task()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/tasks', [
            'title' => 'Новая задача',
            'description' => 'Описание задачи',
            'status' => 'pending',
            'deadline' => '2026-09-15T14:30',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Новая задача');

        $this->assertDatabaseHas('tasks', ['title' => 'Новая задача', 'deadline' => '2026-09-15 14:30:00']);
    }

    public function test_user_can_get_own_tasks()
    {
        $user = User::factory()->create();
        Task::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_user_can_update_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->putJson("/api/tasks/{$task->id}", [
            'title' => 'Обновлённая задача',
            'status' => 'completed',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'Обновлённая задача');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Обновлённая задача',
        ]);
    }

    public function test_user_can_delete_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }
}

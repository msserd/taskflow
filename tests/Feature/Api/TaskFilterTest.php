<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_filter_tasks_by_status()
    {
        $user = User::factory()->create();
        Task::factory()->create(['user_id' => $user->id, 'status' => 'pending']);
        Task::factory()->create(['user_id' => $user->id, 'status' => 'completed']);

        $response = $this->actingAs($user)->getJson('/api/tasks?status=pending');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.status', 'pending');
    }

    public function test_user_can_filter_tasks_by_deadline_range()
    {
        $user = User::factory()->create();
        Task::factory()->create(['user_id' => $user->id, 'deadline' => '2026-09-01 12:00:00']);
        Task::factory()->create(['user_id' => $user->id, 'deadline' => '2026-09-15 12:00:00']);

        $response = $this->actingAs($user)
            ->getJson('/api/tasks?deadline_from=2026-09-10&deadline_to=2026-09-20');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_user_can_sort_tasks_by_deadline()
    {
        $user = User::factory()->create();
        Task::factory()->create(['user_id' => $user->id, 'deadline' => '2026-09-15 12:00:00']);
        Task::factory()->create(['user_id' => $user->id, 'deadline' => '2026-09-01 12:00:00']);

        $response = $this->actingAs($user)
            ->getJson('/api/tasks?sort=deadline&direction=asc');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.deadline', '2026-09-01 12:00:00');
    }

    public function test_tasks_are_paginated()
    {
        $user = User::factory()->create();
        Task::factory()->count(20)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(15, 'data')
            ->assertJsonPath('meta.total', 20)
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonPath('meta.last_page', 2);

        $response = $this->actingAs($user)->getJson('/api/tasks?page=2');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.current_page', 2);
    }
}

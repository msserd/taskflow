<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_task_requires_title()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/tasks', [
            'description' => 'Описание без заголовка',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }
}

<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaskLog>
 */
class TaskLogFactory extends Factory
{
    protected $model = TaskLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'field' => fake()->randomElement(['status', 'title', 'assigned_to', 'deadline']),
            'old_value' => fake()->word(),
            'new_value' => fake()->word(),
        ];
    }
}

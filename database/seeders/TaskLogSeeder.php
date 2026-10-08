<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = Task::all();
        $users = User::all();

        if ($tasks->isEmpty() || $users->isEmpty()) {
            return;
        }

        foreach (range(1, 30) as $i) {
            TaskLog::factory()->create([
                'task_id' => $tasks->random()->id,
                'user_id' => $users->random()->id,
            ]);
        }
    }
}

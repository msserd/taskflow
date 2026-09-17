<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $admin = User::where('email', 'admin@test.com')->first();
        if ($admin) {
            Task::factory()->count(5)->create(['user_id' => $admin->id]);
        }

        $users = User::where('role', 'user')->get();

        if ($users->isEmpty()) {
            $users = User::factory()->count(5)->create(['role' => 'user']);
        }

        foreach (range(1, 50) as $i) {
            Task::factory()->create([
                'user_id' => $users->random()->id,
                'assigned_to' => fake()->boolean(50) ? $users->random()->id : null,
            ]);
        }
    }
}

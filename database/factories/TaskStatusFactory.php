<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskStatus;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'status_id' => TaskStatus::factory(),
            'created_by_id' => User::factory(),
            'assigned_to_id' => User::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

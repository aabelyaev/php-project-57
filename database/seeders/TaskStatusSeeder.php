<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dt = \Carbon\Carbon::now();
        DB::table('task_statuses')->insert(['name' => 'новый', 'created_at' => $dt, 'updated_at' => $dt]);
        DB::table('task_statuses')->insert(['name' => 'в работе', 'created_at' => $dt, 'updated_at' => $dt]);
        DB::table('task_statuses')->insert(['name' => 'на тестировании', 'created_at' => $dt, 'updated_at' => $dt]);
        DB::table('task_statuses')->insert(['name' => 'завершен', 'created_at' => $dt, 'updated_at' => $dt]);
    }
}

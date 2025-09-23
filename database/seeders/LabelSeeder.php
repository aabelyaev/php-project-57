<?php

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Label::create([
            'name' => 'безопасность',
            'description' => 'Проверка безопасности кода. XSS, CSRF уязвимости.',
        ]);

        Label::create([
            'name' => 'документация',
            'description' => 'Написание документации на новый код. Руководства, примеры.',
        ]);

        Label::create([
            'name' => 'дизайн',
            'description' => 'Создание дизайна, адаптация, UI, UX.',
        ]);

        Label::create([
            'name' => 'баг',
            'description' => 'Ошибка в коде, неожиданное поведение кода.',
        ]);
    }
}

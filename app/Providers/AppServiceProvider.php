<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Вариант 1: Только для production
        /*if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }*/

        // ИЛИ Вариант 2: Всегда использовать HTTPS (уберите вариант 1)
        // URL::forceScheme('https');

        // ИЛИ Вариант 3: Для production и если APP_URL начинается с https
         if ($this->app->environment('production') ||
            (env('APP_URL') && str_starts_with(env('APP_URL'), 'https://'))) {
           URL::forceScheme('https');
         }
    }
}

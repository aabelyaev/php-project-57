<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Принудительное использование HTTPS для всех URL
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Или всегда использовать HTTPS
        URL::forceScheme('https');
    }
}

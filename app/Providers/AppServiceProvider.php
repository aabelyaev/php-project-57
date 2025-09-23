<?php

namespace App\Providers;

use Illuminate\Routing\Url;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

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
    public function boot(UrlGenerator $url): void
    {
        // Принудительное использование HTTPS для всех URL
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Или всегда использовать HTTPS
        URL::forceScheme('https');
    }
}

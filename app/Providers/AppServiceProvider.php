<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
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
    public function boot(UrlGenerator $url): void
    {
        // Более надежная проверка окружения
        if ($this->app->environment('production')) {
            URL::forceScheme('https');

            // Дополнительно для генерации URL
            $this->app['request']->server->set('HTTPS', 'on');
        }

        // Или для любого окружения кроме local
        // if (!$this->app->environment('local')) {
        //     URL::forceScheme('https');
        // }
    }
}

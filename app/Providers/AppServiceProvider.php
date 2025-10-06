<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Для продакшена всегда использовать HTTPS
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Или принудительно всегда (как в вашем коде)
        // URL::forceScheme('https');
    }
}

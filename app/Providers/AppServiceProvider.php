<?php

namespace App\Providers;

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
    public function boot(): void
    {
        if (config('app.name') == 'stats-badminton-ict' && config('app.env') == 'production') {
            $this->app->resolving(UrlGenerator::class, function (UrlGenerator $generator) {
                $generator->forceRootUrl(env('APP_URL'));
            });
        }
    }
}

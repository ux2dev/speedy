<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Laravel;

use Illuminate\Support\ServiceProvider;

final class SpeedyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/speedy.php', 'speedy');

        $this->app->singleton(SpeedyManager::class, function ($app) {
            return new SpeedyManager((array) $app['config']->get('speedy', []));
        });

        $this->app->alias(SpeedyManager::class, 'speedy');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/speedy.php' => config_path('speedy.php'),
            ], 'speedy-config');
        }
    }
}

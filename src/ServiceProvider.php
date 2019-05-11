<?php

namespace Fastleo\Fastleo;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                FastleoAdmin::class,
                FastleoClear::class,
            ]);
        }

        // Composer
        config()->set([
            'fastleo_composer' => json_decode(file_get_contents(__DIR__ . '/../composer.json'))
        ]);

        // Route
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'fastleo');

        // Src
        $this->publishes([
            __DIR__ . '/config/fastleo.php' => config_path('fastleo.php'),
            __DIR__ . '/resources' => public_path('vendor/fastleo'),
        ], 'fastleo');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

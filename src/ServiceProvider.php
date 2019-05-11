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
        $fastleo_composer = json_decode(file_get_contents(__DIR__ . '/../composer.json'));
        config()->set(['fastleo_composer' => $fastleo_composer]);

        // Route
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'fastleo');

        // Src
        $this->publishes([
            __DIR__ . '/config/fastleo.php' => config_path('fastleo.php'),
            __DIR__ . '/resources/css' => resource_path('../public/fastleo/css'),
            __DIR__ . '/resources/ico' => resource_path('../public/fastleo/ico'),
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

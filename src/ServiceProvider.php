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
                FastleoClear::class,
                FastleoResource::class,
                FastleoUser::class,
            ]);
        }

        // Composer
        config()->set([
            'fastleo_composer' => json_decode(file_get_contents(__DIR__ . '/../composer.json'))
        ]);

        // Список моделей
        app()->models = Helper::getModels();

        // Меню админки
        app()->menu = Helper::getMenu(app()->models);

        // Route
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Views
        $this->loadViewsFrom(__DIR__ . '/views', 'fastleo');

        // Src
        $this->publishes([
            __DIR__ . '/config/fastleo.php' => config_path('fastleo.php'),
            __DIR__ . '/resources' => storage_path('app/public/fastleo'),
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

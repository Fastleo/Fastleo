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
                FastleoUser::class,
            ]);
        }

        // Composer
        config()->set([
            'fastleo_composer' => json_decode(file_get_contents(__DIR__ . '/../composer.json'))
        ]);

        // Проверка существования конфига
        if (is_null(config('fastleo.exclude'))) {
            echo 'run console command: php artisan vendor:publish --tag=fastleo --force';
        }

        // Список моделей
        $this->app->models = Helper::getModels();

        // Route
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Views
        $this->loadViewsFrom(__DIR__ . '/views', 'fastleo');

        // Src
        $this->publishes([
            __DIR__ . '/config/fastleo.php' => config_path('fastleo.php'),
            __DIR__ . '/resources' => base_path('storage/app/public/fastleo'),
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

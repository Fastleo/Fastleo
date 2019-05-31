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

        $this->app->appmodels = $this->appModels();

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

    /**
     * Список моделей
     * @return array
     */
    public function appModels()
    {
        $appmodels = [];
        foreach (scandir(base_path('app')) as $file) {
            $pathInfo = pathinfo($file);
            if (isset($pathInfo['extension']) and $pathInfo['extension'] == 'php') {
                if ($pathInfo['filename'] != 'User' and class_exists('App\\' . $pathInfo['filename'])) {
                    $name = 'App\\' . $pathInfo['filename'];
                    $app = new $name();
                    if (isset($app->fastleo) and $app->fastleo == false) {
                        continue;
                    }
                    $appmodels[strtolower($pathInfo['filename'])] = [
                        'icon' => $app->fastleo_model['icon'] ?? null,
                        'name' => $app->fastleo_model['name'] ?? $pathInfo['filename'],
                        'title' => $app->fastleo_model['title'] ?? $pathInfo['filename'],
                    ];
                }
            }
        }
        return $appmodels;
    }
}

<?php

namespace Processton\ProcesstonUser;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ProcesstonUserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'processton-user');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'processton-user');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('module-user.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/processton-user'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/processton-user'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/processton-user'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'module-user');

        // Register the main class to use with the facade
        $this->app->singleton('processton-user', function () {
            return new ProcesstonUser;
        });
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/routes.php');
        });
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => config('module-user.base_path'),
            'middleware' => [
                ...(config('processton-client.middleware') ? config('processton-client.middleware') : []),
                ...(config('module-user.middleware') ? config('module-user.middleware') : [])
            ],
        ];
    }
}

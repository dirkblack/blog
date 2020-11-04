<?php

namespace DarkBlog\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Service provider
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../../routes/routes.php');
        $this->loadViewsFrom(__DIR__.'/../views', 'darkblog');
        $this->publishes([
            __DIR__.'/../views', resource_path('views/vendor/darkblog')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

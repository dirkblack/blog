<?php

namespace DarkBlog\Providers;

use DarkBlog\Console\Commands\PublishPosts;
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
        $this->loadFactoriesFrom(__DIR__.'/../database/factories');
        $this->publishes([
            __DIR__.'/../views', resource_path('views/vendor/darkblog')
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishPosts::class
            ]);
        }
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

<?php

namespace DarkBlog\Providers;

use DarkBlog\Composers\BlogDashboardComposer;
use DarkBlog\Policies\PostPolicy;
use DarkBlog\Console\Commands\PublishPosts;
use DarkBlog\Models\Post;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Service provider
 */
class ServiceProvider extends BaseServiceProvider
{

    protected $policies = [
        Post::class => PostPolicy::class
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/routes.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'darkblog');
        $this->loadFactoriesFrom(__DIR__ . '/../database/factories');
        $this->publishes([
            __DIR__ . '/../views', resource_path('views/vendor/darkblog')
        ]);

        $this->registerPolicies();

        // Using class based composers...
        View::composer(
            'darkblog::_admin_menu',
            BlogDashboardComposer::class
        );
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

    public function registerPolicies()
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }
}

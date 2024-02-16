<?php

namespace DarkBlog;

use DarkBlog\Composers\BlogDashboardComposer;
use DarkBlog\Console\Commands\MailSubscribers;
use DarkBlog\Http\Controllers\BlogApiController;
use DarkBlog\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BlogServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('blog')
            ->hasConfigFile()
            ->hasViews()
            ->hasViewComposer('*', BlogDashboardComposer::class)
            ->hasMigration('create_posts_table')
            ->hasMigration('create_tags_table')
            ->hasMigration('create_tagged_table')
            ->hasMigration('create_subscribers_table')
            ->hasRoutes(['web', 'api'])
            ->hasAssets()
            ->hasCommand(MailSubscribers::class)
            ->hasInstallCommand(function(InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishAssets()
                    ->publishMigrations()
//                    ->askToRunMigrations()
                    ->copyAndRegisterServiceProviderInApp();
            });
    }

    public function packageRegistered()
    {
        $base_url = 'blog';

        Route::macro('blog', function () use ($base_url) {

            Route::prefix($base_url)->group(function () {
                Route::get('/', [BlogController::class, 'index']);
            });

            // API Routes
            Route::middleware('api')
            ->prefix($base_url.'/api')->group(function () {
                Route::get('/', [BlogApiController::class, 'index']);
            });
        });

//        $this->app->bind(HasTimedResources::class, TestOwner::class);


        // In the project place this in the RouteServiceProvider to register the routes
//        Route::blog();
    }
}

<?php

namespace DarkBlog;

use DarkBlack\Blog\Commands\BlogCommand;
use DarkBlack\Blog\Http\Api\TimesheetApiController;
use DarkBlack\Blog\Http\Controllers\TimesheetController;
use DarkBlack\Blog\Tests\TestSupport\TestModels\TestOwner;
use DarkBlog\Console\Commands\MailSubscribers;
use DarkBlog\Http\Controllers\BlogApiController;
use DarkBlog\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;
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
            ->hasMigration('create_tags_table')
            ->hasCommand(MailSubscribers::class);
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

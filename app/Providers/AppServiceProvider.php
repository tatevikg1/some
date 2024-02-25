<?php

namespace App\Providers;

use App\Repositories\ProfileRepository;
use App\Services\FirebaseNotificationService;
use App\Services\ImageService;
use App\Services\NotificationServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(NotificationServiceInterface::class, function () {
            return new FirebaseNotificationService();
        });
        $this->app->bind(
            ProfileRepository::class,
            function ($app) {
                return new ProfileRepository($app->make(ImageService::class));
            }
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

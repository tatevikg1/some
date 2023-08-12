<?php

namespace App\Providers;

use App\Constants\AppConstants;
use App\Policies\ProfilePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        Gate::define('update-profile', [ProfilePolicy::class, 'update']);
        Gate::define('viewWebSocketsDashboard', function ($user) {
            return $user->email == env('ADMIN_USER_EMAIL');
        });

        $permissions = [
            AppConstants::SCOPE_WEBSITE_API => 'Access APIs on Website',
            AppConstants::SCOPE_ANOTHER_THING => 'Do something else'
        ];
        Passport::tokensCan($permissions);
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
class GatePolicyProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // somewhere in a service provider
        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        // Gate::before(function ($user, $ability) {
        //     return $user->hasRole('Super Admin') ? true : null;
        // });
        // Gate::after(function ($user, $ability) {
        //     return $user->hasRole('Super Admin'); // note this returns boolean
        // });

        //
        // Gate::define('chart', function (User $user) {
        //     return $user->isEditor
        //         ? Response::allow()
        //         : Response::deny('You must be an administrator.');
        // });

        Gate::define('edit-settings', function (User $user) {
            return $user->hasRole('Nida')
                        ? Response::allow()
                        : Response::deny('You must be a super administrator to perform this action.');
        });
    }
}

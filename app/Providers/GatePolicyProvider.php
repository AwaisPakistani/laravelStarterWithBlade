<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\MyPolicy;// If we are not saving policy in laravel by default folder then always import need otherwise in policy no need to import policy 
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
        // Gate::before will run before any GAte works
        // Gate::before(function ($user, $ability) {
        //     return $user->hasRole('Super Admin') ? true : null;
        // });
        // Gate::after will run after any Gate running complete
        // Gate::after(function ($user, $ability) {
        //     return $user->hasRole('Super Admin'); // note this returns boolean
        // });

        //
        // Gate::define('chart', function (User $user) {
        //     return $user->isEditor
        //         ? Response::allow()
        //         : Response::deny('You must be an administrator.');
        // });

        // Gate::define('rolesAccess', function (User $user) {
        //     return $user->hasRole('Nida')
        //                 ? Response::allow()
        //                 : Response::deny('You must be a super administrator to perform this action.');
        // });

        // Gate::policy(User::class,MyPolicy::class);
        // If we are not saving policy in laravel by default folder then always call in providers like above
        // Dashboard options
        Gate::define('GateSuperAdmin',function(User $user){
            return $user->hasRole('Super Admin');
        });
        // View Profile either it's you or not
        Gate::define('view-my-profile',function(User $user,$Userid){
            return $user->id===$Userid;
        });


    }
}

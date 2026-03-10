<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Spatie\Permission\Exceptions\UnauthorizedException;
use App\Exceptions\PermissionException;
class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission = null, $guard = null): Response
    {
        // return $next($request);
        $authGuard = app('auth')->guard($guard);
        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }
        $permission = $request->route()->getName();

        // return ($authGuard->user()->hasRole('Super Admin')  || $authGuard->user()->haspermission($permission) ? $next($request)  :  throw UnauthorizedException::forPermissions(explode(",",$permission)));

        if ($authGuard->user()->hasRole('Super Admin')  || $authGuard->user()->haspermission($permission)) {
            return $next($request);
        }
        return redirect(route('admin.unauthorized'));
    }
}

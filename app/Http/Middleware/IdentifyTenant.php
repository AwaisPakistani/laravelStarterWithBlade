<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\TenantResolverService;

class IdentifyTenant
{
    protected TenantResolverService $tenantResolverService;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function __construct(TenantManager $tenantResolverService)
    {
        $this->tenantResolverService = $tenantResolverService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost(); // e.g. tenant1.localhost

        $tenant = $this->tenantResolverService->loadTenant($host);
          if (! $tenant) {
            abort(404, 'Tenant not found.');
        }

        // Optionally share tenant globally
        app()->instance('currentTenant', $tenant);

        return $next($request);
    }
}

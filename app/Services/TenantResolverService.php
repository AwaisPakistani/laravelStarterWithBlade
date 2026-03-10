<?php
namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantResolverService
{
    protected ?Tenant $tenant = null;

    public function loadTenant($domain): ?Tenant
    {
        $this->tenant = Tenant::where('domain', $domain)->first();

        if ($this->tenant) {
            $this->configureTenantConnection($this->tenant);
        }

        return $this->tenant;
    }

    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    protected function configureTenantConnection(Tenant $tenant): void
    {
        // Create a new connection config at runtime
    }
}

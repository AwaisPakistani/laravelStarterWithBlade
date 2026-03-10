<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Tenant;
use App\Services\TenantResolverService;
use Illuminate\Support\Facades\Artisan;
class MigrateTenants extends Command
{
    protected $signature = 'tenants:migrate {--fresh}';
    protected $description = 'Run migrations for all tenants';

    public function handle(TenantResolverService $tenantResolverService)
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->info("Migrating tenant: {$tenant->name} ({$tenant->domain})");

            // Configure connection
            $tenantResolverService->loadTenant($tenant->domain);

            $params = [
                '--database' => 'tenant',
                '--path'     => 'database/migrations', // or a specific folder: database/migrations/tenant
                '--force'    => true,
            ];

            if ($this->option('fresh')) {
                Artisan::call('migrate:fresh', $params);
            } else {
                Artisan::call('migrate', $params);
            }

            $this->line(Artisan::output());
        }

        $this->info('All tenant migrations completed.');

        return self::SUCCESS;
    }
}

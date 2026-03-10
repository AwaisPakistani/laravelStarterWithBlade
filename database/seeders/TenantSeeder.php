<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tenant;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::create([
            'name'        => 'Tenant One',
            'domain'      => 'tenant1.localhost',   // or tenant1.yourdomain.test
            'database'    => 'tenant1_db',
            'db_username' => 'root',
            'db_password' => '',
        ]);

        Tenant::create([
            'name'        => 'Tenant Two',
            'domain'      => 'tenant2.localhost',
            'database'    => 'tenant2_db',
            'db_username' => 'root',
            'db_password' => '',
        ]);
    }
}

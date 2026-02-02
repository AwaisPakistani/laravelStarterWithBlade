<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
// use App\Jobs\ZktDevice\AuthJob;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin_role = Role::create([
            'name' => 'Super Admin',
            // 'display_name' => 'Super Admin'
        ]);

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('superadmin'),
        ]);

        $superAdmin->assignRole($superAdmin_role);
        // AuthJob::dispatch($superAdmin);
    }
}

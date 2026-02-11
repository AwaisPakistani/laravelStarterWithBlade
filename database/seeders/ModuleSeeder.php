<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
class ModuleSeeder extends Seeder
{
    public function run()
    {
        // Module::factory()->count(10)->create();
        $modules = [
            'superadmin',
            'admin',
            'employee',
        ];

        $subModules = [
            [
                'parent_id' => 1, //superadmin Module
                'modules' => [
                    'Super Admin Dashboard',
                    'Users',
                    'Roles',
                    'Permissions',
                ]
            ],
            [
                'parent_id' => 2, //admin Module
                'modules' => [
                    'Admin Dashboard',
                ]
            ],
            [
                'parent_id' => 3, // employee Module
                'modules' => [
                    'Employee Dashboard',
                ]
            ],
        ];


        foreach ($modules as $module) {
            Module::create([
                'name' => $module,
                'slug' => Str::slug($module),
            ]);
        }

        foreach ($subModules as $subModule) {
            $module_array = [];
            foreach ($subModule['modules'] as $module) {
                array_push($module_array, [
                    'name' => $module,
                    'slug' => Str::slug($module),
                    'parent_id' => $subModule['parent_id'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            Module::insert($module_array);
        }

    }
}

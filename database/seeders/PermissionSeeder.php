<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\{Module, RoleModel, User};
use Carbon\Carbon;
use Spatie\Permission\Models\{Permission, Role};

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = Module::whereNull('parent_id')->with('subModules')->get();

        $super_admin = Role::where('name', 'Super Admin')->first();
        $admin_role = Role::where('name', 'Admin')->first();

        DB::beginTransaction();

        foreach ($modules as $module) {
            foreach ($module->subModules as $subModule) {
                $permissions = Permission::where('module_id', $subModule->id)->get();
                if ($permissions->isEmpty()) {
                    $permissions = ['index', 'create', 'show', 'edit', 'update', 'store', 'destroy', 'approve', 'download', 'all'];

                    $permission_array = [];

                    foreach ($permissions as $permission) {
                        array_push($permission_array, [
                            'name' => "$module->slug.$subModule->slug.$permission",
                            'module_id' => $subModule->id,
                            'guard_name' => 'web',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }

                    Permission::insert($permission_array);

                    $permissions = Permission::where('module_id', $subModule->id)->get();

                    $super_admin->givePermissionTo($permissions);

                    // if($subModule->name != 'Court'){
                    //     $court_admin->givePermissionTo($permissions);
                    // }

                    if ($module->name == 'HR') {
                        $hr_admin->givePermissionTo($permissions);
                    }  elseif ($module->name == 'Employee') {
                        $admin_role->givePermissionTo($permissions);
                    }
                }
            }
        }
        DB::commit();
    }
}

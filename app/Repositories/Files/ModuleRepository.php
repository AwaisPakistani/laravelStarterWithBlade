<?php

namespace App\Repositories\Files;

use App\Models\Module;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\ModuleRepositoryInterface;

class ModuleRepository implements ModuleRepositoryInterface
{
    protected $model;

    public function __construct(Module $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        $module = Module::find($data['parent_id']);
        $super_admin = Role::where('name', 'Super Admin')->first();

        if (!$module) {
            dd('Module not found');
            return back()->with('error', 'Module Not Found.')->withInput();
        }
        DB::beginTransaction();
        try {
            $subModule = $this->model->create([
                'name'=>$data['name'],
                'slug'=>Str::slug($data['name']),
                'parent_id'=>$module->id,
            ]);

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
            DB::commit();
            return $data;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Module Creation Error : ' . $th->getMessage());
            return back()->with('error', 'Operation Failed Contact To IT Team.');
        }

    }

    public function update($id, array $data)
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        $model = $this->model->findOrFail($id);
        $model->delete();
        return $model;
    }
}

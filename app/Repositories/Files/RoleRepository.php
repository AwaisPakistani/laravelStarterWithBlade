<?php

namespace App\Repositories\Files;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Role;
use App\Models\RolePermission;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Supports\Collection;
use Illuminate\Support\Facades\Request as NewRequest;
class RoleRepository implements RoleRepositoryInterface
{
    protected $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function paginate(int $perpage= 10) : LengthAwarePaginator
    {
        $search = NewRequest::input('search');
        $perpageRecords = NewRequest::input('perPage', $perpage); // Use input value or default
        return $this->model
        ->newQuery()
        ->latest()
        // ->active()
        ->when($search, fn ($query, $search) =>$query->search($search))
        ->paginate($perpageRecords);
    }
    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        $authUserRole = Auth::user()->roles()->first();

        $existRole = $this->model->where('name', $data['name'])->exists();
        if ($existRole) {
            return back()->with('error', 'Role Already Exist.')->withInput();
        }

        DB::beginTransaction();

        try {
            $role = Role::create([
                'name' => $data['name'],
                'module_id' => $data['module_id'] ?? $authUserRole->module_id,
                'guard_name' => 'web'
            ]);

            // $role->syncPermissions($data['permissions']);
            $permissions = collect($data['permissions']??[])->map(function ($permission) use ($role) {
                return [
                    'role_id' => $role->id,
                    'permission_id' => $permission
                ];
            })->toArray();

            if (!empty($permissions)) {
                RolePermission::insert($permissions);
            }
            DB::commit();
            return $data;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            return back()->with('error', 'Your Operation Failed. Contact IT Team.')->withInput();
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

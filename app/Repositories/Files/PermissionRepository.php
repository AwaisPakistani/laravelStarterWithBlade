<?php

namespace App\Repositories\Files;

use App\Models\Permission;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Supports\Collection;
use Illuminate\Support\Facades\Request as NewRequest;
class PermissionRepository implements PermissionRepositoryInterface
{
    protected $model;

    public function __construct(Permission $model)
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
       $data['guard_name'] = 'web';
        return $this->model->create($data);
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

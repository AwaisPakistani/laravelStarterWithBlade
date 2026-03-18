<?php

namespace App\Repositories\Interfaces;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Supports\Collection;
use App\Models\User;
interface UserRepositoryInterface
{
    public function all();
    public function paginate(int $perpage= 10) : Paginator;
    public function find(int $id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}

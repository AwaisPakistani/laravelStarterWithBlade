<?php

namespace App\Repositories\Files;

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->cursor();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        $user = tap($this->model->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]), function ($user) use ($data) {
            // Assign roles by their IDs
            $roles = Role::whereIn('id', $data['roles'])->get(); // Retrieve roles by IDs
            $user->assignRole($roles); // Assign the roles to the user
        });
        return $user;
    }

    public function update($id, array $data)
    {
         // Retrieve the user model instance
        $user = $this->model->findOrFail($id);

        // Use tap to update user information and sync roles
        $user = tap($user, function ($user) use ($data) {
            // Update basic user info
            $user->name = $data['name'];
            $user->email = $data['email'];
             // If password is provided, hash it and update it
            if (!empty($data['password'])) {
                $user->password = $data['password'];
            }

            // Save the updated user data
            $user->save();
            // Sync roles
            if (isset($data['roles']) && !empty($data['roles'])) {
                $roles = Role::whereIn('id', $data['roles'])->get();
                $user->syncRoles($roles); // Sync the roles to the user
            }
        });
        // Return the updated user
        return $user;
    }

    public function delete($id)
    {
        $model = $this->model->findOrFail($id);
        $model->delete();
        return $model;
    }
}

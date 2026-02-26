<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;

use App\Repositories\Interfaces\UserRepositoryInterface;

class UserController extends Controller
{
    protected $userinterface;
    /**
     * Display a listing of the resource.
     */
    public function __construct(UserRepositoryInterface $userinterface){
        $this->userinterface= $userinterface;
    }
    public function index()
    {
        $allRecords = $this->userinterface->paginate(10);
        return view('admin.users.index', compact('allRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::cursor();
        return view('admin.users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->userinterface->create($validated);
            return redirect()->route('admin.users.index')->with('success_title', 'Created!')->with('success', 'User created successfully');;
        } catch (\Throwable $th) {
            return redirect()->route('admin.users.index')->with('error_title', 'Not Created!')->with('error', 'User creation failed');;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->userinterface->find($id);
        $roles = Role::cursor();
        return view('admin.users.show',compact('user','roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::cursor();
        return view('admin.users.edit',compact('user','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            $validated = $request->validated();
            $this->userinterface->update($user->id,$validated);
            return redirect()->route('admin.users.index')->with('success_title', 'Updated!')->with('success', 'User updated successfully');;
        } catch (\Throwable $th) {
            return redirect()->route('admin.users.index')->with('error_title', 'Not Updated!')->with('error', 'User Updation failed');;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $this->userinterface->delete($user->id);
            return redirect()
                ->route('admin.users.index')->with('success_title', 'Deleted!')
                ->with('success', 'Deleted successfully');
        } catch (\Throwable $th) {
            return redirect()->route('admin.users.index')->with('error_title', 'Not Deleted!')->with('error', 'User Deletion failed');;
        }
    }
}

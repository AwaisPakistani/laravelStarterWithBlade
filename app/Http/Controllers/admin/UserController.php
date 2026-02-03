<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
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
        $allRecords = $this->userinterface->all();
        return view('admin.users.index', compact('allRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->userinterface->create($validated);
            return redirect()->route('admin.users.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
         try {
            $validated = $request->validated();
            $this->userinterface->update($user->id,$validated);
            return redirect()->route('admin.users.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $this->userinterface->delete($user->id);
            return redirect()->route('admin.users.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

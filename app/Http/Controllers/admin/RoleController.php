<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use App\Models\Role;
use App\Models\Module;
use App\Repositories\Interfaces\RoleRepositoryInterface;

class RoleController extends Controller
{
    protected $Roleinterface;
    /**
     * Display a listing of the resource.
     */
    public function __construct(RoleRepositoryInterface $Roleinterface){
        $this->Roleinterface= $Roleinterface;
    }
    public function index()
    {
        $allRecords = $this->Roleinterface->all();
        return view('admin.Roles.index', compact('allRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authUserRole = Auth::user()->roles()->first();
        // dd($authUserRole->toArray());
        if ($authUserRole->name == 'Super Admin') {
            $modules = Module::with('subModules.permissions')->whereNull('parent_id')->cursor();
        } else {
            $modules = Module::with('subModules.permissions')->where('id', $authUserRole->module_id)->cursor();
        }
        return view('admin.Roles.create',compact('modules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $this->Roleinterface->create($validated);
            DB::commit();
            return redirect()->route('admin.roles.index')->with('success_title', 'Created!')->with('success','Role Created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            return redirect()->back()->with('error_title', 'Not Created!')->with('error', 'Role Creation failed');
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
    public function edit(Role $Role)
    {
        $authUserRole = Auth::user()->roles()->first();
        // dd($authUserRole->toArray());
        if ($authUserRole->name == 'Super Admin') {
            $modules = Module::with('subModules.permissions')->whereNull('parent_id')->cursor();
        } else {
            $modules = Module::with('subModules.permissions')->where('id', $authUserRole->module_id)->cursor();
        }
        return view('admin.Roles.edit',compact('Role','modules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $Role)
    {
         try {
            $validated = $request->validated();
            $this->Roleinterface->update($Role->id,$validated);
            return redirect()->route('admin.roles.index')->with('success_title', 'Updated!')->with('success','Role Updated successfully');;
        } catch (\Throwable $th) {
            return redirect()->back()->with('error_title', 'Not Updated!')->with('error', 'Role Updation failed');;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $Role)
    {
        try {
            $this->Roleinterface->delete($Role->id);
            return redirect()->route('admin.roles.index')->with('success_title', 'Deleted!')->with('success','Role Deleted successfully');;
        } catch (\Throwable $th) {
            return redirect()->back()->with('error_title', 'Not Deletd!')->with('error', 'Role Deletion failed');;
        }
    }
}

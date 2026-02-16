<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ModuleRequest;
use App\Models\Module;
use App\Repositories\Interfaces\ModuleRepositoryInterface;

class ModuleController extends Controller
{
    protected $Moduleinterface;
    /**
     * Display a listing of the resource.
     */
    public function __construct(ModuleRepositoryInterface $Moduleinterface){
        $this->Moduleinterface= $Moduleinterface;
    }
    public function index()
    {
        $allRecords = $this->Moduleinterface->all();
        return view('admin.Modules.index', compact('allRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $modules = Module::whereNull('parent_id')->cursor();
        return view('admin.Modules.create',compact('modules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ModuleRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->Moduleinterface->create($validated);
            return redirect()->route('admin.modules.index')->with('success_title', 'Created!')->with('success','Module Created successfully');
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
    public function edit(Module $module)
    {
        $modules = Module::cursor();
        return view('admin.modules.edit',compact('module','modules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ModuleRequest $request, Module $Module)
    {
         try {
            $validated = $request->validated();
            $this->Moduleinterface->update($Module->id,$validated);
            return redirect()->route('admin.modules.index')->with('success_title', 'Updated!')->with('success','Module Updated successfully');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $Module)
    {
        try {
            $this->Moduleinterface->delete($Module->id);
            return redirect()->route('admin.modules.index')->with('success_title', 'Deleted!')->with('success','Module Deleted successfully');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

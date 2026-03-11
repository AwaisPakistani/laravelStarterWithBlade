<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Gate;
use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;

class PostController extends Controller
{
    protected $Postinterface;
    /**
     * Display a listing of the resource.
     */
    public function __construct(PostRepositoryInterface $Postinterface){
        $this->Postinterface= $Postinterface;
    }
    public function index()
    {
        $allRecords = $this->Postinterface->all();
        return view('admin.Posts.index', compact('allRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Gate::authorize('create',auth()->user()->id);
        return view('admin.Posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->Postinterface->create($validated);
            return redirect()->route('admin.posts.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        Gate::authorize('view',$post);// will use GAte for policies too will not use policy for policy ok so don't confuse
        dd('Post view');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $Post)
    {
        Gate::authorize('update',$Post);
        return view('admin.Posts.edit',compact('Post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, Post $Post)
    {
        Gate::authorize('update',$Post);
        try {
            $validated = $request->validated();
            $this->Postinterface->update($Post->id,$validated);
            return redirect()->route('admin.posts.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $Post)
    {
        Gate::authorize('delete',$Post);

        try {
            $this->Postinterface->delete($Post->id);
            return redirect()->route('admin.posts.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

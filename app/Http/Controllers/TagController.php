<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $categories = Category::where('status',Category::ACTIVE)->pluck('name','id');
        return view("dashboard.tags.index", compact("categories"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('status',Category::ACTIVE)->pluck('name','id');
        return view('dashboard.tags.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        
            try{
                DB::beginTransaction();
                Tag::createTag($request->all());
                DB::commit();
            }catch(\Exception $e){
                return $e->getMessage();
                return back()->with(['type'=>'error','message'=> $e->getMessage()]);
            }
            return redirect('tags')->with(['type'=>'success','message'=>'Tag Created Successfully']);
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTagRequest $request, Tag $tag)
    {

        try{
            DB::beginTransaction();
            $tag->updateTag($request->all());
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return $e->getMessage();
            return back()->with(['type'=> 'error','message'=> $e->getMessage()]);
        }

        return redirect('tags')->with(['type'=> 'success','message'=> 'Tag Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        
        $tag->delete();
        return redirect()->route('tags.index')->with([
            'type'    => 'success',
            'message' => 'Tag Deleted Successfully'
        ]);
    }
}

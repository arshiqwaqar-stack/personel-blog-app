<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::all();
        return view("dashboard.tags.index", compact("tags",));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('status',1)->pluck('name','id');
        return view('dashboard.tags.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=> 'required',
            'category_id'=> 'required',
            ]);
            try{
                DB::beginTransaction();
                $tag = new Tag();
                $tag->name = $request->name;
                $tag->category_id = $request->category_id;
                $tag->status = $request->status??'1';
                $tag->save();
                DB::commit();

            }catch(\Exception $e){
                DB::rollBack();
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
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name'=> 'required',
            'category_id'=> 'required',
        ]);
        try{
            DB::beginTransaction();
            $tag = Tag::find($id);
            $tag->name = ucfirst($request->name);
            $tag->category_id = $request->category_id;
            $tag->status = $request->status;

            $tag->save();
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with(['type'=> 'error','message'=> $e->getMessage()]);
        }

        return redirect('tags')->with(['type'=> 'success','message'=> 'Tag Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
        return redirect('tags')->with(['type'=>'success','message'=>'Tag Deleted Successfully']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view("dashboard.category.create",compact("categories"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "name"=> "required",
        ]);
        try {
            DB::beginTransaction();
            $category = new Category();
            $category->name = ucfirst($request->name);
            $category->status = $request->status??'1';
            $category->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with(['type'=>'error','message'=> $e->getMessage()]);
        }
        return redirect('categories/create')->with(['type'=>'success','message'=>'Category Added Successfully']);
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
        try{
            DB::beginTransaction();
            $category = Category::find($id);
            $category->name = ucfirst($request->name);
            $category->status = $request->status??'1';
            $category->save();
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with(['type'=> 'error','message'=> $e->getMessage()]);
        }
        return redirect('categories/create')->with(['type'=> 'success','message'=> 'Category Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            DB::beginTransaction();
            $category = Category::findOrFail($id);
            $category->delete();
            DB::commit();

        }catch(\Exception $e){
            DB::rollBack();
            return back()->with(['type'=> 'error','message'=> $e->getMessage()]);
        }
        return redirect('categories/create')->with(['type'=> 'success','message'=> '']);
    }
}

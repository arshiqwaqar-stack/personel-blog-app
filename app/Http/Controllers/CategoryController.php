<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

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
    public function create(Request $request)
    {
        return view("dashboard.category.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            DB::beginTransaction();
            Category::createCategory($request->all());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
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
    public function update(StoreCategoryRequest $request, Category $category)
    {
        try{
            DB::beginTransaction();
            $category->update($request->all());
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
    public function destroy(Category $category)
    {
        try{
            DB::beginTransaction();
            $category->delete();
            DB::commit();

        }catch(\Exception $e){
            DB::rollBack();
            return back()->with(['type'=> 'error','message'=> $e->getMessage()]);
        }
        return redirect('categories/create')->with(['type'=> 'success','message'=> '']);
    }
}

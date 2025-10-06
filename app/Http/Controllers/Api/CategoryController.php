<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
    public function index(Request $request)
    {
        $categories = Category::query();
        if ($request->ajax()) {
            return DataTables::of($categories)
               ->addIndexColumn() 
               ->addColumn("status", function ($category) {
                return $category->status == 1?"Active":"Inactive";
               })
                ->addColumn('action', function($category){
                    $editBtn = '<button type="button" 
                                    class="btn btn-primary btn-sm edit-category-btn" 
                                    category-id="'.$category->id.'" 
                                    category-status="'.$category->status.'" 
                                    category-name="'.$category->name.'">
                                    Edit
                                </button>';
                    $deleteForm = '
                            <button type="submit" onClick="deleteCategory('.$category->id.',this)" class="btn btn-danger btn-sm">Delete</button>';

                    return $editBtn . ' ' . $deleteForm;
                })->filterColumn('status', function ($query, $keyword) {
                    $keyword = strtolower($keyword);
                    if ($keyword === 'active') {
                        $query->where('status',Category::ACTIVE);
                    } elseif ($keyword === 'inactive') {
                        $query->where('status', Category::INACTIVE);
                    }
                })->rawColumns(['action','status'])
                ->make(true);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         try {
            DB::beginTransaction();
            Category::createCategory($request->all());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return returnResponse('error', $e->getMessage(),500);    
        }
        return returnResponse('success', 'Category Created Successfully',200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
            // $category = Category::find($id);
            $category->update($request->validated());
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return returnResponse('error', $e->getMessage(),500);
        }
        return returnResponse('success', 'Category Updated Successfully',200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $id)
    {
        if($id){
            $id->delete();
            return returnResponse('success','Category Deleted Successfully',200);
        }
    }
}

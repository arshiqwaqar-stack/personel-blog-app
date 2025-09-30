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

                    $csrf   = csrf_field();
                    $method = method_field('DELETE');
                    $deleteForm = '
                        <form class="d-inline" action="'.route('categories.destroy', $category).'" method="POST">
                            '.$csrf.'
                            '.$method.'
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    ';

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
        return view("dashboard.category.create",compact("categories"));
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

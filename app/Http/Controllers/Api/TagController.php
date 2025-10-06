<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
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
        $tags = Tag::query();
        if ($request->ajax()) {
            return DataTables::of($tags)
                ->addIndexColumn()
               ->addColumn("status", function ($tag) {
                return $tag->status == 1?"Active":"Inactive";
               })
               ->addColumn("category", function ($tag) {
                return $tag->category->name;
               })
                ->addColumn('action', function($tag){
                    $editBtn = '<button type="button" class="edit-tag-btn btn btn-primary" data-url="'.route("tags.update", $tag).'" category-id="'. $tag->category_id .'" tag-status="'.$tag->status.'" tag-name="'. $tag->name.'" >
                                        Edit
                                    </button>';

                    $csrf   = csrf_field();
                    $method = method_field('DELETE');
                    $deleteForm = '
                        <form class="d-inline" action="'.route('tags.destroy', $tag).'" method="POST">
                            '.$csrf.'
                            '.$method.'
                            <button type="submit" class="btn btn-danger ">Delete</button>
                        </form>
                    ';
                    return $editBtn . ' ' . $deleteForm;
                })->filterColumn('status', function ($query, $keyword) {
                    $keyword = strtolower($keyword);
                    if ($keyword === 'active') {
                        $query->where('status',Tag::ACTIVE);
                    } elseif ($keyword === 'inactive') {
                        $query->where('status', Tag::INACTIVE);
                    }
                })
                ->rawColumns(['action','status'])
                ->make(true);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        try{
                DB::beginTransaction();
                Tag::createTag($request->validated());
                DB::commit();
            }catch(\Exception $e){
                return returnResponse('error', $e->getMessage(),500);
            }
            return returnResponse('success', 'Tag Created Successfully',201);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

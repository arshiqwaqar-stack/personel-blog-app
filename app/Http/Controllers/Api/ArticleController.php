<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\SingleArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index(Request $request)
    {
        $articles = Article::query();
        $user = $request->user();
        if ($request->ajax()) {
            return DataTables::of($articles)
                ->addIndexColumn()
                ->addColumn("image", fn($article) =>
                    '<img src="'.asset("storage/website/" . ($article->image ?? "")).'" height="100" width="100" alt="Article Image">'
                )
                ->addColumn('action', function($article) use ($user) {
                    $viewUrl = route('articles.show', $article->id);

                    if ($user->hasRole("admin")) {
                        $editUrl   = route('articles.edit', $article->id);
                        $deleteUrl = route('articles.destroy', $article->id);
                        return '
                            <a href="'.$viewUrl.'" class="btn btn-success btn-sm">View</a>
                            <a href="'.$editUrl.'" class="btn btn-primary btn-sm mx-2">Edit</a>
                            <button type="submit" class="btn btn-danger btn-sm" onclick="deleteArticle('.$article->id.',this )">Delete</button>
                        ';
                    }

                    if ($user->hasRole("user")) {
                        return '<a href="'.$viewUrl.'" class="btn btn-success btn-sm">View</a>';
                    }
                })
                ->rawColumns(['action','image'])
                ->make(true);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        try{
            DB::beginTransaction();
            Article::createArticle($request->validated());
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return returnResponse('error', $e->getMessage(),500);
        }
        return returnResponse('success', 'Article Created Successfully',201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $article_id)
    {
         $article = Article::findOrFail($article_id);

        return new SingleArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreArticleRequest $request, Article $article)
    {
         try{
            DB::beginTransaction();
            Article::updateArticle( $request->validated(), $article );
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
           return returnResponse('error', $e->getMessage(),500);
        }
        return returnResponse('success','Updated Successfully',200);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::find($id);
        if($article->delete()){
            DB::commit();
            return returnResponse('success','Deleted Successfully',200);
        }else{
            return returnResponse('error','Something went wrong',500);
        }
    }
    public function showCollection(){
        return ArticleResource::collection(Article::all());
    }
}

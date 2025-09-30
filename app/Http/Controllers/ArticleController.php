<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ArticleController extends Controller
{
    public function __construct(){
        $this->middleware("auth");
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $articles = Article::query();
        if ($request->ajax()) {
            return DataTables::of($articles)
                ->addIndexColumn()
                ->addColumn("image", function ($article) {
                    $path = asset("storage/website/" . ($article->image ?? ""));
                    return '<img src="'.$path.'" height="100" width="100" alt="Article Image">';
                })
                ->addColumn('action', function($article){
                    $viewUrl   = route('articles.show', $article->id);
                    $editUrl   = route('articles.edit', $article->id);
                    $deleteUrl = route('articles.destroy', $article->id);

                    $csrf   = csrf_field();
                    $method = method_field('DELETE');

                    $actionBtn = '
                        <a href="'.$viewUrl.'" class="btn btn-success btn-sm">View</a>
                        <a href="'.$editUrl.'" class="btn btn-primary btn-sm mx-2">Edit</a>
                        <form action="'.$deleteUrl.'" method="POST" style="display:inline-block;">
                            '.$csrf.'
                            '.$method.'
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    ';
                    return $actionBtn;
                })
                
                ->rawColumns(['action','image'])
                ->make(true);
        }
        return view("dashboard",compact("articles"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('status',Category::ACTIVE)->pluck('name', 'id');
        $tags = Tag::where('status',Tag::ACTIVE)->pluck("name","id");
        return view("dashboard.articles.create",compact("categories",'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        try{
            DB::beginTransaction();
            Article::createArticle($request->all());
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return $e->getMessage();
            return back()->with(['type'=>'error','message'=> $e->getMessage()]);
        }
        return redirect('articles')->with(['type'=> 'success','message'=> 'Article Created Successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $article = Article::findOrFail($article->id);
        return view('dashboard.articles.show',compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $article = Article::findOrFail($id);
        $categories = Category::where('status',Category::ACTIVE)->pluck('name','id');
        $tags = Tag::where('status',TAG::ACTIVE)->pluck('name','id');
        return view('dashboard.articles.edit',compact('article','categories','tags'));
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
            return $e->getMessage();
            return back()->with(['type'=>'error','message'=> $e->getMessage()]);
        }
        
        return redirect('articles')->with(['type'=> 'success','message'=> 'Article Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect('articles')->with(['type'=> 'success','message'=> 'Deleted Successfully']);
    }
}

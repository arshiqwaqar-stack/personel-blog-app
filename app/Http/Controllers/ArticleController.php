<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function __construct(){
        $this->middleware("auth");
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::orderBy("created_at","desc")->paginate(10);
        return view("dashboard",compact("articles"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('status', 1)->pluck('name', 'id');
        $tags = Tag::where('status',1)->pluck("name","id");
        return view("dashboard.articles.create",compact("categories",'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $this->validate($request, [
            'title'       => 'required|string',
            'description' => 'required|string',
            'category' => 'required',
            'image'       => 'required|mimes:jpeg,jpg,png',
        ]);
        try{
            DB::beginTransaction();
            $article = new Article();
            $article->title = $request->title;
            $article->description = $request->description;
            $article->category_id = $request->category;

            if($request->hasFile('image')){
                $safeName = $this->storeImage('images', $request->image);
                $article->image = $safeName;
            }
            $article->save();
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
    public function show(string $id)
    {
        $article = Article::findOrFail($id);
        return view('dashboard.articles.show',compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $article = Article::findOrFail($id);
        $categories = Category::where('status',Category::ACTIVE)->pluck('name','id');
        return view('dashboard.articles.edit',compact('article','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'title'       => 'required|string',
            'description' => 'required|string',
            'category' => 'required',
            'image'       => 'required|mimes:jpeg,jpg,png',
        ]);
        try{
            DB::beginTransaction();
            $article = Article::findOrFail($id);
            $article->title = $request->title;
            $article->description = $request->description;
            $article->category_id = $request->category;
            if( $request->hasFile('image') ){
                if(Storage::disk('articles')->exists( $article->image )){
                    Storage::disk('articles')->delete( $article->image );
                }
                $safeName = $this->storeImage('articles',$request->file('image'));
                $article->image = $safeName;
            }
            $article->save();
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with(['type'=>'error','message'=> $e->getMessage()]);
        }
        return back()->with(['type'=> 'success','message'=> 'Article Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);
        $article->delete();
        return redirect('articles')->with(['type'=> 'success','message'=> 'Deleted Successfully']);
    }
}

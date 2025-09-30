<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [] ;
    public function category(){
        return $this->belongsTo(Category::class);
    }
   
    public function tags(){
        return $this->belongsToMany(Tag::class,"article_tags","article_id","tag_id",);
    }
    
    
    public static function createArticle(array $data){
        $safeName = null;
        if($data['image']){
                $safeName = storeImage('images', $data['image']);
                $data['image'] = $safeName;
            }
        $article = self::create([
            'title'=> $data['title'],
            'description'=> $data['description'],
            'category_id'=> $data['category_id'],
            'image'=> $safeName,
        ]
        );
        $article->tags()->sync($data['tag_id']);
        return $article;
    }
    public static function updateArticle(array $data,Article $article){
        $safeName = $article->image;
        if(isset($data['image'])){
                $safeName = storeImage('images', $data['image']);
                $data['image'] = $safeName;
            }
        $article->tags()->sync($data['tag_id']);
        return $article->update(
         [
                'title'=> $data['title'],
                'description'=> $data['description'],
                'category_id'=> $data['category_id'],
                'image'=> $safeName,
            ] 
          );
        }

}

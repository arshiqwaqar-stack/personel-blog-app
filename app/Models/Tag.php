<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];
   const ACTIVE = 1;
    const INACTIVE = 0;
    public function category(){
        return $this->belongsTo(Category::class);
    }
     public function articles()
    {
        return $this->belongsToMany(
            Article::class,
            'article_tags',
            'tag_id',
            'article_id'
        );
    }

    public static function createTag(array $newTag){
            return self::create([
                    'name'        => $newTag['name'],
                    'category_id' => $newTag['category_id'],
                    'status'      => $newTag['status'] ?? 1,
                ]);
            
    }
    public function updateTag(array $data): bool
    {
        return $this->update([
            'name'        => $data['name'] ?? $this->name,
            'category_id' => $data['category_id'] ?? $this->category_id,
            'status'      => $data['status'] ?? $this->status,
        ]);
    }
   

}

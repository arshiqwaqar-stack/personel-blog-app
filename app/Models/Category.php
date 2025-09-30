<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\ActiveScope;

class Category extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];
    const ACTIVE = 1;
    const INACTIVE = 0;
    // protected static function booted():void{
    //     static::addGlobalScope(new ActiveScope);
    // }
    public static function createCategory(array $data){
        return self::create([
            "name"=> ucfirst($data["name"]),
            "status"=> $data["status"]??self::ACTIVE,
        ]);
    }
    public static function updateCategory(array $data){
        return self::update([
            'name'=> ucfirst($data['name']),
            'status'=> $data['status']??self::ACTIVE,
            ]);
        }
}

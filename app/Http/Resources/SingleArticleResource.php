<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $article): array
    {
         return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'image_url'   => asset('storage/website/'.$this->image),
            'tags'        => $this->tags->pluck('name'),
            'created_at'  => $this->created_at->diffForHumans(),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'content' => $this->content,
            'image_url' => $this->getFirstMediaUrl('articles'),
            'type' => $this->type,
            'category' => $this->category ? new ArticleCategoryResource($this->category)  : null,
            'donation' => $this->donation ? [
                'id' => $this->donation->id,
                'title' => $this->donation->title
            ] : null,
            'put_on_highlight' => $this->put_on_highlight,
            'created_at' => $this->created_at,
            'tags' => TagResource::collection($this->tags),

        ];
    }
}

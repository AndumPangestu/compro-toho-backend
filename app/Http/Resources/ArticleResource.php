<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'type' => $this->type,
            'description' => $this->description,
            'category_name' => $this->category->name ?? null,
            'put_on_highlight' => $this->put_on_highlight,
            'image_url' => $this->getFirstMediaUrl('articles'),
            'created_at' => $this->created_at,
        ];
    }
}

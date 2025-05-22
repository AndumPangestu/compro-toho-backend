<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationDetailResource extends JsonResource
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
            'category' => $this->category ? $this->category->name : null,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'content' => $this->content,
            'target_amount' => number_format($this->target_amount, 0, ',', '.'),
            'collected_amount' => number_format($this->collected_amount, 0, ',', '.'),
            'start_date' => $this->start_date->format('d M Y'),
            'end_date' => $this->end_date->format('d M Y'),
            'location' => $this->location,
            'put_on_highlight' => $this->put_on_highlight,
            'status' => $this->status,
            'created_at' => $this->created_at->format('d M Y H:i'),
            'updated_at' => $this->updated_at->format('d M Y H:i'),
            'image' => $this->getFirstMediaUrl('donations'),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationResource extends JsonResource
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
            'fund_usage_details' => $this->fund_usage_details,
            'description' => $this->description,
            'distribution_information' => $this->distribution_information,
            'category_name' => $this->category ? $this->category->name : null,
            'target_amount' => number_format($this->target_amount, 0, ',', '.'),
            'collected_amount' => number_format($this->collected_amount, 0, ',', '.'),
            'start_date' => $this->start_date->format('d M Y'),
            'end_date' => $this->end_date->format('d M Y'),
            'location' => $this->location,
            'put_on_highlight' => $this->put_on_highlight,
            'image' => $this->getFirstMediaUrl('donations'),
        ];
    }
}

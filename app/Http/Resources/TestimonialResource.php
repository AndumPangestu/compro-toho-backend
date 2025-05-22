<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
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
            'message' => $this->message,
            'sender_name' => $this->sender_name,
            'organization' => $this->organization,
            'sender_category' => $this->sender_category,
            'image_url' => $this->getFirstMediaUrl('testimonials'),
        ];
    }
}

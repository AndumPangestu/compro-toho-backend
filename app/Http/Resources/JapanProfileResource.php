<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JapanProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'established' => $this->established,
            'address' => $this->address,
            'employees' => $this->employees,
            'chairman' => $this->chairman,
            'president' => $this->president,

            'domestic_group' => collect($this->domestic_group)->map(function ($group) {
                return [
                    'name' => $group->name ?? null,
                ];
            }),

            'overseas_group' => collect($this->overseas_group)->map(function ($group) {
                return [
                    'name' => $group->name ?? null,
                ];
            }),
            'image_urls' => $this->image_urls ?? [],
        ];
    }
}

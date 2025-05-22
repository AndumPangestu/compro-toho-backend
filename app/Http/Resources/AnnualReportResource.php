<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnualReportResource extends JsonResource
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
            'year' => $this->year,
            'collected_funds' => $this->collected_funds,
            'donor_count' => $this->donor_count,
            'active_program_count' => $this->active_program_count,
            'created_at' => $this->created_at->format('d M Y H:i'),
            'file' => $this->getFirstMediaUrl('annual_reports'),
        ];;
    }
}

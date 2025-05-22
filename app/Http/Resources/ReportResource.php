<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'online_funds' => $this->online_funds,
            'offline_funds' => $this->offline_funds,
            'total_funds' => $this->online_funds + $this->offline_funds,
            'donor_count' => $this->donor_count,
            'active_program' => $this->active_program,
            'beneficiary_count' => $this->beneficiary_count,
            'coverage_area' => $this->coverage_area,
        ];
    }
}

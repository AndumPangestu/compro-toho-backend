<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'donation_id' => $this->donation_id,
            'donation_title' => $this->donation->title,
            'payment_type' => $this->payment_type,
            'user_id' => $this->user_id ?? null,
            'user_name' => $this->is_anonym ? "Anonim" : ($this->user_id ? $this->user?->name : ($this->anonymousDonor?->name ?? 'Anonim')),
            'midtrans_transaction_id' => $this->midtrans_transaction_id,
            'amount' => $this->amount,
            'transaction_status' => $this->transaction_status,
            'snap_token' => $this->snap_token ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

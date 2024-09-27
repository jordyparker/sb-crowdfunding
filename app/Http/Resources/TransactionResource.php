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
            'payer' => $this->whenLoaded('payer', fn () => $this->payer),
            'transaction_id' => strtoupper($this->transaction_id),
            'amount' => (float) $this->amount,
            'currency' => $this->currency,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'item' => $this->whenLoaded('item', fn () => $this->item),
            'details' => $this->details,
            'created_at' => $this->created_at->format('d/m/Y H:m:s')
        ];
    }
}

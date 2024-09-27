<?php

namespace App\Http\Resources;

use App\Models\DonationCampaign;
use App\Models\User;
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
            'running_amount' => (float) $this->running_amount,
            'amount' => (float) $this->amount,
            'currency' => $this->currency,
            'comments' => $this->comments,
            'donator' => $this->whenLoaded('donator', fn () => match ($this->donator_type) {
                User::class => [
                    'id' => $this->donator->id,
                    'first_name' => $this->donator->first_name,
                    'last_name' => $this->donator->last_name,
                    'username' => $this->donator->username,
                    'avatar' => $this->donator->avatar ?
                        asset("storage/{$this->donator->avatar}") : null,
                    'email' => $this->donator->email,
                    'phone' => $this->donator->phone
                ],
                default => null
            }),
            'donator_id' => $this->donator_id,
            'donator_type' => $this->donator_type,
            'campaign' => $this->whenLoaded('campaign', fn () => match ($this->campain_type) {
                DonationCampaign::class => DonationCampaignResource::make($this->campaigm),
                default => null
            }),
            'campaign_id' => $this->campaign_id,
            'campaign_type' => $this->campaign_type,
            'transaction' => $this->whenLoaded('transaction', function () {
                return TransactionResource::make($this->transaction);
            }),
            'transaction_id' => $this->transaction_id,
            'comments' => $this->comments,
            'created_at' => $this->created_at?->format('d M Y H:m')
        ];
    }
}

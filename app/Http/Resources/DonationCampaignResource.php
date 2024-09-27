<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationCampaignResource extends JsonResource
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
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image ? asset($this->image) : null,
            'target_amount' => (float) $this->target_amount,
            'min_donation_amount' => (float) $this->min_donation_amount,
            'number_of_participants' => (int) $this->number_of_participants,
            'currency' => $this->currency,
            'category_id' => (int) $this->category_id,
            'category' => $this->whenLoaded('category', fn () => CategoryResource::make($this->category)),
            'receiver_id' => $this->receiver_id,
            'receiver_type' => $this->receiver_type,
            'receiver' => $this->whenLoaded('receiver', fn () => match ($this->receiver_type) {
                User::class => [
                    'id' => $this->receiver->id,
                    'first_name' => $this->receiver->first_name,
                    'last_name' => $this->receiver->last_name,
                    'username' => $this->receiver->username,
                    'avatar' => $this->receiver->avatar ?
                        asset("storage/{$this->receiver->avatar}") : null,
                    'email' => $this->receiver->email,
                    'phone' => $this->receiver->phone
                ],
                default => null
            }),
            'creator_id' => $this->creator_id,
            'creator_type' => $this->creator_type,
            'creator' => $this->whenLoaded('creator', fn () => match ($this->creator_type) {
                User::class => [
                    'id' => $this->creator->id,
                    'first_name' => $this->creator->first_name,
                    'last_name' => $this->creator->last_name,
                    'username' => $this->creator->username,
                    'avatar' => $this->creator->avatar ?
                        asset("storage/{$this->creator->avatar}") : null,
                    'email' => $this->creator->email,
                    'phone' => $this->creator->phone
                ],
                default => null
            }),
            'donations' => $this->whenLoaded('donations', fn () => DonationResource::collection($this->donations)),
            'total_donations_count' => $this->whenCounted('donations'),
            'total_donations_amount' => $this->whenHas('donations_sum_base_amount', function () {
                return $this->donations_sum_base_amount ?: 0;
            }),
            'can_receive_donations' => $this->canReceiveDonations(),
            'ends_at' => $this->ends_at?->format('d M Y H:m'),
            'closed_at' => $this->closed_at?->format('d M Y H:m'),
            'created_at' => $this->created_at?->format('d M Y H:m')
        ];
    }
}

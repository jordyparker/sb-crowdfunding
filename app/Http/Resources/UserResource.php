<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'avatar' => $this->avatar ? asset("storage/{$this->avatar}") : null,
            'email' => $this->email,
            'phone' => $this->phone,
            'dail_code' => $this->dail_code,
            'email_verified_at' => $this->email_verified_at,
            'phone_verified_at' => $this->phone_verified_at,
            'account_verified_at' => $this->account_verified_at,
            'created_at' => $this->created_at
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:10',
            'description' => 'required|string|max:500',
            'image' => 'nullable|image|max:5048',
            'target_amount' => 'required|numeric',
            'min_donation_amount' => 'nullable|numeric|lt:target_amount',
            'number_of_participants' => 'nullable|integer|min:2',
            'category_id' => 'nullable|exists:categories,id',
            'currency' => 'required',
            'ends_at' => 'nullable|date'
        ];
    }
}

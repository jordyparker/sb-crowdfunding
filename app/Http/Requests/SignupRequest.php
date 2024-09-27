<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'dail_code' => 'required',
            'email' => 'nullable|email|unique:users,email',
            'avatar' => 'nullable|image|max:5048',
            'phone' => [
                'required',
                Rule::unique('users', 'phone')
                    ->where('dail_code', request('dail_code'))
            ],
            'password' => 'required|confirmed|min:6'
        ];
    }
}

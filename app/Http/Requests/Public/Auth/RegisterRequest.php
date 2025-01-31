<?php

namespace App\Http\Requests\Public\Auth;

use App\Rules\EgyptianPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:2|max:50',
            'phone' => ['required', new EgyptianPhoneNumber(), 'unique:users,phone'],
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => ['required', 'min:8', 'confirmed'],
            'address_lat' => 'required|min:-90|max:90',
            'address_long' => 'required|min:-180|max:180',
            'user_type' => 'required|in:user,delivery',
        ];
    }
}

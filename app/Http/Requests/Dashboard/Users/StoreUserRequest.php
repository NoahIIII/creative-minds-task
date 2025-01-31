<?php

namespace App\Http\Requests\Dashboard\Users;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name'=>'required|min:1|max:50',
            'phone'=>'required|numeric|unique:users,phone',
            'profile_image'=>'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'status'=>'required|boolean',
            'password'=>'required|min:8|string'
        ];
    }
    public function failedValidation($validator)
    {
        return ApiResponseTrait::failedValidation($validator, [], null, 422);
    }
}

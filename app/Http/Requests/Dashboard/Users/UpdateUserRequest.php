<?php

namespace App\Http\Requests\Dashboard\Users;

use App\Rules\EgyptianPhoneNumber;
use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'phone' => ['required', Rule::unique('users', 'phone')->ignore($this->route('user')->id, 'id'), new EgyptianPhoneNumber()],
            'profile_image'=>'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'status'=>'required|boolean',
            'password'=>'nullable|min:8|string',
            'user_type'=>'required|in:user,delivery',
            'phone_verified'=>'required|boolean',
        ];
    }
    public function failedValidation($validator)
    {
        return ApiResponseTrait::failedValidation($validator, [], null, 422);
    }
}

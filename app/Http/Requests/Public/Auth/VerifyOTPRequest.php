<?php

namespace App\Http\Requests\Public\Auth;

use App\Rules\EgyptianPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class VerifyOTPRequest extends FormRequest
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
            'code'=>'required|digits:4',
            'phone'=>['required', 'exists:users,phone',new EgyptianPhoneNumber()],
        ];
    }
}

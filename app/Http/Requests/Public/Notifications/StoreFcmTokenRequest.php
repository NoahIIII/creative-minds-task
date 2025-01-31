<?php

namespace App\Http\Requests\Public\Notifications;

use Illuminate\Foundation\Http\FormRequest;

class StoreFcmTokenRequest extends FormRequest
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
            'fcm_token' => 'nullable|string|max:256',
            'device_id' => 'required_with:fcm_token|string',
            'device_type' => 'required_with:fcm_token|boolean'
        ];
    }
}

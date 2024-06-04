<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

class DriverUpdateRequest extends FormRequest
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
            'name' => 'filled',
            'phone_number' => 'filled',
            'password' => 'filled',
            'avatar' => 'filled',
            'review_rate' => 'filled',
            'current_location' => 'filled',
            'status' => 'filled',
            'fcm_token' => 'filled'
        ];
    }
}

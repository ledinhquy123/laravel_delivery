<?php

namespace App\Http\Requests\User;

use App\Models\Driver;
use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name' => 'filled|string',
            'phone_number' => 'filled|string|unique:users,id',
            'password' => 'filled|confirmed|min:6',
            'address' => 'filled|json',
            'avatar' => 'filled|nullable',
            'fcm_token' => 'filled|string'
        ];
    }
}
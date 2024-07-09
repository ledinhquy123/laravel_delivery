<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
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
            'user_id' => "filled",
            'driver_id' => "filled",
            'items' => 'filled',
            'from_address' => 'filled',
            'to_address' => 'filled',
            'shipping_cost' => 'filled',
            'order_status_id' => "filled",
            'driver_accept_at' => 'filled',
            'complete_at' => 'filled',
            'user_note' => 'filled',
            'receiver' => 'filled',
            'driver_rate' => 'filled',
            'distance' => 'filled'
        ];
    }
}

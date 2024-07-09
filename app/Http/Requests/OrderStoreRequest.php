<?php

namespace App\Http\Requests;

use App\Models\Driver;
use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
        $userModel = get_class(new User());
        $driverModel = get_class(new Driver());
        $orderStatusModel = get_class(new OrderStatus());

        return [
            'user_id' => "required|exists:{$userModel},id",
            'driver_id' => "nullable|exists:{$driverModel},id",
            'items' => 'required|json',
            'from_address' => 'required|json',
            'to_address' => 'required|json',
            'shipping_cost' => 'required|numeric',
            'order_status_id' => "required|exists:{$orderStatusModel},id",
            'driver_accept_at' => 'nullable|string',
            'complete_at' => 'nullable|string',
            'user_note' => 'nullable|string',
            'receiver' => 'required|json',
            'driver_rate' => 'nullable|integer',
            'distance' => 'required|numeric',
            'serverKey' => 'required|string'
        ];
    }
}
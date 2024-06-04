<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\DriverUpdateRequest;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function update(Driver $driver, DriverUpdateRequest $request) {
        $data = $request->validated();
        
        $driver->name = $data['name'] ?? $driver->name;
        $driver->phone_number = $data['phone_number'] ?? $driver->phone_number;
        $driver->password = isset($data['password']) == 1  ? Hash::make($data['password']) : $driver->password;
        $driver->avatar = $data['avatar'] ?? $driver->avatar;
        $driver->review_rate = $data['review_rate'] ?? $driver->review_rate;
        $driver->current_location = $data['current_location'] ?? $driver->current_location;
        $driver->status = $data['status'] ?? $driver->status;
        $driver->fcm_token = $data['fcm_token'] ?? $driver->fcm_token;
        $driver->save();

        return $this->success(
            $driver,
            'Driver has been updated successfully'
        );
    }
}

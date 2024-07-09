<?php

namespace App\Http\Controllers\Auth;

use App\Events\SendOtpEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\DriverLoginRequest;
use App\Http\Requests\Auth\DriverRegisterRequest;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Http\Controllers\NotificationController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function register(DriverRegisterRequest $request) {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        
        $driver = Driver::create($data);
        $driver = [
            $driver
        ];
        return $this->success($driver, 'Driver has been register successfully');
    }

    public function login($data, $tokenDevice, $serverKey) {
        $token = auth('driver_jwt')->attempt($data); // Create JWT token

        if(!$token) {
            return $this->error('Invalid login');
        }

        $optCode = str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
        $notiStatus = NotificationController::notify(
            'OTP Code', 
            $optCode, 
            $tokenDevice,
            $serverKey
        );

        if($notiStatus == false) {
            return $this->error('The Otp sent failed');
        }
        
        $this->updateFcmToken(auth('driver_jwt')->id(), $tokenDevice);

        $data = [
            'driver' => auth('driver_jwt')->user(),
            'token' => $token
        ];
        return $this->success($data, 'Driver has been login successfully');
    }

    public function logout() {
        auth('driver_jwt')->logout();
        return $this->success(null, 'Driver has been logout successfully');
    }

    public function updateFcmToken($driverId, $fcmToken) {
        $driver = Driver::find($driverId);
        if(!$driver) return false;

        $driver->fcm_token = $fcmToken;
        $driver->save();

        return true;
    }
}
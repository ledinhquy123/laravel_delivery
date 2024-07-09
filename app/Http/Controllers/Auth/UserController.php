<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserLoginEvent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request) {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        
        $user = User::create($data);
        return $this->success($user, 'User has been register successfully');
    }

    public function login($data, $tokenDevice, $serverKey) {
        $token = auth('user_jwt')->attempt($data); // Create JWT token

        if(!$token) {
            return $this->error('Invalid login details');
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

        $data = [
            'user' => auth('user_jwt')->user(),
            'token' => $token
        ];
        return $this->success($data, 'User has been login successfully');
    }

    public function logout() {
        auth('user_jwt')->logout();
        return $this->success(null, 'User has been logout successfully');
    }
}
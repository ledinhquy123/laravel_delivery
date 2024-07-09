<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\VerifyPhoneNumberRequest;
use App\Models\Driver;
use App\Models\User;

class AuthController extends Controller
{
    public function login(VerifyPhoneNumberRequest $request) {
        $data = $request->validated();
        
        if($data['guard'] == 'driver') {
            $auth = new DriverController;

            return $auth->login([
                'phone_number' => $data['phone_number'],
                'password' => $data['password'],
            ], $data['token'], $data['serverKey']);
        } 
        elseif($data['guard'] == 'user') {
            $auth = new UserController;

            return $auth->login([
                'phone_number' => $data['phone_number'],
                'password' => $data['password']
            ], $data['token'], $data['serverKey']);
        }
        return $this->error('User invalid', 400);
    }
}
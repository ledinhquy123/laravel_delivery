<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // {"lat": "10.758970", "lng": "106.675461"}
    public function update(UserUpdateRequest $request, User $user) {
        $data = $request->validated();

        $user->name = $data['name'] ?? $user->name;
        $user->phone_number = $data['phone_number'] ?? $user->phone_number;
        $user->password = isset($data['password']) == 1  ? Hash::make($data['password']) : $user->password;
        $user->address = $data['address'] ?? $user->address;
        $user->avatar = isset($data['avatar']) == 1 ? $this->saveImage($data['avatar'], 'users') : $user->avatar;
        $user->fcm_token = $data['fcm_token'] ?? $user->fcm_token;
        $user->save();
        
        return $this->success(
            $user,
            'User has been updated successfully'
        );
    }
}
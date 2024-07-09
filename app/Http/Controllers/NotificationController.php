<?php

namespace App\Http\Controllers;

use App\Models\DriverNotification;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    static function notify($title, $body, $device_key, $serverKey) {
        $url = 'https://fcm.googleapis.com/v1/projects/testnotifications-2efc1/messages:send';

        $data = [
            "message" => [
                "token" => $device_key,
                "notification" => [
                    "body" => $body,
                    "title" => $title
                ]
            ]
        ];

        $encodeData = json_encode($data);

        $headers = [
            "Authorization: Bearer ".$serverKey,
            "Content-Type:application/json",
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodeData);


        $result = json_decode(curl_exec($ch));
        return !isset($result->error);
    }   

    public function index(Request $request) {
        $guard = $request->input('guard', 'user');
        $id = $request->input('id');

        $notifications = [];
        if($guard == 'user') {
            $notifications = UserNotification::where('user_id', $id)->with('user')
            ->latest()
            ->get();
        }else if($guard == 'driver') {
            $notifications = DriverNotification::where('driver_id', $id)->with('driver')
            ->latest()
            ->get();
        }

        return $this->success($notifications, 'Notification has been got successfully');
    }
}
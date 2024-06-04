<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

abstract class Controller
{
    public function success(mixed $data, string $message = 'okay', int $statusCode = 200) {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message
        ], $statusCode);
    }

    public function error(string $message, int $statusCode = 500) {
        return response()->json([
            'success' => false,
            'data' => null,
            'message' => $message
        ], $statusCode);
    }

    public function saveImage($image, $path = 'public') {
        if(!$image) {
            return null;
        }

        $fileName = time().'.png';
        // Save image
        Storage::disk($path)->put($fileName, base64_decode($image));

        return URL::to('/').'/'.'storage/'.$path.'/'.$fileName;
    }
}

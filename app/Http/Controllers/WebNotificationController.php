<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendWebNotificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;

class WebNotificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('home');
    }

    public function storeToken(Request $request): JsonResponse
    {
        auth()->user()->update([
            'device_key' => $request->token,
        ]);
        return response()->json(['Token successfully stored.']);
    }

    public function sendWebNotification(SendWebNotificationRequest $request)
    {
        $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();
        $data = $request->validated();
        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $data['title'],
                "body" => $data['body'],
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . config('notification.firebase.server_key'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, config('notification.firebase.fcm_api_url'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        return $result;
    }
}

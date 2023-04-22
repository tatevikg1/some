<?php

return [
    'firebase' => [
        'fcm_api_url' => env('FCM_API_URL', 'https://fcm.googleapis.com/fcm/send'),
        'server_key' =>  env('FIREBASE_SERVER_KEY'),

        'api_key' => env('FIREBASE_API_KEY'),
        'auth_domain' => env('FIREBASE_AUTH_DOMAIN', 'cookbook-5fdd3.firebaseapp.com'),
        'project_id' => env('FIREBASE_PROJECT_ID','cookbook-5fdd3'),
        'storage_bucket' => env('FIREFOX_STORAGE_BACKET', 'cookbook-5fdd3.appspot.com'),
        'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID', '500719604396'),
        'app_id' => env('FIREBASE_APP_ID', '1:500719604396:web:5041e6221a5667c2a9c509'),
        'measurement_id' => env('FIREBASE_MEASUREMENT_ID','G-0507867HWH'),
    ],
];

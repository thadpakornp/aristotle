<?php

return [
    'driver' => env('FCM_PROTOCOL', 'http'),
    'log_enabled' => false,

    'http' => [
        'server_key' => 'AAAARQzV3w8:APA91bErqnAruYBzeoxsvTMAdvfc_S2IdltVdd-cTtjevrFo099ITHlJFf5ZpFYcn37tULxCO1IqNsDw7qxuwnmYzZj1Esc7UZ0EMnhWa3y_aBLpjKFWA7La4nMD--nrqByfLj4QcpBY',
        'sender_id' => '296568086287',
        'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_group_url' => 'https://android.googleapis.com/gcm/notification',
        'timeout' => 30.0, // in second
    ],
];

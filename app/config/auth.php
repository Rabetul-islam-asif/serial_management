<?php

return [
    'session_lifetime' => 120, // 2 hours
    'otp_expiry' => 300,       // 5 minutes
    'otp_attempts_limit' => 3, // Block after 3 failed verification attempts
    'rate_limits' => [
        'login' => [
            'attempts' => 10,
            'window' => 900 // 15 mins
        ],
        'otp' => [
            'attempts' => 5,
            'window' => 900 // 15 mins
        ]
    ]
];

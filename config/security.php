<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security-related configuration options for the
    | Cyber Infinity platform.
    |
    */

    'rate_limits' => [
        'login_attempts' => [
            'max_attempts' => env('LOGIN_MAX_ATTEMPTS', 5),
            'lockout_time' => env('LOGIN_LOCKOUT_TIME', 60), // seconds
        ],
        'flag_submissions' => [
            'challenge_max_attempts' => env('CHALLENGE_FLAG_MAX_ATTEMPTS', 10),
            'challenge_lockout_time' => env('CHALLENGE_FLAG_LOCKOUT_TIME', 3600), // 1 hour
            'ctf_max_attempts' => env('CTF_FLAG_MAX_ATTEMPTS', 5),
            'ctf_lockout_time' => env('CTF_FLAG_LOCKOUT_TIME', 600), // 10 minutes
        ],
    ],

    'file_uploads' => [
        'max_size' => env('UPLOAD_MAX_SIZE', 51200), // 50MB in KB
        'allowed_types' => [
            'challenge_files' => ['zip', 'rar', 'txt', 'pdf', 'jpg', 'jpeg', 'png', 'gif'],
            'ctf_files' => ['zip', 'rar', 'txt', 'pdf', 'jpg', 'jpeg', 'png', 'gif'],
            'banners' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'videos' => ['mp4', 'webm', 'avi', 'mov'],
        ],
        'storage_path' => env('UPLOAD_STORAGE_PATH', 'public'),
    ],

    'validation' => [
        'password_regex' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
        'flag_regex' => '/^[a-zA-Z0-9_\-{}]+$/',
        'name_regex' => '/^[a-zA-Z\s]+$/',
        'filename_regex' => '/^[a-zA-Z0-9._-]+$/',
    ],

    'user_constraints' => [
        'minimum_age' => env('USER_MINIMUM_AGE', 13),
        'max_name_length' => env('USER_MAX_NAME_LENGTH', 255),
        'max_email_length' => env('USER_MAX_EMAIL_LENGTH', 255),
    ],

    'session' => [
        'secure_cookies' => env('SESSION_SECURE_COOKIES', true),
        'same_site' => env('SESSION_SAME_SITE', 'strict'),
        'http_only' => env('SESSION_HTTP_ONLY', true),
    ],

    'headers' => [
        'x_frame_options' => env('X_FRAME_OPTIONS', 'DENY'),
        'x_content_type_options' => env('X_CONTENT_TYPE_OPTIONS', 'nosniff'),
        'x_xss_protection' => env('X_XSS_PROTECTION', '1; mode=block'),
        'referrer_policy' => env('REFERRER_POLICY', 'strict-origin-when-cross-origin'),
    ],
];

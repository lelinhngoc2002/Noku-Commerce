<?php
// Cấu hình bảo mật
return [
    // Cấu hình session
    'session' => [
        'lifetime' => 120, // Thời gian sống của session (phút)
        'path' => '/',
        'domain' => '',
        'secure' => true, // Chỉ gửi cookie qua HTTPS
        'httponly' => true, // Chặn truy cập cookie qua JavaScript
        'samesite' => 'Strict' // Chống CSRF
    ],

    // Cấu hình password
    'password' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_special_chars' => true
    ],

    // Cấu hình rate limiting
    'rate_limit' => [
        'login' => [
            'max_attempts' => 5,
            'lockout_time' => 60 // 1 phút
        ],
        'api' => [
            'max_requests' => 100,
            'time_window' => 3600 // 1 giờ
        ]
    ],

    // Cấu hình CSRF
    'csrf' => [
        'token_name' => 'csrf_token',
        'token_length' => 32,
        'expire_time' => 3600 // 1 giờ
    ],

    // Cấu hình file upload
    'upload' => [
        'max_size' => 5 * 1024 * 1024, // 5MB
        'allowed_types' => [
            'image/jpeg',
            'image/png',
            'image/gif'
        ],
        'allowed_extensions' => [
            'jpg',
            'jpeg',
            'png',
            'gif'
        ]
    ],

    // Cấu hình headers bảo mật
    'headers' => [
        'X-Frame-Options' => 'SAMEORIGIN',
        'X-XSS-Protection' => '1; mode=block',
        'X-Content-Type-Options' => 'nosniff',
        'Content-Security-Policy' => "default-src 'self' 'unsafe-inline' 'unsafe-eval' data: *.googleapis.com *.gstatic.com;",
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
        'Referrer-Policy' => 'strict-origin-when-cross-origin'
    ],

    // Cấu hình logging
    'logging' => [
        'enabled' => true,
        'path' => __DIR__ . '/../logs',
        'level' => 'error', // debug, info, warning, error, critical
        'max_files' => 5,
        'max_size' => 5 * 1024 * 1024 // 5MB
    ],

    // Cấu hình backup
    'backup' => [
        'enabled' => true,
        'path' => __DIR__ . '/../backups',
        'max_files' => 10,
        'schedule' => 'daily' // daily, weekly, monthly
    ]
];
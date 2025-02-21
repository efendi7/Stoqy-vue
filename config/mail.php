<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | Mailer default yang akan digunakan untuk mengirim email. Pilihan yang 
    | tersedia: "smtp", "sendmail", "mailgun", "ses", "postmark", "resend", "log", 
    | "array", "failover", "roundrobin".
    |
    */

    'default' => env('MAIL_MAILER', 'smtp'), // Gunakan SMTP sebagai default

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Mailer
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk semua mailer yang digunakan dalam aplikasi.
    | Anda bisa menambahkan pengaturan tambahan sesuai kebutuhan.
    |
    */

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.gmail.com'), // Sesuaikan dengan penyedia email
            'port' => env('MAIL_PORT', 587), // Gunakan port 587 untuk TLS atau 465 untuk SSL
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'), // Bisa "ssl" jika menggunakan port 465
            'timeout' => null,
            'auth_mode' => null,
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
        ],

        'resend' => [
            'transport' => 'resend',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL', 'stack'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
        ],

        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => [
                'ses',
                'postmark',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Alamat Email Default "From"
    |--------------------------------------------------------------------------
    |
    | Semua email yang dikirim dari aplikasi akan berasal dari alamat ini.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'your_email@gmail.com'), // Sesuaikan dengan email Anda
        'name' => env('MAIL_FROM_NAME', 'Stockify'),
    ],

];

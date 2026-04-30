<?php

declare(strict_types=1);

return [
    'default'  => env('SPEEDY_DEFAULT_ACCOUNT', 'main'),

    'accounts' => [
        'main' => [
            'base_url'         => env('SPEEDY_BASE_URL', 'https://api.speedy.bg/v1'),
            'user_name'        => env('SPEEDY_USERNAME'),
            'password'         => env('SPEEDY_PASSWORD'),
            'language'         => env('SPEEDY_LANGUAGE'),
            'client_system_id' => env('SPEEDY_CLIENT_SYSTEM_ID') === null
                ? null
                : (int) env('SPEEDY_CLIENT_SYSTEM_ID'),
            'timeout'          => (int) env('SPEEDY_TIMEOUT', 30),

            // Extra hostnames allowed for base_url beyond api.speedy.bg + local dev hosts.
            // Use only if Speedy gives you a non-canonical endpoint (sandbox, partner, etc.).
            'additional_allowed_hosts' => array_filter(explode(',', (string) env('SPEEDY_ALLOWED_HOSTS', ''))),
        ],
    ],

    'nomenclatures' => [
        'enabled'  => env('SPEEDY_SYNC_NOMENCLATURES', false),
        'entities' => ['countries', 'states', 'sites', 'streets', 'postcodes', 'offices'],
        'schedule' => '0 3 * * *',
    ],
    'shipments' => [
        'enabled'      => env('SPEEDY_PERSIST_SHIPMENTS', false),
        'auto_persist' => env('SPEEDY_AUTO_PERSIST', false),
    ],
    'tracking' => [
        'enabled'    => env('SPEEDY_TRACK_SHIPMENTS', false),
        'poll_batch' => 200,
        'schedule'   => '*/15 * * * *',
    ],
];

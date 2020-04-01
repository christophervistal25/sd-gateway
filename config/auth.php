<?php

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'devices',
    ],

    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'devices',
        ],
    ],

    'providers' => [
        'devices' => [
            'driver' => 'eloquent',
            'model' => App\Device::class
        ]
    ]
];

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'socials' => [
        'facebook' => [
            'clientId' => '1758040850935285',
            'clientSecret' => '60232ca1f8860f9ce9c7f5b52a53ff55',
            'redirectUrl' => env('APP_URL').'/'
        ],
        'vk' => [
            'clientId' => '4689934',
            'clientSecret' => 'CcYXIqshESPYX3pULIKH',
            'redirectUrl' => env('APP_URL')
        ],
        'google' => [
            'clientId' => '135562740050-brfjngcfaui2eicj1vpq801hfm07osa4.apps.googleusercontent.com',
            'clientSecret' => 'TJAEEmjDB_j3TaXq6HdoTTqA',
            'redirectUrl' => env('APP_URL')
        ],
    ]
];

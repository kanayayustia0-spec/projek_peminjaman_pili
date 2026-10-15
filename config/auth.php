<?php

use App\Models\User;

return [

   'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'admin' => [ // <--- TAMBAHKAN INI
            'driver' => 'session',
            'provider' => 'admins',
        ],
    ],
   'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

        'admins' => [ // <--- TAMBAHKAN INI
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
    ],
];
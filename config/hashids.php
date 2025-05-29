<?php
return [

    'default' => 'main',

    'connections' => [

        'main' => [
            'salt' => env('HASHIDS_SALT', 'iniSaltRahasia'), // Ganti ini!
            'length' => env('HASHIDS_LENGTH', 10),
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',
        ],

    ],

];

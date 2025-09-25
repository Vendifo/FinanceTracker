<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_origins' => [
        'http://147.45.151.90:5173',
        'https://147.45.151.90:5173',
        'http://касса-крым.рф',
        'https://касса-крым.рф',
        'http://xn----7sba2bdm1aea8h.xn--p1ai',
        'https://xn----7sba2bdm1aea8h.xn--p1ai',
    ],
    'allowed_methods' => ['*'],
    'allowed_headers' => ['*'],
    'supports_credentials' => true,

];

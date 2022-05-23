<?php
return [
    'prefix' => 'api',
    'model' => [
        'authenticator' => [
            'username' => [
                'required' => true,
                'type' => 'string',
                'minlength' => 4,
                'maxlength' => 70
            ],
            'email' => [
                'required' => true,
                'type' => 'email',
                'minlength' => 4,
                'maxlength' => 70
            ],
            'password' => [
                'required' => true,
                'type' => 'string',
                'minlength' => 8,
                'maxlength' => 70
            ],
        ],
        'forgot_password' => [
            'username' => [
                'required' => true,
                'unique' => true,
                'type' => 'string',
                'minlength' => 4,
                'maxlength' => 70
            ],
            'email' => [
                'required' => true,
                'unique' => true,
                'type' => 'email',
                'minlength' => 4,
                'maxlength' => 70
            ]
        ]
    ],
    'sanctum' => [
        'enabled' => false
    ],
    'abilities' => [
        'auth-login',
        'auth-logout',
        'auth-signup',
        'auth-forgot-password',
        'auth-reset-password',
    ],
    'middleware' => ['api']
];
?>
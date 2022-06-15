<?php
return [
    'prefix' => 'api',
    'model' => [
        'authenticator' => [
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
            ],
            'password' => [
                'required' => true,
                'type' => 'string',
                'minlength' => 8,
                'maxlength' => 70
            ],
            'status' => [
                'required' => true,
                'type' => 'integer',
                'default' => 1
            ],
            'role_id' => [
                'required' => true,
                'type' => 'integer',
                'default' => 1
            ]
        ],
        'user_profile' => [
            'user_id' => [
                'required' => true,
                'type' => 'integer',
            ],
            'first_name' => [
                'required' => true,
                'type' => 'string',
                'minlength' => 1,
                'maxlength' => 70
            ],
            'last_name' => [
                'required' => true,
                'type' => 'string',
                'minlength' => 1,
                'maxlength' => 70
            ]
        ],
        'user_role' => [
            'role' => [
                'required' => true,
                'unique' => true,
                'type' => 'string',
                'minlength' => 1,
                'maxlength' => 25
            ],
            'description' => [
                'required' => true,
                'type' => 'string',
                'minlength' => 1,
                'maxlength' => 100
            ]
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
        'enabled' => true
    ],
    'abilities' => [
        'auth-login',
        'auth-logout',
        'auth-signup',
        'auth-forgot-password',
        'auth-reset-password',
        'auth-change-password'
    ],
    'middleware' => ['api']
];
?>
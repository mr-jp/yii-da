<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    // da-upload-test account
    // 'da' => [
    //     'client_id' => '9794',
    //     'client_secret' => '285eb185cc10785ee4fe2d444dbcd7a0',
    //     'redirect_uri' => 'http://da-upload-php.com',
    //     'auth_url' => 'https://www.deviantart.com/oauth2/authorize',
    //     'token_url' => 'https://www.deviantart.com/oauth2/token',
    //     'response_type' => 'code',
    //     'api_url' => 'https://www.deviantart.com/api/v1/oauth2',
    //     'scope' => 'basic stash feed publish browse collection'
    // ],
    // personal account
    'da' => [
        'client_id' => '9807',
        'client_secret' => '818e72188658d058e99da3d98f24ac7c',
        'redirect_uri' => 'http://da-upload-php.com',
        'auth_url' => 'https://www.deviantart.com/oauth2/authorize',
        'token_url' => 'https://www.deviantart.com/oauth2/token',
        'response_type' => 'code',
        'api_url' => 'https://www.deviantart.com/api/v1/oauth2',
        'scope' => 'basic stash feed publish browse collection'
    ],
    'menuItems' => [
        'loggedInItems' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Stash', 'url' => ['/stash/index']],
            ['label' => 'Gallery', 'url' => ['/gallery/index']],
            ['label' => 'Logout', 'url' => ['/site/logout']],
        ],
        'loggedOutItems' => [
            ['label' => 'Home', 'url' => ['/site/index']],
        ]
    ]
];

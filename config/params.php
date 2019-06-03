<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'da' => [
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

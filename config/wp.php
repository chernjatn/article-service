<?php

return [
    'login' => env('WP_USER', 'api-editor'),
    'password' => env('WP_PASSWORD', 'pPQ49fkv15qHzpc9Jowqp5JR'),
    'url' => env('WP_URL', 'https://sovety.superapteka.ru/wp-json/wp/v2/'),
    'url_edit' => 'https://sovety.superapteka.ru/wp-admin/post.php?post=$id&action=edit',
    'token' => env('API_TOKEN', 'test'),
];

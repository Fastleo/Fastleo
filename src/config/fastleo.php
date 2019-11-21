<?php

return [
    'images' => ['png', 'jpg', 'jpeg', 'gif'],

    'uploads' => 'public/uploads',

    'exclude' => [

        'get_list' => ['id', '_token', '_return', '_search', 'page'],

        'list_name' => ['sort', 'menu', 'password', 'remember_token', 'admin'],

        'row_name' => ['id', 'sort', 'menu', 'password', 'remember_token', 'created_at', 'updated_at'],
    ]
];
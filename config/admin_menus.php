<?php
return [
    [
        'name' => 'dashboard',
        'priority' => 5,
        'title' => 'Bảng tin',
        'url' => env('APP_URL') . '/admin/dashboard',
        'icon' => 'dashicons-before dashicons-dashboard',
    ],
    [
        'name' => 'posts',
        'priority' => 10,
        'title' => 'Bài viết',
        'url' => env('APP_URL') . '/admin/posts/post',
        'icon' => 'dashicons-before dashicons-admin-post',
        'sub' => [
            ['priority' => 5, 'title' => 'Tất cả bài viết', 'url' => env('APP_URL') . '/admin/posts/post'],
            ['priority' => 10, 'title' => 'Thêm bài viết', 'url' => env('APP_URL') . '/admin/posts/post/add'],
            ['priority' => 15, 'title' => 'Chuyên mục bài viết', 'url' => env('APP_URL') . '/admin/taxonomy/post-category'],
            ['priority' => 20, 'title' => 'Thẻ bài viết', 'url' => env('APP_URL') . '/admin/taxonomy/post-tag']
        ]
    ],
    [
        'name' => 'media',
        'priority' => 15,
        'title' => 'Thư viện',
        'url' => env('APP_URL') . '/admin/media',
        'icon' => 'dashicons-before dashicons-admin-media',
    ],
    [
        'name' => 'users',
        'priority' => 45,
        'title' => 'Người dùng',
        'url' => env('APP_URL') . '/admin/users',
        'icon' => 'dashicons-before dashicons-admin-users',
        'sub' => [
            ['priority' => 5, 'title' => 'Tất cả người dùng', 'url' => env('APP_URL') . '/admin/users'],
            ['priority' => 10, 'title' => 'Thêm người dùng mới', 'url' => env('APP_URL') . '/admin/users/action/add'],
            ['priority' => 15, 'title' => 'Hồ sơ của tôi', 'url' => env('APP_URL') . '/admin/users/action/profile'],
        ]
    ],
];

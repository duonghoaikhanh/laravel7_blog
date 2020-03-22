<?php
return [
    'post' => [
        'columns' => ['title', 'author', 'categories', 'tags', 'comments', 'views', 'seo', 'date'],
        'title' => 'post.post.title',
        'widgets' => [
            // 'left' => ['title', 'slug', 'editor', 'excerpt', 'seo', 'author', 'discussion', 'comments'],
            'left' => ['title', 'slug', 'editor', 'excerpt', 'author'],
            'right' => ['general', 'categories', 'tags', 'image']
        ]
    ],
    'page' => [
        'columns' => ['title', 'author', 'seo', 'date'],
        'title' => 'post.page.title',
        'widgets' => [
            'left' => ['title', 'slug', 'editor', 'excerpt', 'seo'],
            'right' => ['general']
        ]
    ],
    'faq' => [
        'columns' => ['image', 'title', 'author', 'categories', 'views', 'seo', 'date'],
        'title' => 'post.product.title',
        'widgets' => [
            'left' => ['title', 'slug', 'editor', 'excerpt', 'seo'],
            'right' => ['general', 'categories', 'image', 'gallery']
        ]
    ],
    'product' => [
        'columns' => ['image', 'title', 'author', 'categories', 'views', 'seo', 'date'],
        'title' => 'post.product.title',
        'widgets' => [
            'left' => ['title', 'slug', 'product_function', 'product_howtouse', 'excerpt', 'seo'],
            'right' => ['general', 'price', 'price_original', 'categories', 'item_focus', 'image', 'gallery']
        ]
    ],
    'introduction' => [
        'columns' => ['image', 'title', 'author', 'categories', 'views', 'seo', 'date'],
        'title' => 'post.introduction.title',
        'widgets' => [
            'left' => ['title', 'slug', 'editor', 'excerpt', 'seo'],
            'right' => ['general', 'categories', 'image', 'gallery']
        ]
    ],
    'service' => [
        'columns' => ['image', 'title', 'author', 'categories', 'views', 'seo', 'date'],
        'title' => 'post.service.title',
        'widgets' => [
            'left' => ['title', 'slug', 'service_time_price', 'service_info_bottle', 'service_info_face', 'service_info_flower', 'editor', 'excerpt', 'seo'],
            'right' => ['general', 'price', 'price_original', 'categories', 'item_focus', 'image', 'gallery']
        ]
    ],
    'knowledge' => [
        'columns' => ['image', 'title', 'author', 'categories', 'views', 'seo', 'date'],
        'title' => 'post.knowledge.title',
        'widgets' => [
            'left' => ['title', 'slug', 'editor', 'excerpt', 'seo'],
            'right' => ['general', 'categories', 'image', 'gallery']
        ]
    ],
    'promotion' => [
        'columns' => ['image', 'title', 'author', 'categories', 'views', 'seo', 'date'],
        'title' => 'post.promotion.title',
        'widgets' => [
            'left' => ['title', 'slug', 'editor', 'excerpt', 'seo'],
            'right' => ['general', 'categories', 'image', 'gallery']
        ]
    ],
    'customer-feedback' => [
        'columns' => ['title', 'customer_name', 'date'],
        'title' => 'post.customer_feedback.title',
        'widgets' => [
            'left' => ['title', 'customer_name', 'editor'],
            'right' => ['general', 'image']
        ]
    ]
];

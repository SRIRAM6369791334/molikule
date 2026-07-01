<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Product Management Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the enhanced product management system
    |
    */

    // Cache settings
    'cache' => [
        'enabled' => env('PRODUCT_CACHE_ENABLED', true),
        'ttl' => env('PRODUCT_CACHE_TTL', 3600), // 1 hour
        'tags' => env('PRODUCT_CACHE_TAGS', true),
    ],

    // Analytics settings
    'analytics' => [
        'enabled' => env('PRODUCT_ANALYTICS_ENABLED', true),
        'track_views' => env('TRACK_PRODUCT_VIEWS', true),
        'track_searches' => env('TRACK_PRODUCT_SEARCHES', true),
        'days_to_keep' => env('ANALYTICS_DAYS_TO_KEEP', 90),
    ],

    // Image optimization settings
    'images' => [
        'optimization' => env('IMAGE_OPTIMIZATION_ENABLED', true),
        'quality' => env('IMAGE_QUALITY', 85),
        'max_size' => env('MAX_IMAGE_SIZE', 8192), // KB
        'allowed_types' => ['jpeg', 'jpg', 'png', 'webp'],
        'sizes' => [
            'thumbnail' => [150, 150],
            'small' => [300, 300],
            'medium' => [600, 600],
            'large' => [1200, 1200]
        ],
    ],

    // SEO settings
    'seo' => [
        'auto_generate_slugs' => env('AUTO_GENERATE_SLUGS', true),
        'max_title_length' => env('SEO_MAX_TITLE_LENGTH', 60),
        'max_description_length' => env('SEO_MAX_DESCRIPTION_LENGTH', 160),
        'default_robots' => env('SEO_DEFAULT_ROBOTS', 'index,follow'),
    ],

    // Performance settings
    'performance' => [
        'enable_search_optimization' => env('ENABLE_SEARCH_OPTIMIZATION', true),
        'search_cache_ttl' => env('SEARCH_CACHE_TTL', 300), // 5 minutes
        'enable_lazy_loading' => env('ENABLE_LAZY_LOADING', true),
        'pagination_default' => env('DEFAULT_PAGINATION', 15),
    ],

    // Inventory tracking
    'inventory' => [
        'low_stock_threshold' => env('LOW_STOCK_THRESHOLD', 15),
        'track_quantity' => env('TRACK_QUANTITY', true),
        'continue_selling_out_of_stock' => env('CONTINUE_SELLING_OUT_OF_STOCK', false),
    ],

    // Validation rules
    'validation' => [
        'max_name_length' => env('MAX_PRODUCT_NAME_LENGTH', 255),
        'max_description_length' => env('MAX_DESCRIPTION_LENGTH', 10000),
        'max_short_description_length' => env('MAX_SHORT_DESCRIPTION_LENGTH', 500),
        'price_min' => env('MIN_PRICE', 0.01),
        'price_max' => env('MAX_PRICE', 999999.99),
        'stock_min' => env('MIN_STOCK', 0),
        'stock_max' => env('MAX_STOCK', 999999),
    ],

    // API settings
    'api' => [
        'rate_limit' => env('API_RATE_LIMIT', 100),
        'rate_limit_window' => env('API_RATE_LIMIT_WINDOW', 60), // seconds
        'enable_cors' => env('ENABLE_API_CORS', true),
        'pagination_max' => env('API_PAGINATION_MAX', 100),
    ],

    // Security settings
    'security' => [
        'enable_csrf' => env('ENABLE_API_CSRF', true),
        'sanitize_input' => env('SANITIZE_INPUT', true),
        'max_file_size' => env('MAX_FILE_SIZE', 8192), // KB
        'allowed_file_types' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
    ],
];
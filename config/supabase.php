<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supabase Configuration
    |--------------------------------------------------------------------------
    |
    | Supabase 数据库和存储服务配置
    |
    */

    'url' => env('SUPABASE_URL'),
    'key' => env('SUPABASE_KEY'),
    'secret' => env('SUPABASE_SECRET'),
    
    'database' => [
        'host' => env('SUPABASE_DB_HOST'),
        'port' => env('SUPABASE_DB_PORT', 5432),
        'database' => env('SUPABASE_DB_DATABASE', 'postgres'),
        'username' => env('SUPABASE_DB_USERNAME', 'postgres'),
        'password' => env('SUPABASE_DB_PASSWORD'),
    ],
    
    'storage' => [
        'bucket' => env('SUPABASE_STORAGE_BUCKET', 'uploads'),
        'public_url' => env('SUPABASE_STORAGE_URL'),
    ],
    
    'auth' => [
        'enabled' => env('SUPABASE_AUTH_ENABLED', false),
        'redirect_url' => env('SUPABASE_AUTH_REDIRECT_URL'),
    ],
];

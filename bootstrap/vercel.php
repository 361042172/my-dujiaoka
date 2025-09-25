<?php

/**
 * Vercel 环境适配配置
 * 在 Vercel 环境中优化 Laravel 配置
 */

// 设置 Vercel 专用的存储路径
if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL'])) {
    // 设置临时目录
    $tempPath = '/tmp/laravel';
    
    // 创建必要的临时目录
    $directories = [
        $tempPath,
        $tempPath . '/storage',
        $tempPath . '/storage/app',
        $tempPath . '/storage/app/public',
        $tempPath . '/storage/framework',
        $tempPath . '/storage/framework/cache',
        $tempPath . '/storage/framework/sessions',
        $tempPath . '/storage/framework/views',
        $tempPath . '/storage/logs',
        $tempPath . '/bootstrap',
        $tempPath . '/bootstrap/cache'
    ];
    
    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }
    
    // 设置环境变量
    $_ENV['VIEW_COMPILED_PATH'] = $tempPath . '/storage/framework/views';
    $_SERVER['VIEW_COMPILED_PATH'] = $tempPath . '/storage/framework/views';
    
    // 设置存储路径
    $_ENV['STORAGE_PATH'] = $tempPath . '/storage';
    $_SERVER['STORAGE_PATH'] = $tempPath . '/storage';
    
    // 设置缓存路径
    $_ENV['CACHE_PATH'] = $tempPath . '/storage/framework/cache';
    $_SERVER['CACHE_PATH'] = $tempPath . '/storage/framework/cache';
    
    // 设置会话路径
    $_ENV['SESSION_PATH'] = $tempPath . '/storage/framework/sessions';
    $_SERVER['SESSION_PATH'] = $tempPath . '/storage/framework/sessions';
    
    // 设置日志路径
    $_ENV['LOG_PATH'] = $tempPath . '/storage/logs';
    $_SERVER['LOG_PATH'] = $tempPath . '/storage/logs';
    
    // 优化缓存配置
    $_ENV['CACHE_DRIVER'] = 'array';
    $_ENV['SESSION_DRIVER'] = 'cookie';
    $_ENV['QUEUE_CONNECTION'] = 'sync';
    $_ENV['LOG_CHANNEL'] = 'stderr';
    
    // 禁用不必要的功能
    $_ENV['BROADCAST_DRIVER'] = 'null';
    $_ENV['MAIL_MAILER'] = $_ENV['MAIL_MAILER'] ?? 'array';
}

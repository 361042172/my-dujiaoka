<?php

/**
 * Vercel PHP Runtime Entry Point
 * 独角数卡 Vercel 适配版本
 */

// 设置错误报告
error_reporting(E_ALL);
ini_set('display_errors', 0);

// 设置时区
date_default_timezone_set('Asia/Shanghai');

// 路径设置
$basePath = __DIR__ . '/..';
$publicPath = $basePath . '/public';

// 检查必要的目录
if (!is_dir($publicPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Application not properly configured']);
    exit;
}

// 设置环境变量
$_ENV['APP_RUNNING_IN_CONSOLE'] = false;

// 更改工作目录
chdir($basePath);

// 设置 $_SERVER 变量以适配 Vercel
$_SERVER['SCRIPT_NAME'] = '/api/index.php';
$_SERVER['SCRIPT_FILENAME'] = __FILE__;
$_SERVER['DOCUMENT_ROOT'] = $publicPath;

// 处理路由
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';

// 移除 /api 前缀
if (strpos($requestUri, '/api') === 0) {
    $requestUri = substr($requestUri, 4);
}

$_SERVER['REQUEST_URI'] = $requestUri ?: '/';

// 处理静态文件
if ($requestUri !== '/' && file_exists($publicPath . $requestUri)) {
    // 让 Vercel 处理静态文件
    return false;
}

// 启动 Laravel 应用
try {
    require $publicPath . '/index.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Application Error',
        'message' => getenv('APP_DEBUG') ? $e->getMessage() : 'Internal Server Error'
    ]);
}

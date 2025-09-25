<?php

/**
 * Vercel PHP Runtime Entry Point
 * 将所有请求转发到 Laravel 的 public/index.php
 */

// 设置正确的工作目录
$publicPath = __DIR__ . '/../public';
if (!is_dir($publicPath)) {
    http_response_code(500);
    echo 'Public directory not found';
    exit;
}

// 更改工作目录到 public
chdir($publicPath);

// 设置 $_SERVER 变量以适配 Vercel
$_SERVER['SCRIPT_NAME'] = '/api/index.php';
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

// 包含 Laravel 的入口文件
require $publicPath . '/index.php';

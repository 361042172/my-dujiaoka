#!/bin/bash

# Vercel 构建脚本
# 为 Vercel 环境准备 Laravel 应用

echo "🚀 开始 Vercel 构建过程..."

# 设置错误处理
set -e

# 检查 PHP 版本
echo "📋 检查 PHP 版本..."
php --version

# 检查 Composer
echo "📦 检查 Composer..."
composer --version

# 安装依赖
echo "📦 安装 Composer 依赖..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# 检查必要的 PHP 扩展
echo "🔍 检查 PHP 扩展..."
php -m | grep -E "(pdo|pgsql|mbstring|tokenizer|xml|ctype|json|bcmath)" || echo "⚠️ 某些扩展可能缺失"

# 创建必要的目录
echo "📁 创建必要的目录..."
mkdir -p bootstrap/cache
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# 设置权限
echo "🔒 设置目录权限..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# 检查 .env 文件
if [ ! -f .env ]; then
    echo "📝 创建 .env 文件..."
    cp .env.example .env 2>/dev/null || echo "⚠️ .env.example 不存在，需要手动配置环境变量"
fi

# 生成应用密钥（如果需要）
if [ -f .env ] && ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 生成应用密钥..."
    php artisan key:generate --force
fi

# 优化应用
echo "⚡ 优化应用性能..."
# 注意：在 Vercel 环境中，缓存文件会在 /tmp 目录中，不是持久化的
php artisan config:cache 2>/dev/null || echo "⚠️ 配置缓存失败，将在运行时处理"
php artisan route:cache 2>/dev/null || echo "⚠️ 路由缓存失败，将在运行时处理"

# 检查关键文件
echo "🔍 检查关键文件..."
[ -f "public/index.php" ] && echo "✅ public/index.php 存在" || echo "❌ public/index.php 缺失"
[ -f "api/index.php" ] && echo "✅ api/index.php 存在" || echo "❌ api/index.php 缺失"
[ -f "vercel.json" ] && echo "✅ vercel.json 存在" || echo "❌ vercel.json 缺失"

# 输出构建信息
echo "📊 构建统计信息:"
echo "- PHP 版本: $(php --version | head -n1)"
echo "- Laravel 版本: $(php artisan --version 2>/dev/null || echo '未知')"
echo "- 总文件大小: $(du -sh . | cut -f1)"
echo "- Vendor 大小: $(du -sh vendor 2>/dev/null | cut -f1 || echo '未知')"

echo "✅ Vercel 构建完成！"
echo ""
echo "📋 接下来请确保："
echo "1. 在 Vercel 中配置所有必需的环境变量"
echo "2. 设置正确的 Supabase 数据库连接"
echo "3. 在部署后运行数据库迁移"

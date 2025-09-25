#!/bin/bash

# 独角数卡 Vercel + Supabase 部署脚本

echo "🚀 开始准备 Vercel + Supabase 部署..."

# 检查必要的工具
command -v composer >/dev/null 2>&1 || { echo "❌ 需要安装 Composer"; exit 1; }
command -v php >/dev/null 2>&1 || { echo "❌ 需要安装 PHP"; exit 1; }

echo "📦 更新 Composer 依赖..."
composer install --no-dev --optimize-autoloader

echo "🔑 生成应用密钥..."
php artisan key:generate

echo "🧹 清理缓存..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "📁 设置权限..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "✅ 准备完成！"
echo ""
echo "📋 接下来的步骤："
echo "1. 设置 Supabase 数据库（参考 supabase-setup.md）"
echo "2. 在 Vercel 中创建项目并连接此仓库"
echo "3. 配置环境变量（参考 vercel-supabase-env.txt）"
echo "4. 部署项目"
echo "5. 运行数据库迁移：php artisan migrate --force"
echo ""
echo "📖 详细步骤请查看 VERCEL_SUPABASE_DEPLOY.md"

#!/bin/bash

# 独角数卡 Railway 部署脚本
# 在 Railway 控制台中运行此脚本来初始化应用

echo "🚀 开始初始化独角数卡..."

# 检查环境变量
echo "📋 检查环境变量..."
if [ -z "$DB_HOST" ]; then
    echo "❌ 数据库环境变量未设置，请先配置数据库服务"
    exit 1
fi

if [ -z "$REDIS_HOST" ]; then
    echo "❌ Redis 环境变量未设置，请先配置 Redis 服务"
    exit 1
fi

# 清理缓存
echo "🧹 清理缓存..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 生成应用密钥（如果还没有的话）
echo "🔑 检查应用密钥..."
if [ -z "$APP_KEY" ]; then
    echo "生成新的应用密钥..."
    php artisan key:generate --force
else
    echo "应用密钥已存在"
fi

# 设置存储目录权限
echo "📁 设置存储目录权限..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# 创建存储链接
echo "🔗 创建存储链接..."
php artisan storage:link

# 运行数据库迁移
echo "🗄️ 运行数据库迁移..."
php artisan migrate --force

# 检查是否需要运行 seeder
echo "🌱 检查是否需要初始化数据..."
# 这里可以根据需要添加 seeder

# 优化应用
echo "⚡ 优化应用性能..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ 独角数卡初始化完成！"
echo "🎉 你现在可以访问你的应用了"
echo "📝 默认后台账户: admin / admin"
echo "⚠️  请立即修改默认密码！"

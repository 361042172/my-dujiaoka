#!/bin/bash

# 独角数卡 Vercel 一键部署脚本
# 运行此脚本以快速准备 Vercel 部署

echo "🚀 独角数卡 Vercel + Supabase 一键部署"
echo "========================================="

# 检查必要的工具
echo "🔍 检查环境..."
command -v git >/dev/null 2>&1 || { echo "❌ 需要安装 Git"; exit 1; }
command -v php >/dev/null 2>&1 || { echo "❌ 需要安装 PHP"; exit 1; }
command -v composer >/dev/null 2>&1 || { echo "❌ 需要安装 Composer"; exit 1; }

echo "✅ 环境检查通过"

# 生成应用密钥
echo "🔑 生成应用密钥..."
if [ ! -f .env ]; then
    echo "创建 .env 文件..."
    cp .env.example .env 2>/dev/null || touch .env
fi

# 生成新的应用密钥
php artisan key:generate --force 2>/dev/null || echo "⚠️ 无法生成密钥，请手动运行 php artisan key:generate"

# 获取生成的密钥
APP_KEY=$(grep "APP_KEY=" .env 2>/dev/null | cut -d'=' -f2- || echo "base64:需要手动生成")
echo "📋 应用密钥: $APP_KEY"

# 测试构建
echo "🧪 测试本地构建..."
./build.sh

echo ""
echo "✅ 准备完成！"
echo ""
echo "📋 接下来的步骤："
echo ""
echo "1️⃣ 设置 Supabase 数据库："
echo "   - 访问 https://supabase.com/"
echo "   - 创建新项目"
echo "   - 记录数据库连接信息"
echo ""
echo "2️⃣ 在 Vercel 中部署："
echo "   - 访问 https://vercel.com/"
echo "   - 连接 GitHub 仓库: 361042172/my-dujiaoka"
echo "   - 设置构建命令: bash build.sh"
echo ""
echo "3️⃣ 配置环境变量（关键！）："
echo "   APP_KEY=$APP_KEY"
echo "   DB_HOST=db.你的项目.supabase.co"
echo "   DB_PASSWORD=你的Supabase密码"
echo "   APP_URL=https://你的项目.vercel.app"
echo ""
echo "4️⃣ 部署后初始化："
echo "   运行: php migrate-supabase.php"
echo ""
echo "📖 详细步骤请查看: VERCEL_SUPABASE_DEPLOY.md"
echo ""
echo "🎯 推荐的部署顺序："
echo "   Supabase → Vercel → 环境变量 → 数据库迁移"

# ✅ Vercel + Supabase 部署检查清单

## 📋 部署前准备

### ✅ 账户准备
- [ ] Supabase 账户已注册
- [ ] Vercel 账户已注册并连接 GitHub
- [ ] GitHub 仓库代码已推送

### ✅ Supabase 设置
- [ ] 创建 Supabase 项目
- [ ] 记录数据库连接信息：
  - [ ] 项目 URL: `https://xxxxxx.supabase.co`
  - [ ] Database Host: `db.xxxxxx.supabase.co`
  - [ ] Database Password: `你的密码`
  - [ ] Anon Key: `你的匿名密钥`

## 🚀 Vercel 部署步骤

### 1️⃣ 创建 Vercel 项目
- [ ] 访问 [vercel.com](https://vercel.com/)
- [ ] 点击 "New Project"
- [ ] 选择仓库: `361042172/my-dujiaoka`
- [ ] Framework Preset: `Other`

### 2️⃣ 配置构建设置
- [ ] Build Command: `bash build.sh`
- [ ] Output Directory: (留空)
- [ ] Install Command: (保持默认)
- [ ] Node.js Version: `18.x`

### 3️⃣ 添加环境变量 ⭐ **最重要**
复制以下环境变量到 Vercel Settings → Environment Variables：

```bash
# 基础配置
APP_NAME=独角数卡
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-project-name.vercel.app

# 应用密钥（运行 php artisan key:generate 获取）
APP_KEY=base64:your-generated-key-here

# Supabase 数据库
DB_CONNECTION=pgsql
DB_HOST=db.xxxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password

# Vercel 优化
VERCEL=1
CACHE_DRIVER=array
SESSION_DRIVER=cookie
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
FILESYSTEM_DISK=local
VIEW_COMPILED_PATH=/tmp/views
STORAGE_PATH=/tmp/storage

# 独角数卡
DUJIAO_ADMIN_LANGUAGE=zh_CN
```

### 4️⃣ 部署项目
- [ ] 点击 "Deploy"
- [ ] 等待构建完成（5-10分钟）
- [ ] 检查构建日志无错误

### 5️⃣ 更新 APP_URL
- [ ] 复制 Vercel 提供的域名
- [ ] 在环境变量中更新 `APP_URL`
- [ ] 重新部署

## 🔧 部署后初始化

### 6️⃣ 数据库迁移
运行以下命令初始化数据库：
```bash
# 方法1：使用专用脚本
php migrate-supabase.php

# 方法2：手动运行
php artisan migrate --force
```

### 7️⃣ 验证部署
- [ ] 访问前台页面
- [ ] 访问后台管理: `your-domain/admin`
- [ ] 默认账户登录: `admin / admin`
- [ ] 立即修改默认密码

## 🐛 常见问题排查

### 构建失败
- 检查 `build.sh` 脚本是否有执行权限
- 查看构建日志中的具体错误信息
- 确认所有必需的文件都已推送到 GitHub

### 数据库连接失败
- 验证 Supabase 连接信息是否正确
- 检查数据库密码是否包含特殊字符（需要转义）
- 确认 PostgreSQL 端口 5432 是否正确

### 应用无法访问
- 检查 `APP_KEY` 是否已设置
- 确认 `APP_URL` 与实际域名匹配
- 查看 Vercel 函数日志

### 静态资源加载失败
- 检查 `vercel.json` 中的路由配置
- 确认静态文件路径正确
- 验证缓存配置

## 📊 性能优化建议

### 免费计划限制
- 执行时间: 10秒
- 内存: 1024MB
- 带宽: 100GB/月

### 优化建议
- 启用静态资源缓存
- 优化图片大小
- 使用 CDN 分发资源
- 考虑升级到付费计划

## 🆘 需要帮助？

### 文档资源
- [Vercel 文档](https://vercel.com/docs)
- [Supabase 文档](https://supabase.com/docs)
- [Laravel 文档](https://laravel.com/docs)

### 社区支持
- [Vercel Discord](https://discord.gg/vercel)
- [Supabase Discord](https://discord.supabase.com/)
- [Laravel 社区](https://laravel.com/community)

## ⚠️ 重要提醒

1. **安全性**: 立即修改默认密码
2. **备份**: 定期备份 Supabase 数据库
3. **监控**: 关注 Vercel 使用量
4. **更新**: 定期更新依赖包

---

## 🎉 部署成功后

恭喜！你的独角数卡已成功部署到 Vercel + Supabase！

**下一步操作:**
1. 配置支付方式
2. 设置邮件通知
3. 添加商品和分类
4. 测试购买流程
5. 配置域名（可选）

**记住**: 这是一个实验性部署方案，生产环境建议使用传统的 VPS 或专门的 PHP 托管服务以获得更好的兼容性和性能。

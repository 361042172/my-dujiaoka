# 🚀 独角数卡 Vercel + Supabase 部署指南

## ⚠️ 重要说明

这是一个**实验性方案**，将 PHP Laravel 项目部署到 Vercel 有一定的局限性：

**限制：**
- 无法使用队列处理（异步任务）
- 无法使用定时任务
- 文件系统是只读的
- 冷启动时间较长
- 部分 Laravel 功能可能不完全兼容

**建议的混合架构：**
- **前端**：Vercel + Next.js（商品展示、购买流程）
- **后端 API**：Railway/Render + Laravel（管理后台、复杂业务逻辑）
- **数据库**：Supabase PostgreSQL

---

## 📋 前置准备

### 1. 账户准备
- [ ] [Vercel 账户](https://vercel.com/)
- [ ] [Supabase 账户](https://supabase.com/)
- [ ] GitHub 仓库

### 2. 技术要求
- PHP 8.0+
- Composer
- Git

---

## 🗄️ 第一步：设置 Supabase 数据库

### 1.1 创建 Supabase 项目
1. 访问 [supabase.com](https://supabase.com/)
2. 点击 "New project"
3. 填写项目信息：
   - Name: `dujiaoka-db`
   - Password: 生成强密码并保存
   - Region: 选择合适的区域
4. 等待项目创建完成（约2-3分钟）

### 1.2 获取连接信息
在 Project Settings → Database 中找到：
```
Host: db.xxx.supabase.co
Database: postgres
Port: 5432
User: postgres
Password: [你设置的密码]
```

### 1.3 设置数据库表
1. 进入 Supabase 项目
2. 在 SQL Editor 中运行独角数卡的建表语句
3. 或者通过 Laravel 迁移创建表结构

---

## 🛠️ 第二步：准备项目文件

### 2.1 安装 PostgreSQL 驱动
```bash
composer require ext-pgsql
composer require supabase/supabase-php
```

### 2.2 更新 Composer 依赖
将 `composer.json` 中的 Laravel 版本更新为兼容 PHP 8.0+：
```json
{
  "require": {
    "php": "^8.0",
    "laravel/framework": "^8.0|^9.0|^10.0"
  }
}
```

### 2.3 项目文件检查
确保项目包含：
- ✅ `vercel.json` - Vercel 配置
- ✅ `api/index.php` - Vercel PHP 入口
- ✅ `vercel-supabase-env.txt` - 环境变量模板

---

## 🚀 第三步：部署到 Vercel

### 3.1 连接 GitHub
1. 访问 [vercel.com](https://vercel.com/)
2. 使用 GitHub 登录
3. 点击 "New Project"
4. 选择你的独角数卡仓库 (`361042172/my-dujiaoka`)

### 3.2 配置项目设置
**重要：** 在 Vercel 项目设置中配置以下选项：

1. **Framework Preset**: `Other`
2. **Root Directory**: 保持为根目录 (`./`)
3. **Build Command**: `bash build.sh`
4. **Output Directory**: 保持默认 (留空)
5. **Install Command**: 保持默认
6. **Node.js Version**: `18.x`

### 3.3 添加环境变量 ⭐ **关键步骤**
在 Vercel 项目 Settings → Environment Variables 中添加以下变量：

**📋 复制这些变量到 Vercel：**
```bash
# 基础配置
APP_NAME=独角数卡
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-project-name.vercel.app

# 应用密钥（先用占位符，稍后生成）
APP_KEY=base64:placeholder

# Supabase 数据库（替换为你的实际值）
DB_CONNECTION=pgsql
DB_HOST=db.xxxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password

# Vercel 优化配置
VERCEL=1
CACHE_DRIVER=array
SESSION_DRIVER=cookie
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
FILESYSTEM_DISK=local

# 路径配置
VIEW_COMPILED_PATH=/tmp/views
STORAGE_PATH=/tmp/storage

# 独角数卡配置
DUJIAO_ADMIN_LANGUAGE=zh_CN
```

### 3.4 部署项目
1. **点击 "Deploy"**
2. **等待构建完成**（首次构建可能需要 5-10 分钟）
3. **查看构建日志**：
   - 成功：显示 "✅ Vercel 构建完成！"
   - 失败：查看错误信息并调试

### 3.5 获取部署 URL
部署成功后，Vercel 会提供一个 URL，格式如：
```
https://my-dujiaoka-xxx.vercel.app
```

**⚠️ 重要：** 将这个 URL 更新到环境变量 `APP_URL` 中

---

## 🔧 第四步：初始化应用

### 4.1 生成应用密钥
在 Vercel 的 Functions 标签页或本地运行：
```bash
php artisan key:generate
```
将生成的 APP_KEY 添加到 Vercel 环境变量中

### 4.2 运行数据库迁移
```bash
php artisan migrate --force
```

### 4.3 清理缓存
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 🎯 第五步：访问和配置

### 5.1 访问应用
- 前台：`https://your-project.vercel.app`
- 后台：`https://your-project.vercel.app/admin`

### 5.2 默认登录
- 用户名：`admin`
- 密码：`admin`
- **⚠️ 立即修改默认密码！**

---

## ⚠️ 已知限制和解决方案

### 1. 队列处理
**问题**：Vercel 无法运行持久化队列
**解决方案**：
- 使用 `QUEUE_CONNECTION=sync` 同步处理
- 或者将队列任务移到其他服务

### 2. 文件上传
**问题**：Vercel 文件系统只读
**解决方案**：
- 配置 Supabase Storage
- 或使用 AWS S3、阿里云 OSS

### 3. 定时任务
**问题**：无法运行 Laravel 定时任务
**解决方案**：
- 使用 Vercel Cron Jobs
- 或外部定时任务服务

### 4. 性能问题
**问题**：冷启动时间长
**解决方案**：
- 考虑混合架构
- 使用 Vercel Pro 计划

---

## 🔄 推荐的混合架构

对于生产环境，建议使用混合架构：

### 前端（Vercel）
创建 Next.js 项目处理：
- 商品展示页面
- 购买流程
- 用户界面
- SEO 优化

### 后端 API（Railway/Render）
Laravel 项目处理：
- 管理后台
- 复杂业务逻辑
- 队列处理
- 定时任务

### 数据库（Supabase）
- 共享 PostgreSQL 数据库
- 实时功能
- 认证服务

---

## 🆘 故障排除

### 常见问题

1. **部署失败**
   - 检查 PHP 版本兼容性
   - 查看构建日志
   - 确认 composer.json 配置

2. **数据库连接失败**
   - 验证 Supabase 连接信息
   - 检查防火墙设置
   - 确认环境变量配置

3. **500 错误**
   - 检查 APP_KEY 是否设置
   - 查看 Vercel 函数日志
   - 确认权限配置

4. **静态资源问题**
   - 检查 public 目录配置
   - 确认 asset() 函数路径

---

## 💰 成本估算

### Vercel
- Hobby: 免费（100GB 带宽）
- Pro: $20/月（1TB 带宽）

### Supabase
- 免费计划：500MB 数据库 + 1GB 存储
- Pro: $25/月（8GB 数据库 + 100GB 存储）

### 总计
- 免费方案：$0/月（有限制）
- 基础方案：$20-45/月

---

## ✅ 部署检查清单

- [ ] Supabase 项目创建并配置
- [ ] 数据库连接信息正确
- [ ] Vercel 项目创建并连接 GitHub
- [ ] 环境变量配置完成
- [ ] 应用成功部署
- [ ] 数据库迁移完成
- [ ] 应用可以正常访问
- [ ] 后台管理正常
- [ ] 默认密码已修改

**注意**：这是一个实验性方案，生产环境建议使用混合架构以获得更好的性能和稳定性。

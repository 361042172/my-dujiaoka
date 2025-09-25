# 🗄️ Supabase 数据库设置指南

## 1. 创建 Supabase 项目

1. 访问 [supabase.com](https://supabase.com/)
2. 点击 "Start your project"
3. 登录/注册账户
4. 点击 "New project"
5. 填写项目信息：
   - Name: `dujiaoka-db`
   - Database Password: 生成强密码并保存
   - Region: 选择离你最近的区域
6. 点击 "Create new project"

## 2. 获取连接信息

项目创建完成后，在 Settings → Database 中找到：

```
Host: db.xxx.supabase.co
Database name: postgres
Port: 5432
User: postgres
Password: [你设置的密码]
```

## 3. 连接字符串格式

```
postgresql://postgres:[password]@db.xxx.supabase.co:5432/postgres
```

## 4. Laravel 数据库配置

在你的 Laravel 项目中，需要安装 PostgreSQL 驱动：

```bash
composer require ext-pgsql
```

然后在 `.env` 文件中配置：

```env
DB_CONNECTION=pgsql
DB_HOST=db.xxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

## 5. 迁移数据库

运行 Laravel 迁移来创建表结构：

```bash
php artisan migrate
```

## 6. Supabase 特性

Supabase 提供的额外功能：
- 实时订阅
- 行级安全（RLS）
- 自动生成的 REST API
- GraphQL API
- 认证服务
- 存储服务

## 7. 免费额度

Supabase 免费计划包括：
- 500MB 数据库存储
- 1GB 文件存储
- 50MB 文件上传限制
- 50,000 月活用户

# 🚀 独角数卡 Railway 部署完整指南

## 📋 准备工作

### 1. 账户准备
- [ ] 注册 [Railway](https://railway.app/) 账户
- [ ] 连接你的 GitHub 账户到 Railway
- [ ] 确保你的独角数卡项目已上传到 GitHub

### 2. 项目准备
✅ 已为你创建以下配置文件：
- `railway.json` - Railway 部署配置
- `nixpacks.toml` - 构建配置
- `Procfile` - 进程配置
- `railway-env-template.txt` - 环境变量模板

---

## 🎯 第一步：在 Railway 创建项目

### 1.1 登录 Railway
访问 [railway.app](https://railway.app/) 并登录

### 1.2 创建新项目
1. 点击 **"New Project"**
2. 选择 **"Deploy from GitHub repo"**
3. 授权 Railway 访问你的 GitHub（如果还没有的话）
4. 选择你的独角数卡仓库

### 1.3 初始部署
Railway 会自动开始首次部署，但这次部署会失败，这是正常的，因为我们还没有配置数据库。

---

## 🗄️ 第二步：添加数据库服务

### 2.1 添加 MySQL 数据库
1. 在你的 Railway 项目中，点击 **"New Service"**
2. 选择 **"Database"** → **"MySQL"**
3. Railway 会自动创建 MySQL 实例并生成连接信息

### 2.2 添加 Redis 缓存
1. 再次点击 **"New Service"**
2. 选择 **"Database"** → **"Redis"**
3. Railway 会自动创建 Redis 实例并生成连接信息

### 2.3 查看生成的环境变量
在每个数据库服务的 **"Variables"** 标签中，你可以看到自动生成的连接信息：

**MySQL 变量：**
- `MYSQLHOST`
- `MYSQLPORT`
- `MYSQLUSER`
- `MYSQLPASSWORD`
- `MYSQLDATABASE`

**Redis 变量：**
- `REDISHOST`
- `REDISPORT`
- `REDISPASSWORD`

---

## ⚙️ 第三步：配置环境变量

### 3.1 在 Web 服务中配置环境变量
1. 点击你的 Web 应用服务
2. 进入 **"Variables"** 标签
3. 添加以下环境变量（参考 `railway-env-template.txt` 文件）：

```bash
# 基础配置
APP_NAME=独角数卡
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-project-name.railway.app

# 数据库配置
DB_CONNECTION=mysql
DB_HOST=${MYSQLHOST}
DB_PORT=${MYSQLPORT}
DB_DATABASE=${MYSQLDATABASE}
DB_USERNAME=${MYSQLUSER}
DB_PASSWORD=${MYSQLPASSWORD}

# Redis 配置
REDIS_HOST=${REDISHOST}
REDIS_PASSWORD=${REDISPASSWORD}
REDIS_PORT=${REDISPORT}

# 缓存和会话
CACHE_DRIVER=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120
QUEUE_CONNECTION=redis

# 日志
LOG_CHANNEL=stack
LOG_LEVEL=error

# 独角数卡配置
DUJIAO_ADMIN_LANGUAGE=zh_CN
```

### 3.2 邮件配置（可选但推荐）
如果你有邮件服务，添加以下配置：

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME=独角数卡
```

---

## 🚀 第四步：部署和初始化

### 4.1 触发重新部署
配置好环境变量后：
1. 进入 **"Deployments"** 标签
2. 点击 **"Deploy Latest"** 重新部署

### 4.2 监控部署日志
在 **"Deploy Logs"** 中查看部署过程，确保没有错误。

### 4.3 生成应用密钥
部署成功后，在 Railway 控制台的终端中运行：
```bash
php artisan key:generate
```

### 4.4 运行数据库迁移
```bash
php artisan migrate --force
```

### 4.5 创建存储链接
```bash
php artisan storage:link
```

---

## 🎯 第五步：配置域名和访问

### 5.1 获取应用 URL
1. 在 **"Settings"** 标签中找到自动生成的域名
2. 格式通常是：`https://your-project-name.railway.app`

### 5.2 更新 APP_URL
将正确的 URL 添加到环境变量中：
```bash
APP_URL=https://your-actual-domain.railway.app
```

### 5.3 自定义域名（可选）
如果你有自己的域名：
1. 在 **"Settings"** → **"Domains"** 中添加自定义域名
2. 按照说明配置 DNS

---

## 🔧 第六步：初始化独角数卡

### 6.1 访问应用
使用浏览器访问你的应用 URL

### 6.2 后台管理登录
访问：`https://your-domain.railway.app/admin`

**默认账户：**
- 用户名：`admin`
- 密码：`admin`

### 6.3 首次设置
1. **立即修改默认密码**
2. 在系统设置中配置基本信息
3. 配置支付方式
4. 设置邮件模板

---

## ⚠️ 重要注意事项

### 文件存储
- Railway 的文件系统是临时的，每次部署都会重置
- 建议配置外部存储（如 AWS S3）用于图片和文件上传

### 队列处理
- 项目已配置 worker 进程来处理队列任务
- 在 Railway 控制台中可以看到 web 和 worker 两个进程

### 监控和日志
- 在 Railway 控制台的 **"Logs"** 中查看应用日志
- 在 **"Metrics"** 中监控应用性能

### 成本优化
- Railway 提供免费额度，超出后按使用量计费
- 监控你的资源使用情况

---

## 🆘 故障排除

### 常见问题

1. **部署失败**
   - 检查环境变量是否正确配置
   - 查看部署日志中的错误信息

2. **数据库连接失败**
   - 确认 MySQL 服务正在运行
   - 检查数据库环境变量是否正确

3. **页面显示错误**
   - 检查 APP_KEY 是否已生成
   - 确认 APP_URL 设置正确

4. **静态资源加载失败**
   - 运行 `npm run production` 重新构建前端资源
   - 检查 public 目录的权限

### 获取帮助
- Railway 文档：https://docs.railway.app/
- 独角数卡文档：https://github.com/assimon/dujiaoka
- Railway 社区：https://discord.gg/railway

---

## ✅ 部署完成检查清单

- [ ] Railway 项目创建成功
- [ ] MySQL 和 Redis 服务已添加
- [ ] 环境变量配置完成
- [ ] 应用部署成功
- [ ] 数据库迁移完成
- [ ] 应用可以正常访问
- [ ] 后台管理可以登录
- [ ] 默认密码已修改
- [ ] 基本设置已配置

恭喜！你的独角数卡应用已成功部署到 Railway！🎉

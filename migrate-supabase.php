<?php

/**
 * Supabase 数据库迁移脚本
 * 用于在 Vercel 环境中初始化数据库
 */

require __DIR__ . '/vendor/autoload.php';

// 加载 Vercel 环境配置
if (file_exists(__DIR__ . '/bootstrap/vercel.php')) {
    require __DIR__ . '/bootstrap/vercel.php';
}

echo "🚀 开始 Supabase 数据库迁移...\n";

try {
    // 检查环境变量
    $requiredEnvs = ['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
    foreach ($requiredEnvs as $env) {
        if (!getenv($env) && !isset($_ENV[$env])) {
            throw new Exception("环境变量 {$env} 未设置");
        }
    }

    // 设置 Laravel 应用
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "✅ Laravel 应用已启动\n";

    // 检查数据库连接
    echo "🔍 检查数据库连接...\n";
    $pdo = new PDO(
        "pgsql:host=" . getenv('DB_HOST') . ";port=" . (getenv('DB_PORT') ?: 5432) . ";dbname=" . getenv('DB_DATABASE'),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD'),
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✅ 数据库连接成功\n";

    // 运行迁移
    echo "📊 运行数据库迁移...\n";
    $output = null;
    $returnCode = null;
    exec('php artisan migrate --force 2>&1', $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "✅ 数据库迁移成功\n";
        foreach ($output as $line) {
            echo "   {$line}\n";
        }
    } else {
        echo "❌ 数据库迁移失败\n";
        foreach ($output as $line) {
            echo "   {$line}\n";
        }
        exit(1);
    }

    // 检查数据库表
    echo "🔍 检查数据库表...\n";
    $tables = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname = 'public'")->fetchAll(PDO::FETCH_COLUMN);
    echo "✅ 找到 " . count($tables) . " 个表:\n";
    foreach ($tables as $table) {
        echo "   - {$table}\n";
    }

    // 创建默认管理员（如果需要）
    echo "👤 检查管理员账户...\n";
    $adminExists = $pdo->query("SELECT COUNT(*) FROM admin_users WHERE username = 'admin'")->fetchColumn();
    
    if ($adminExists == 0) {
        echo "📝 创建默认管理员账户...\n";
        // 这里需要根据实际的 admin_users 表结构来调整
        $hashedPassword = password_hash('admin', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password, name, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->execute(['admin', $hashedPassword, 'Administrator']);
        echo "✅ 默认管理员账户已创建 (用户名: admin, 密码: admin)\n";
        echo "⚠️ 请立即登录后修改默认密码！\n";
    } else {
        echo "✅ 管理员账户已存在\n";
    }

    echo "\n🎉 Supabase 数据库初始化完成！\n";
    echo "📝 下一步:\n";
    echo "   1. 访问你的应用\n";
    echo "   2. 登录后台管理 (/admin)\n";
    echo "   3. 修改默认密码\n";
    echo "   4. 配置系统设置\n";

} catch (Exception $e) {
    echo "❌ 错误: " . $e->getMessage() . "\n";
    echo "🔍 调试信息:\n";
    echo "   DB_HOST: " . (getenv('DB_HOST') ?: '未设置') . "\n";
    echo "   DB_DATABASE: " . (getenv('DB_DATABASE') ?: '未设置') . "\n";
    echo "   DB_USERNAME: " . (getenv('DB_USERNAME') ?: '未设置') . "\n";
    echo "   DB_PASSWORD: " . (getenv('DB_PASSWORD') ? '已设置' : '未设置') . "\n";
    exit(1);
}

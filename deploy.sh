#!/bin/bash

echo "🚀 Railway デプロイ開始..."

echo "📦 Composer インストール..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "� 環境設定ファイル作成..."
cat > .env << EOF
APP_NAME=AttendEase
APP_ENV=production
APP_KEY=base64:YWRlYmMxNGRlYzM5MzMyNjQyYjFlZWI5NzQ4MGI4Y2IK
APP_DEBUG=false
APP_URL=https://attendease-production-14cb.up.railway.app
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
LOG_CHANNEL=stack
LOG_LEVEL=error
DB_CONNECTION=mysql
DB_HOST=metro.proxy.rlwy.net
DB_PORT=29939
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=ixOxZEOACaqQzVjzlFbnaDDeyMqErqRg
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@attendease.app
MAIL_FROM_NAME=AttendEase
EOF

echo "�🔑 アプリケーションキー確認..."
php artisan config:clear

echo "🎨 アセットビルド..."
npm ci --silent
npm run build

echo "📊 データベースマイグレーション..."
php artisan migrate --force

echo "✅ デプロイ完了！"

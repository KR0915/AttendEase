#!/bin/bash

echo "🚀 Railway デプロイ開始..."

echo "📝 環境変数から .env ファイルを生成..."
cat > .env << EOF
APP_NAME=${APP_NAME:-AttendEase}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-true}
APP_URL=${APP_URL:-https://attendease-production.up.railway.app}

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
PHP_CLI_SERVER_WORKERS=4
BCRYPT_ROUNDS=12

LOG_CHANNEL=${LOG_CHANNEL:-stack}
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=${LOG_LEVEL:-debug}

DB_CONNECTION=${DB_CONNECTION:-mysql}
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-railway}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}

CACHE_STORE=${CACHE_STORE:-file}
CACHE_PREFIX=

SESSION_DRIVER=${SESSION_DRIVER:-file}
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

MAIL_MAILER=${MAIL_MAILER:-log}
MAIL_HOST=${MAIL_HOST:-127.0.0.1}
MAIL_PORT=${MAIL_PORT:-2525}
MAIL_USERNAME=${MAIL_USERNAME}
MAIL_PASSWORD=${MAIL_PASSWORD}
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="${MAIL_FROM_ADDRESS:-hello@example.com}"
MAIL_FROM_NAME="${APP_NAME:-AttendEase}"
EOF

echo "✓ .env ファイルを生成しました"
echo "🔍 生成された .env ファイルの内容（一部）:"
head -10 .env

echo "📦 Composer インストール..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "🔑 設定ファイル最適化..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "🏗️ 設定キャッシュ作成..."
php artisan config:cache

echo "🎨 アセットビルド..."
npm ci --silent
npm run build

echo "📊 データベースマイグレーション..."
php artisan migrate --force

echo "🔄 アプリケーション最適化..."
php artisan optimize

echo "✅ デプロイ完了！"

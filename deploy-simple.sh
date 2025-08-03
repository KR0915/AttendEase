#!/bin/bash

echo "🚀 Railway デプロイ開始..."

echo "📝 環境変数から .env ファイルを生成..."
cat > .env << 'EOF'
APP_NAME="${APP_NAME:-AttendEase}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG:-true}"
APP_URL="${APP_URL:-https://attendease-production-14cb.up.railway.app}"
APP_TIMEZONE="${APP_TIMEZONE:-UTC}"

APP_LOCALE="${APP_LOCALE:-en}"
APP_FALLBACK_LOCALE="${APP_FALLBACK_LOCALE:-en}"
APP_FAKER_LOCALE="${APP_FAKER_LOCALE:-en_US}"

APP_MAINTENANCE_DRIVER="${APP_MAINTENANCE_DRIVER:-file}"
PHP_CLI_SERVER_WORKERS="${PHP_CLI_SERVER_WORKERS:-4}"
BCRYPT_ROUNDS="${BCRYPT_ROUNDS:-12}"

LOG_CHANNEL="${LOG_CHANNEL:-stack}"
LOG_STACK="${LOG_STACK:-single}"
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL="${LOG_LEVEL:-debug}"

DB_CONNECTION="${DB_CONNECTION:-mysql}"
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-railway}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"

BROADCAST_CONNECTION="${BROADCAST_CONNECTION:-log}"
FILESYSTEM_DISK="${FILESYSTEM_DISK:-local}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-database}"

CACHE_STORE="${CACHE_STORE:-database}"
CACHE_PREFIX=

SESSION_DRIVER="${SESSION_DRIVER:-database}"
SESSION_LIFETIME="${SESSION_LIFETIME:-120}"
SESSION_ENCRYPT="${SESSION_ENCRYPT:-false}"
SESSION_PATH=/
SESSION_DOMAIN=null

MAIL_MAILER="${MAIL_MAILER:-log}"
MAIL_HOST="${MAIL_HOST:-127.0.0.1}"
MAIL_PORT="${MAIL_PORT:-2525}"
MAIL_USERNAME="${MAIL_USERNAME}"
MAIL_PASSWORD="${MAIL_PASSWORD}"
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="${MAIL_FROM_ADDRESS:-noreply@attendease.app}"
MAIL_FROM_NAME="${MAIL_FROM_NAME:-AttendEase}"
EOF

echo "✓ .env ファイルを生成しました"
echo "🔍 生成された .env ファイルの内容確認:"
echo "APP_NAME=$(grep '^APP_NAME=' .env)"
echo "APP_ENV=$(grep '^APP_ENV=' .env)"
echo "APP_DEBUG=$(grep '^APP_DEBUG=' .env)"
echo "DB_HOST=$(grep '^DB_HOST=' .env)"

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

echo "📊 データベース接続テスト..."
php artisan db:show || echo "⚠️ データベース接続情報を確認してください"

echo "📊 データベースマイグレーション..."
php artisan migrate:status || echo "⚠️ マイグレーション状態を確認できませんでした"
php artisan migrate --force -v
echo "📊 マイグレーション完了後の状態..."
php artisan migrate:status || echo "⚠️ マイグレーション後の状態確認に失敗"

echo "🔄 アプリケーション最適化..."
php artisan optimize

echo "✅ デプロイ完了！"

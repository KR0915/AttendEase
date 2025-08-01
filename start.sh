#!/bin/bash

# 作業ディレクトリをsrcに移動
cd src

# .envファイルを作成（環境変数から）
cat > .env << EOF
APP_NAME=AttendEase
APP_ENV=production
APP_DEBUG=false
APP_URL=https://attendease-production-14cb.up.railway.app
APP_KEY=$APP_KEY

DB_CONNECTION=mysql
DB_HOST=$DB_HOST
DB_PORT=$DB_PORT
DB_DATABASE=$DB_DATABASE
DB_USERNAME=$DB_USERNAME
DB_PASSWORD=$DB_PASSWORD

CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

LOG_CHANNEL=errorlog
EOF

# 権限設定
chmod 644 .env

# Composer依存関係インストール
composer install --optimize-autoloader --no-dev --no-interaction

# NPM依存関係インストールとビルド
npm install --production
npm run build

# Laravel設定
php artisan config:clear
php artisan route:clear
php artisan view:clear

# データベースマイグレーション
php artisan migrate --force

# アプリケーション開始
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}

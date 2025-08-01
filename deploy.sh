#!/bin/bash

echo "🚀 Railway デプロイ開始..."

# ディレクトリ移動
cd src

echo "📦 Composer インストール..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "🔑 アプリケーションキー生成..."
php artisan key:generate --force

echo "🎨 アセットビルド..."
npm ci --silent
npm run build

echo "📊 データベースマイグレーション..."
php artisan migrate --force

echo "🧹 キャッシュ最適化..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ デプロイ完了！"

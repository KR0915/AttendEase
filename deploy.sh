#!/bin/bash

echo "🚀 Railway デプロイ開始..."

# ディレクトリ移動
cd src

echo "📦 Composer インストール..."
composer install --no-dev --optimize-autoloader

echo "🔑 アプリケーションキー生成..."
php artisan key:generate --force

echo "📊 データベースマイグレーション..."
php artisan migrate --force

echo "🎨 アセットビルド..."
npm ci
npm run build

echo "🧹 キャッシュ最適化..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ デプロイ完了！"

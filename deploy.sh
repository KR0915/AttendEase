#!/bin/bash

echo "🚀 Railway デプロイ開始..."

echo "📦 Composer インストール..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "🔑 アプリケーションキー確認..."
# php artisan key:generate --force

echo "🎨 アセットビルド..."
npm ci --silent
npm run build

echo "📊 データベースマイグレーション..."
php artisan migrate --force

echo "✅ デプロイ完了！"

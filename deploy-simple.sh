#!/bin/bash

echo "🚀 Railway デプロイ開始..."

echo "� 環境設定ファイルのコピー..."
if [ -f ".env.production" ]; then
    cp .env.production .env
    echo "✓ .env.production を .env にコピーしました"
else
    echo "⚠️ .env.production が見つかりません"
fi

echo "�📦 Composer インストール..."
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

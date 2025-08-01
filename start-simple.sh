#!/bin/bash

# 作業ディレクトリを src に移動
cd src || exit 1

# .env ファイルが存在しない場合のみ作成
if [ ! -f .env ]; then
    echo "Creating .env file from environment variables..."
    
    # 環境変数から .env ファイルを作成
    printenv | grep -E "^(APP_|DB_|SESSION_|CACHE_|QUEUE_|FILESYSTEM_|MAIL_|LOG_)" > .env
    
    # 権限設定
    chmod 644 .env
    
    echo ".env file created successfully"
fi

# Laravel の設定をクリア
php artisan config:clear
php artisan route:clear
php artisan view:clear

# アプリケーションを起動
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}

#!/bin/bash

echo "🚀 Railway デプロイ開始..."

echo "📝 環境変数から .env ファイル生成..."
cat > .env << EOF
APP_NAME=${APP_NAME:-AttendEase}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-https://attendease-production-14cb.up.railway.app}
APP_FORCE_HTTPS=true
APP_TIMEZONE=${APP_TIMEZONE:-UTC}

APP_LOCALE=${APP_LOCALE:-en}
APP_FALLBACK_LOCALE=${APP_FALLBACK_LOCALE:-en}
APP_FAKER_LOCALE=${APP_FAKER_LOCALE:-en_US}

APP_MAINTENANCE_DRIVER=${APP_MAINTENANCE_DRIVER:-file}
PHP_CLI_SERVER_WORKERS=${PHP_CLI_SERVER_WORKERS:-4}
BCRYPT_ROUNDS=${BCRYPT_ROUNDS:-12}

LOG_CHANNEL=${LOG_CHANNEL:-stack}
LOG_STACK=${LOG_STACK:-single}
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=${LOG_LEVEL:-debug}

DB_CONNECTION=${DB_CONNECTION:-mysql}
DB_HOST=${MYSQLHOST:-${DB_HOST}}
DB_PORT=${MYSQLPORT:-3306}
DB_DATABASE=${MYSQLDATABASE:-railway}
DB_USERNAME=${MYSQLUSER:-root}
DB_PASSWORD=${MYSQLPASSWORD:-${DB_PASSWORD}}

BROADCAST_CONNECTION=${BROADCAST_CONNECTION:-log}
FILESYSTEM_DISK=${FILESYSTEM_DISK:-local}
QUEUE_CONNECTION=${QUEUE_CONNECTION:-database}

CACHE_STORE=${CACHE_STORE:-database}
CACHE_PREFIX=

SESSION_DRIVER=${SESSION_DRIVER:-database}
SESSION_LIFETIME=${SESSION_LIFETIME:-120}
SESSION_ENCRYPT=${SESSION_ENCRYPT:-false}
SESSION_PATH=/
SESSION_DOMAIN=null

MAIL_MAILER=${MAIL_MAILER:-log}
MAIL_HOST=${MAIL_HOST:-127.0.0.1}
MAIL_PORT=${MAIL_PORT:-2525}
MAIL_USERNAME=${MAIL_USERNAME}
MAIL_PASSWORD=${MAIL_PASSWORD}
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS:-noreply@attendease.app}
MAIL_FROM_NAME=${MAIL_FROM_NAME:-AttendEase}
EOF

echo "✓ .env ファイルを生成しました"
echo "� .env ファイルを src ディレクトリにコピー..."
cp .env src/.env
echo "✓ .env ファイルをコピーしました"
echo "�🔍 生成された .env ファイルの内容確認:"
echo "APP_NAME=$(grep '^APP_NAME=' .env)"
echo "APP_ENV=$(grep '^APP_ENV=' .env)"
echo "APP_DEBUG=$(grep '^APP_DEBUG=' .env)"
echo "DB_HOST=$(grep '^DB_HOST=' .env)"

echo "📦 Composer インストール..."
cd src && composer install --no-dev --optimize-autoloader --no-interaction
cd ..

echo "🔑 設定ファイル最適化..."
cd src && php artisan config:clear
cd src && php artisan route:clear
cd src && php artisan view:clear
cd src && php artisan cache:clear
cd ..

echo "🏗️ 設定キャッシュ作成..."
cd src && php artisan config:cache
cd ..

echo "🎨 アセットビルド..."
cd src && npm ci --silent
cd src && npm run build
cd ..

echo "📊 データベース接続テスト..."
echo "🔍 現在のディレクトリ: $(pwd)"
echo "🔍 .env ファイルの存在確認: $(ls -la .env 2>/dev/null || echo 'NOT FOUND')"
echo "🔍 データベース接続変数:"
echo "  DB_CONNECTION: $(grep '^DB_CONNECTION=' .env 2>/dev/null || echo 'NOT SET')"
echo "  DB_HOST: $(grep '^DB_HOST=' .env 2>/dev/null || echo 'NOT SET')"
echo "  DB_DATABASE: $(grep '^DB_DATABASE=' .env 2>/dev/null || echo 'NOT SET')"

cd src && php artisan db:show || echo "⚠️ データベース接続に失敗しました"
cd ..

echo "📊 マイグレーションファイル一覧:"
ls -la src/database/migrations/ || echo "⚠️ マイグレーションフォルダが見つかりません"

echo "📊 現在のマイグレーション状態:"
cd src && php artisan migrate:status || echo "⚠️ マイグレーション状態を確認できませんでした"
cd ..

echo "📊 データベースマイグレーション実行..."
cd src && php artisan migrate --force -v || echo "⚠️ マイグレーションに失敗しました"
cd ..

echo "🔧 Railway環境でのis_activeカラム手動追加..."
cd src && php artisan tinker --execute="
try {
    // eventsテーブルにis_activeカラムが存在するかチェック
    \$hasColumn = DB::select('SHOW COLUMNS FROM events LIKE \"is_active\"');
    
    if (empty(\$hasColumn)) {
        echo 'is_activeカラムが存在しません。追加します...' . PHP_EOL;
        
        // is_activeカラムを追加
        DB::statement('ALTER TABLE events ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER max_participants');
        
        echo 'is_activeカラムを追加しました！' . PHP_EOL;
    } else {
        echo 'is_activeカラムは既に存在します。' . PHP_EOL;
    }
    
    // 現在のテーブル構造を確認
    \$columns = DB::select('DESCRIBE events');
    echo 'eventsテーブルの構造:' . PHP_EOL;
    foreach (\$columns as \$column) {
        echo \"- {\$column->Field} ({\$column->Type})\" . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'エラー: ' . \$e->getMessage() . PHP_EOL;
}
" || echo "⚠️ is_activeカラム追加に失敗"
cd ..

echo "🌱 サンプルデータ投入..."
cd src && php artisan db:seed --class=EventSeeder --force || echo "⚠️ シーダー実行に失敗しました"
cd ..

echo "📊 マイグレーション完了後の状態:"
cd src && php artisan migrate:status || echo "⚠️ マイグレーション後の状態確認に失敗"
cd ..

echo "🔍 eventsテーブルの構造確認:"
cd src && php artisan tinker --execute="
try {
    \$columns = collect(DB::select('DESCRIBE events'))->pluck('Field');
    echo 'Events table columns: ' . \$columns->join(', ') . PHP_EOL;
    echo 'is_active column exists: ' . (\$columns->contains('is_active') ? 'YES' : 'NO') . PHP_EOL;
} catch (Exception \$e) {
    echo 'Error checking table structure: ' . \$e->getMessage() . PHP_EOL;
}
" || echo "⚠️ テーブル構造確認に失敗"
cd ..

echo "🔄 アプリケーション最適化..."
cd src && php artisan optimize
cd ..

echo "✅ デプロイ完了！"

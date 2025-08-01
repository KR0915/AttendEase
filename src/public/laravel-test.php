<?php
// 直接的なLaravelテスト
require_once '/app/vendor/autoload.php';

try {
    $app = require_once '/app/bootstrap/app.php';
    echo "✅ Laravel Bootstrap: OK<br>";
    
    // アプリケーションキーチェック
    if (env('APP_KEY')) {
        echo "✅ APP_KEY: Set<br>";
    } else {
        echo "❌ APP_KEY: Not Set<br>";
    }
    
    // データベース接続テスト（Laravel経由）
    $pdo = new PDO(
        "mysql:host=" . env('DB_HOST') . ";port=" . env('DB_PORT') . ";dbname=" . env('DB_DATABASE'),
        env('DB_USERNAME'),
        env('DB_PASSWORD')
    );
    echo "✅ Database (Laravel env): OK<br>";
    
} catch (Exception $e) {
    echo "❌ Laravel Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}
?>

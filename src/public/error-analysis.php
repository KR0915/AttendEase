<?php
// エラー分析用スクリプト

echo "<h1>Laravel Error Analysis</h1>";
echo "<style>body { font-family: Arial, sans-serif; margin: 20px; } .section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; } .success { color: green; } .error { color: red; } .warning { color: orange; }</style>";

// 1. 基本的なPHP情報
echo "<div class='section'>";
echo "<h2>PHP Information</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Memory Limit: " . ini_get('memory_limit') . "<br>";
echo "Max Execution Time: " . ini_get('max_execution_time') . "<br>";
echo "</div>";

// 2. ファイルパスの確認
echo "<div class='section'>";
echo "<h2>File Paths</h2>";
echo "Current Directory: " . getcwd() . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "<br>";
echo "</div>";

// 3. 重要なファイルの存在確認
echo "<div class='section'>";
echo "<h2>Critical Files Check</h2>";
$files_to_check = [
    '../bootstrap/app.php',
    '../.env',
    '../config/app.php',
    '../vendor/autoload.php',
    'index.php'
];

foreach ($files_to_check as $file) {
    $status = file_exists($file) ? "<span class='success'>✓ EXISTS</span>" : "<span class='error'>✗ MISSING</span>";
    $readable = file_exists($file) && is_readable($file) ? "<span class='success'>READABLE</span>" : "<span class='error'>NOT READABLE</span>";
    echo "$file: $status ($readable)<br>";
}
echo "</div>";

// 4. Laravelブートストラップの試行
echo "<div class='section'>";
echo "<h2>Laravel Bootstrap Test</h2>";
try {
    // 現在のディレクトリを変更
    chdir('..');
    echo "Changed directory to: " . getcwd() . "<br>";
    
    // Composerオートローダーの読み込み
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        echo "<span class='success'>✓ Composer autoloader loaded</span><br>";
    } else {
        echo "<span class='error'>✗ Composer autoloader not found</span><br>";
    }
    
    // Laravelアプリケーションの作成
    if (file_exists('bootstrap/app.php')) {
        $app = require_once 'bootstrap/app.php';
        echo "<span class='success'>✓ Laravel app.php loaded</span><br>";
        
        // カーネルの作成
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        echo "<span class='success'>✓ HTTP Kernel created</span><br>";
        
        echo "App Name: " . config('app.name', 'Not Set') . "<br>";
        echo "App Environment: " . config('app.env', 'Not Set') . "<br>";
        echo "App Debug: " . (config('app.debug', false) ? 'true' : 'false') . "<br>";
        
    } else {
        echo "<span class='error'>✗ Laravel bootstrap/app.php not found</span><br>";
    }
    
} catch (Exception $e) {
    echo "<span class='error'>✗ Bootstrap Error: " . $e->getMessage() . "</span><br>";
    echo "<span class='error'>Error File: " . $e->getFile() . " (Line: " . $e->getLine() . ")</span><br>";
    echo "<span class='error'>Stack Trace:</span><br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
} catch (Error $e) {
    echo "<span class='error'>✗ Fatal Error: " . $e->getMessage() . "</span><br>";
    echo "<span class='error'>Error File: " . $e->getFile() . " (Line: " . $e->getLine() . ")</span><br>";
}
echo "</div>";

// 5. 環境変数の確認
echo "<div class='section'>";
echo "<h2>Environment Variables</h2>";
$env_vars = [
    'APP_NAME', 'APP_ENV', 'APP_KEY', 'APP_DEBUG', 'APP_URL',
    'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME'
];

foreach ($env_vars as $var) {
    $value = getenv($var) ?: $_ENV[$var] ?? 'Not Set';
    if ($var === 'APP_KEY' && $value !== 'Not Set') {
        $value = substr($value, 0, 10) . '...'; // セキュリティのため一部のみ表示
    }
    echo "$var: $value<br>";
}
echo "</div>";

// 6. サーバー情報
echo "<div class='section'>";
echo "<h2>Server Information</h2>";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "<br>";
echo "HTTP Host: " . ($_SERVER['HTTP_HOST'] ?? 'Unknown') . "<br>";
echo "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Unknown') . "<br>";
echo "Request Method: " . ($_SERVER['REQUEST_METHOD'] ?? 'Unknown') . "<br>";
echo "</div>";

echo "<p><strong>Analysis completed at: " . date('Y-m-d H:i:s') . "</strong></p>";
?>

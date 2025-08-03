<?php
// デバッグ用の詳細な診断ファイル

echo "<h1>AttendEase Debug Information</h1>";
echo "<style>body { font-family: Arial, sans-serif; margin: 20px; } .section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; background: #f9f9f9; } .success { color: green; } .error { color: red; } .warning { color: orange; }</style>";

// 1. 基本的な環境情報
echo "<div class='section'>";
echo "<h2>Environment Information</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Directory: " . getcwd() . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "</div>";

// 2. ファイル存在確認
echo "<div class='section'>";
echo "<h2>File Existence Check</h2>";
$files = [
    '../.env' => 'Main .env file',
    '../.env.production' => 'Production .env file',
    '../bootstrap/app.php' => 'Laravel bootstrap',
    '../vendor/autoload.php' => 'Composer autoloader',
    '../config/app.php' => 'App configuration'
];

foreach ($files as $file => $description) {
    $exists = file_exists($file);
    $status = $exists ? "<span class='success'>✓ EXISTS</span>" : "<span class='error'>✗ MISSING</span>";
    $size = $exists ? " (" . filesize($file) . " bytes)" : "";
    echo "$description: $status$size<br>";
}
echo "</div>";

// 3. 環境変数の詳細確認
echo "<div class='section'>";
echo "<h2>Environment Variables</h2>";
$env_vars = [
    'APP_NAME', 'APP_ENV', 'APP_KEY', 'APP_DEBUG', 'APP_URL',
    'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE',
    'PORT', 'RAILWAY_ENVIRONMENT'
];

foreach ($env_vars as $var) {
    $value = $_ENV[$var] ?? getenv($var) ?? 'NOT SET';
    if ($var === 'APP_KEY' && $value !== 'NOT SET') {
        $value = substr($value, 0, 15) . '...'; // セキュリティのため一部表示
    }
    echo "$var = $value<br>";
}
echo "</div>";

// 4. Laravel ブートストラップ テスト
echo "<div class='section'>";
echo "<h2>Laravel Bootstrap Test</h2>";
try {
    // ディレクトリ移動
    $original_dir = getcwd();
    chdir('..');
    echo "Changed to directory: " . getcwd() . "<br>";
    
    // Composer autoloader
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        echo "<span class='success'>✓ Composer autoloader loaded</span><br>";
    } else {
        echo "<span class='error'>✗ Composer autoloader not found</span><br>";
        chdir($original_dir);
        return;
    }
    
    // Laravel bootstrap
    if (file_exists('bootstrap/app.php')) {
        echo "Loading Laravel application...<br>";
        $app = require_once 'bootstrap/app.php';
        echo "<span class='success'>✓ Laravel application loaded</span><br>";
        
        // 環境確認
        if (method_exists($app, 'environment')) {
            echo "App Environment: " . $app->environment() . "<br>";
        }
        
        // 設定確認
        if (function_exists('config')) {
            echo "Config APP_NAME: " . config('app.name', 'NOT SET') . "<br>";
            echo "Config APP_ENV: " . config('app.env', 'NOT SET') . "<br>";
            echo "Config APP_DEBUG: " . (config('app.debug', false) ? 'true' : 'false') . "<br>";
            echo "Config DB_CONNECTION: " . config('database.default', 'NOT SET') . "<br>";
        }
        
    } else {
        echo "<span class='error'>✗ Laravel bootstrap not found</span><br>";
    }
    
    chdir($original_dir);
    
} catch (Exception $e) {
    chdir($original_dir);
    echo "<span class='error'>✗ Bootstrap Error: " . $e->getMessage() . "</span><br>";
    echo "<span class='error'>File: " . $e->getFile() . " (Line: " . $e->getLine() . ")</span><br>";
    echo "<div style='white-space: pre-wrap; font-family: monospace; background: #f0f0f0; padding: 10px; margin: 10px 0;'>";
    echo htmlspecialchars($e->getTraceAsString());
    echo "</div>";
} catch (Error $e) {
    chdir($original_dir);
    echo "<span class='error'>✗ Fatal Error: " . $e->getMessage() . "</span><br>";
    echo "<span class='error'>File: " . $e->getFile() . " (Line: " . $e->getLine() . ")</span><br>";
}
echo "</div>";

// 5. サーバー情報
echo "<div class='section'>";
echo "<h2>Server Information</h2>";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "<br>";
echo "HTTP Host: " . ($_SERVER['HTTP_HOST'] ?? 'Unknown') . "<br>";
echo "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Unknown') . "<br>";
echo "Request Method: " . ($_SERVER['REQUEST_METHOD'] ?? 'Unknown') . "<br>";
echo "HTTPS: " . (isset($_SERVER['HTTPS']) ? 'Yes' : 'No') . "<br>";
echo "</div>";

echo "<p><strong>Debug analysis completed at: " . date('Y-m-d H:i:s T') . "</strong></p>";
?>

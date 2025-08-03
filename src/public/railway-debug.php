<?php
// Railway環境でのLaravel詳細デバッグ

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Railway Laravel Debug Information</h1>";
echo "<style>body { font-family: Arial, sans-serif; margin: 20px; } .section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; } .success { color: green; } .error { color: red; } .warning { color: orange; }</style>";

// 1. 基本環境情報
echo "<div class='section'>";
echo "<h2>1. Basic Environment</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Directory: " . getcwd() . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "<br>";
echo "</div>";

// 2. .envファイルの確認
echo "<div class='section'>";
echo "<h2>2. Environment File Check</h2>";
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    echo "<span class='success'>✓ .env file exists</span><br>";
    echo "File size: " . filesize($envPath) . " bytes<br>";
    echo "Last modified: " . date('Y-m-d H:i:s', filemtime($envPath)) . "<br>";
    
    // .envファイルの内容を安全に表示
    $envContent = file_get_contents($envPath);
    $lines = explode("\n", $envContent);
    echo "<h3>Environment variables (sanitized):</h3>";
    echo "<pre>";
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            echo htmlspecialchars($line) . "\n";
            continue;
        }
        
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            // 機密情報をマスク
            if (in_array($key, ['APP_KEY', 'DB_PASSWORD', 'MAIL_PASSWORD'])) {
                $value = str_repeat('*', min(strlen($value), 10));
            }
            echo htmlspecialchars($key . '=' . $value) . "\n";
        }
    }
    echo "</pre>";
} else {
    echo "<span class='error'>✗ .env file missing at: $envPath</span><br>";
}
echo "</div>";

// 3. Railway環境変数の確認
echo "<div class='section'>";
echo "<h2>3. Railway Environment Variables</h2>";
$railwayVars = [
    'RAILWAY_ENVIRONMENT', 'RAILWAY_PROJECT_ID', 'RAILWAY_SERVICE_ID',
    'APP_NAME', 'APP_ENV', 'APP_KEY', 'APP_DEBUG', 'APP_URL',
    'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'
];

foreach ($railwayVars as $var) {
    $value = getenv($var) ?: $_ENV[$var] ?? 'Not Set';
    if (in_array($var, ['APP_KEY', 'DB_PASSWORD']) && $value !== 'Not Set') {
        $value = substr($value, 0, 10) . '...';
    }
    $status = ($value !== 'Not Set') ? 'success' : 'warning';
    echo "<span class='$status'>$var: $value</span><br>";
}
echo "</div>";

// 4. Laravel Bootstrap 試行
echo "<div class='section'>";
echo "<h2>4. Laravel Bootstrap Test</h2>";
try {
    // カレントディレクトリを変更
    $originalDir = getcwd();
    chdir(__DIR__ . '/..');
    echo "Working directory: " . getcwd() . "<br>";
    
    // 重要ファイルの確認
    $requiredFiles = [
        'vendor/autoload.php',
        'bootstrap/app.php',
        'config/app.php'
    ];
    
    foreach ($requiredFiles as $file) {
        if (file_exists($file)) {
            echo "<span class='success'>✓ $file exists</span><br>";
        } else {
            echo "<span class='error'>✗ $file missing</span><br>";
        }
    }
    
    // Composer autoloader
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        echo "<span class='success'>✓ Composer autoloader loaded</span><br>";
        
        // Laravel Application
        if (file_exists('bootstrap/app.php')) {
            $app = require_once 'bootstrap/app.php';
            echo "<span class='success'>✓ Laravel app created</span><br>";
            
            // 設定値の確認
            try {
                echo "App Name: " . config('app.name', 'Not Set') . "<br>";
                echo "App Environment: " . config('app.env', 'Not Set') . "<br>";
                echo "App Debug: " . (config('app.debug', false) ? 'true' : 'false') . "<br>";
                echo "App URL: " . config('app.url', 'Not Set') . "<br>";
                echo "Database Connection: " . config('database.default', 'Not Set') . "<br>";
            } catch (Exception $e) {
                echo "<span class='error'>Config error: " . $e->getMessage() . "</span><br>";
            }
        }
    }
    
    chdir($originalDir);
    
} catch (Exception $e) {
    echo "<span class='error'>✗ Bootstrap Error: " . $e->getMessage() . "</span><br>";
    echo "<span class='error'>File: " . $e->getFile() . " (Line: " . $e->getLine() . ")</span><br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
} catch (Error $e) {
    echo "<span class='error'>✗ Fatal Error: " . $e->getMessage() . "</span><br>";
    echo "<span class='error'>File: " . $e->getFile() . " (Line: " . $e->getLine() . ")</span><br>";
}
echo "</div>";

echo "<p><strong>Debug completed at: " . date('Y-m-d H:i:s') . "</strong></p>";
?>

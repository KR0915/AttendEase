<?php
// APP_KEY確認用テストページ
echo "<h1>🔑 APP_KEY Debug</h1>";

echo "<h2>Environment Variables:</h2>";
echo "APP_KEY: " . ($_ENV['APP_KEY'] ?? 'NOT SET') . "<br>";
echo "Length: " . strlen($_ENV['APP_KEY'] ?? '') . "<br>";

echo "<h2>Server Environment:</h2>";
echo "APP_KEY: " . (getenv('APP_KEY') ?: 'NOT SET') . "<br>";
echo "Length: " . strlen(getenv('APP_KEY') ?: '') . "<br>";

echo "<h2>Config via Laravel:</h2>";
try {
    require_once __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $config = $app->make('config');
    
    $appKey = $config->get('app.key');
    echo "Config APP_KEY: " . ($appKey ?: 'NOT SET') . "<br>";
    echo "Length: " . strlen($appKey ?: '') . "<br>";
    
    echo "<h2>Comparison:</h2>";
    $envKey = getenv('APP_KEY');
    echo "Env Key: " . substr($envKey, 0, 20) . "...<br>";
    echo "Config Key: " . substr($appKey, 0, 20) . "...<br>";
    echo "Keys Match: " . ($envKey === $appKey ? '✅ YES' : '❌ NO') . "<br>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}
?>

<?php
// APP_KEY確認用テストページ
echo "<h1>🔑 APP_KEY Debug</h1>";

echo "<h2>Environment Variable:</h2>";
echo "APP_KEY: " . (env('APP_KEY') ? '***SET***' : '❌ NOT SET') . "<br>";
echo "Length: " . strlen(env('APP_KEY')) . "<br>";
echo "Starts with 'base64:': " . (str_starts_with(env('APP_KEY'), 'base64:') ? 'YES' : 'NO') . "<br>";

echo "<h2>Config Value:</h2>";
try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $config = $app->make('config');
    
    $appKey = $config->get('app.key');
    echo "Config APP_KEY: " . ($appKey ? '***SET***' : '❌ NOT SET') . "<br>";
    echo "Length: " . strlen($appKey) . "<br>";
    echo "Starts with 'base64:': " . (str_starts_with($appKey, 'base64:') ? 'YES' : 'NO') . "<br>";
    
    echo "<h2>Comparison:</h2>";
    echo "Keys Match: " . (env('APP_KEY') === $appKey ? '✅ YES' : '❌ NO') . "<br>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}
?>

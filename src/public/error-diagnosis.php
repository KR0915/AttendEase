<?php
// Laravel エラー診断
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🔍 Laravel Error Diagnosis</h1>";

try {
    echo "<h2>Step 1: Autoloader</h2>";
    require_once __DIR__ . '/../vendor/autoload.php';
    echo "✅ Autoloader loaded<br>";
    
    echo "<h2>Step 2: Bootstrap App</h2>";
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "✅ Bootstrap completed<br>";
    echo "App class: " . get_class($app) . "<br>";
    
    echo "<h2>Step 3: Configuration Test</h2>";
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    echo "✅ HTTP Kernel created<br>";
    
    echo "<h2>Step 4: Request Test</h2>";
    $request = \Illuminate\Http\Request::capture();
    echo "✅ Request captured<br>";
    
    echo "<h2>Step 5: Handle Request</h2>";
    $response = $kernel->handle($request);
    echo "✅ Request handled<br>";
    echo "Status: " . $response->getStatusCode() . "<br>";
    echo "Content Length: " . strlen($response->getContent()) . "<br>";
    
} catch (Exception $e) {
    echo "<h2>❌ Error Details</h2>";
    echo "Message: " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "<br>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?>

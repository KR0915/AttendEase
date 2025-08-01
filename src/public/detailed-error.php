<?php
// 詳細な500エラー分析
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🔍 Detailed 500 Error Analysis</h1>";

try {
    require_once __DIR__ . '/../vendor/autoload.php';
    
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    
    // リクエストを作成してテスト
    $request = \Illuminate\Http\Request::create('/', 'GET');
    
    echo "<h2>Request Details:</h2>";
    echo "URL: " . $request->getUri() . "<br>";
    echo "Method: " . $request->getMethod() . "<br>";
    echo "Headers: " . json_encode($request->headers->all()) . "<br>";
    
    echo "<h2>Processing Request...</h2>";
    
    $response = $kernel->handle($request);
    
    echo "<h2>Response Details:</h2>";
    echo "Status: " . $response->getStatusCode() . "<br>";
    echo "Headers: " . json_encode($response->headers->all()) . "<br>";
    echo "Content Type: " . $response->headers->get('Content-Type') . "<br>";
    echo "Content Length: " . strlen($response->getContent()) . "<br>";
    
    if ($response->getStatusCode() === 500) {
        echo "<h2>500 Error Content:</h2>";
        echo "<pre>" . htmlspecialchars(substr($response->getContent(), 0, 2000)) . "</pre>";
    }
    
} catch (Exception $e) {
    echo "<h2>❌ Exception Details</h2>";
    echo "Message: " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "<br>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
} catch (Error $e) {
    echo "<h2>❌ Fatal Error Details</h2>";
    echo "Message: " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "<br>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?>

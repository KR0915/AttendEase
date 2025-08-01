<?php
echo "🎉 AttendEase Test Page<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server Time: " . date('Y-m-d H:i:s') . "<br>";
echo "Environment: " . ($_ENV['APP_ENV'] ?? 'unknown') . "<br>";
echo "Database Host: " . ($_ENV['DB_HOST'] ?? 'not set') . "<br>";
echo "✅ PHP is working!<br>";

// データベース接続テスト
try {
    $host = $_ENV['DB_HOST'] ?? '';
    $dbname = $_ENV['DB_DATABASE'] ?? '';
    $username = $_ENV['DB_USERNAME'] ?? '';
    $password = $_ENV['DB_PASSWORD'] ?? '';
    $port = $_ENV['DB_PORT'] ?? '3306';
    
    if ($host && $dbname && $username) {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
        echo "✅ Database connection: OK<br>";
    } else {
        echo "⚠️ Database credentials not set<br>";
    }
} catch (Exception $e) {
    echo "❌ Database connection: " . $e->getMessage() . "<br>";
}
?>

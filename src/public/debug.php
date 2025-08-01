<?php
echo "<h1>🔧 AttendEase Debug Info</h1>";

echo "<h2>Environment Variables:</h2>";
$important_vars = [
    'APP_NAME', 'APP_ENV', 'APP_DEBUG', 'APP_URL', 'APP_KEY',
    'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME'
];

foreach ($important_vars as $var) {
    $value = $_ENV[$var] ?? getenv($var) ?? 'NOT SET';
    if ($var === 'DB_PASSWORD' || $var === 'APP_KEY') {
        $value = $value !== 'NOT SET' ? '***SET***' : 'NOT SET';
    }
    echo "$var: $value<br>";
}

echo "<h2>PHP Info:</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'unknown') . "<br>";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'unknown') . "<br>";

echo "<h2>Files Check:</h2>";
$files_to_check = [
    '/app/public/index.php',
    '/app/bootstrap/app.php',
    '/app/.env',
    '/app/composer.json'
];

foreach ($files_to_check as $file) {
    echo "$file: " . (file_exists($file) ? '✅ EXISTS' : '❌ NOT FOUND') . "<br>";
}

echo "<h2>Laravel Artisan Test:</h2>";
try {
    ob_start();
    $output = shell_exec('cd /app && php artisan --version 2>&1');
    echo "Artisan Version: " . htmlspecialchars($output ?? 'ERROR') . "<br>";
} catch (Exception $e) {
    echo "❌ Artisan Error: " . $e->getMessage() . "<br>";
}
?>

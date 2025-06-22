<?php

echo "<h3>Environment File Debug</h3>";

// Check if .env file exists
if (file_exists(__DIR__ . '/.env')) {
    echo "✅ .env file exists<br>";
    echo "File size: " . filesize(__DIR__ . '/.env') . " bytes<br>";
    echo "File permissions: " . substr(sprintf('%o', fileperms(__DIR__ . '/.env')), -4) . "<br>";
    
    // Show first few lines (without sensitive info)
    $envContent = file_get_contents(__DIR__ . '/.env');
    $lines = explode("\n", $envContent);
    echo "<h4>First few .env lines:</h4>";
    foreach (array_slice($lines, 0, 5) as $line) {
        if (strpos($line, 'APP_KEY') === false && strpos($line, 'PASSWORD') === false) {
            echo htmlspecialchars($line) . "<br>";
        } else {
            echo "APP_KEY=***hidden***<br>";
        }
    }
} else {
    echo "❌ .env file does NOT exist<br>";
}

// Test Laravel bootstrap with explicit .env loading
echo "<hr><h3>Laravel Bootstrap Test</h3>";
try {
    require_once __DIR__.'/vendor/autoload.php';
    
    // Try to manually load .env
    if (class_exists('Dotenv\Dotenv')) {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        echo "✅ Dotenv loaded manually<br>";
    }
    
    $app = require_once __DIR__.'/bootstrap/app.php';
    
    echo "APP_KEY from env(): " . (env('APP_KEY') ? '***present***' : '❌ MISSING') . "<br>";
    echo "APP_ENV: " . env('APP_ENV') . "<br>";
    echo "APP_DEBUG: " . (env('APP_DEBUG') ? 'true' : 'false') . "<br>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
} 
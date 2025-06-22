<?php

// Simple test to see if Laravel can bootstrap
try {
    require_once __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
    
    echo "✅ Laravel bootstrap successful!<br>";
    echo "PHP Version: " . phpversion() . "<br>";
    echo "Laravel Version: " . $app->version() . "<br>";
    
    // Test if .env is loaded
    if (env('APP_KEY')) {
        echo "✅ .env file loaded<br>";
        echo "APP_ENV: " . env('APP_ENV') . "<br>";
        echo "APP_URL: " . env('APP_URL') . "<br>";
    } else {
        echo "❌ .env file NOT loaded or APP_KEY missing<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Laravel bootstrap failed: " . $e->getMessage();
} 
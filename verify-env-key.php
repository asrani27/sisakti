<?php

require __DIR__ . '/vendor/autoload.php';

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Verifying ZAI_API_KEY from .env file...\n\n";

// Check if the key exists
$apiKey = env('ZAI_API_KEY');

if ($apiKey) {
    echo "✅ API Key Found!\n";
    echo "Full API Key: " . $apiKey . "\n";
    echo "Key Length: " . strlen($apiKey) . " characters\n";
    echo "First 20 chars: " . substr($apiKey, 0, 20) . "...\n";
    echo "Last 10 chars: ..." . substr($apiKey, -10) . "\n";
    
    // Check if key format is correct (should have a dot in the middle)
    if (strpos($apiKey, '.') !== false) {
        echo "✅ Key format looks correct (contains '.')\n";
        
        $parts = explode('.', $apiKey);
        echo "Key parts: " . count($parts) . " parts\n";
        foreach ($parts as $index => $part) {
            echo "  Part " . ($index + 1) . ": " . substr($part, 0, 10) . "... (length: " . strlen($part) . ")\n";
        }
    } else {
        echo "⚠️  Key format might be incorrect (no '.' found)\n";
    }
} else {
    echo "❌ API Key NOT FOUND!\n";
    echo "Please check that ZAI_API_KEY is set in .env file\n";
}

echo "\nEnvironment check completed.\n";
<?php

require __DIR__ . '/vendor/autoload.php';

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Z AI API Connection...\n\n";

$apiKey = env('ZAI_API_KEY');
$apiUrl = 'https://open.bigmodel.cn/api/paas/v4/chat/completions';

echo "API Key: " . substr($apiKey, 0, 20) . "...\n";
echo "API URL: $apiUrl\n\n";

// Simple test request
try {
    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
        'Content-Type' => 'application/json',
    ])->post($apiUrl, [
        'model' => 'glm-4-flash',
        'messages' => [
            [
                'role' => 'user',
                'content' => 'Hello, please respond with "Z AI is working!" in JSON format: {"status": "success", "message": "Z AI is working!"}'
            ]
        ],
        'temperature' => 0.3,
        'max_tokens' => 100,
    ]);

    echo "Response Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        $result = $response->json();
        echo "Response Body:\n";
        print_r($result);
        
        if (isset($result['choices'][0]['message']['content'])) {
            echo "\n\nZ AI API is working correctly! ✅\n";
            echo "AI Response: " . $result['choices'][0]['message']['content'] . "\n";
        }
    } else {
        echo "Request failed ❌\n";
        echo "Response Body: " . $response->body() . "\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
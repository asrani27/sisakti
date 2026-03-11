<?php

require __DIR__ . '/vendor/autoload.php';

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Z AI Model 4.6...\n\n";

$apiKey = env('ZAI_API_KEY');
$apiUrl = 'https://open.bigmodel.cn/api/paas/v4/chat/completions';

echo "API Key: " . substr($apiKey, 0, 20) . "...\n";
echo "Model: 4.6\n\n";

try {
    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
        'Content-Type' => 'application/json',
    ])->timeout(30)->post($apiUrl, [
        'model' => '4.6',
        'messages' => [
            [
                'role' => 'user',
                'content' => 'Hello! Please respond with "Z AI 4.6 is working!"'
            ]
        ],
        'temperature' => 0.3,
        'max_tokens' => 100,
    ]);

    $status = $response->status();
    echo "Response Status: $status\n";
    
    if ($response->successful()) {
        $result = $response->json();
        echo "✅ SUCCESS! Model 4.6 works!\n\n";
        echo "Full Response:\n";
        print_r($result);
        
        if (isset($result['choices'][0]['message']['content'])) {
            echo "\n\nAI Response: " . $result['choices'][0]['message']['content'] . "\n";
        }
    } else {
        echo "❌ Request failed\n";
        echo "Response Body: " . $response->body() . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\nTest completed.\n";
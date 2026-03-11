<?php

require __DIR__ . '/vendor/autoload.php';

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Different Z AI Models...\n\n";

$apiKey = env('ZAI_API_KEY');
$apiUrl = 'https://open.bigmodel.cn/api/paas/v4/chat/completions';

echo "API Key: " . substr($apiKey, 0, 20) . "...\n\n";

// List of models to test
$models = [
    'glm-4',
    'glm-4-flash',
    'glm-3-turbo',
    'chatglm3-6b',
    'glm-3',
    'glm-4-alltools',
    'glm-4v',
];

foreach ($models as $model) {
    echo "Testing model: $model\n";
    echo str_repeat('-', 50) . "\n";
    
    try {
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(10)->post($apiUrl, [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => 'Say "Hello" only'
                ]
            ],
            'temperature' => 0.3,
            'max_tokens' => 10,
        ]);

        $status = $response->status();
        echo "Status: $status\n";
        
        if ($response->successful()) {
            $result = $response->json();
            echo "✅ SUCCESS! Model '$model' works!\n";
            if (isset($result['choices'][0]['message']['content'])) {
                echo "Response: " . $result['choices'][0]['message']['content'] . "\n";
            }
            echo "\n";
            break; // Stop testing if we find a working model
        } else {
            $body = $response->body();
            echo "❌ Failed\n";
            echo "Error: $body\n";
            echo "\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Exception: " . $e->getMessage() . "\n\n";
    }
    
    // Small delay between requests
    usleep(500000);
}

echo "\nTest completed.\n";
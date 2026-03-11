<?php

require __DIR__ . '/vendor/autoload.php';

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing All Possible Z AI Models...\n\n";

$apiKey = env('ZAI_API_KEY');
$apiUrl = 'https://open.bigmodel.cn/api/paas/v4/chat/completions';

echo "API Key: " . substr($apiKey, 0, 20) . "...\n\n";

// List of all possible models to test
$models = [
    // GLM models
    'glm-4',
    'glm-4-flash',
    'glm-4-plus',
    'glm-4-air',
    'glm-4-long',
    'glm-3-turbo',
    'glm-3',
    'glm-3-lite',
    
    // ChatGLM models
    'chatglm3',
    'chatglm3-6b',
    'chatglm-turbo',
    
    // Other possible names
    'glm-4-0520',
    'glm-4-0206',
    'glm-3-130b',
    'glm-pro',
    'glm-max',
];

$workingModel = null;

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
                    'content' => 'Say "OK" only'
                ]
            ],
            'temperature' => 0.3,
            'max_output_tokens' => 10,
        ]);

        $status = $response->status();
        echo "Status: $status\n";
        
        if ($response->successful()) {
            $result = $response->json();
            echo "✅ SUCCESS! Model '$model' works!\n";
            if (isset($result['choices'][0]['message']['content'])) {
                echo "Response: " . $result['choices'][0]['message']['content'] . "\n";
            }
            $workingModel = $model;
            echo "\n";
            break; // Stop testing if we find a working model
        } else {
            $body = $response->body();
            // Only show error if it's not "model not found" to reduce noise
            if (strpos($body, '模型不存在') === false) {
                echo "❌ Failed\n";
                echo "Error: $body\n";
            } else {
                echo "❌ Model not found\n";
            }
            echo "\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Exception: " . $e->getMessage() . "\n\n";
    }
    
    // Small delay between requests
    usleep(300000);
}

if ($workingModel) {
    echo "\n✅✅✅ WORKING MODEL FOUND: '$workingModel' ✅✅✅\n";
    echo "You can now use this model in your BkuController.\n";
} else {
    echo "\n❌ No working model found.\n";
    echo "Please check:\n";
    echo "1. API key is valid and has access to models\n";
    echo "2. API endpoint is correct\n";
    echo "3. Contact Z AI support for available models\n";
}

echo "\nTest completed.\n";
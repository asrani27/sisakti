<?php

require __DIR__ . '/vendor/autoload.php';

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

echo "Testing Z AI API with minimal script...\n";
echo "API Key: " . substr(env('ZAI_API_KEY'), 0, 20) . "...\n\n";

$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . env('ZAI_API_KEY'),
])->post('https://api.z.ai/api/paas/v4/chat/completions', [
    'model' => 'glm-4.7',
    'messages' => [
        ['role' => 'user', 'content' => 'Hello']
    ],
    'temperature' => 0,
    'max_output_tokens' => 20,
]);

dd($response->json());

<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser as PdfParser;

echo "=== Testing Z AI PDF Extraction (SAFE MODE) ===\n\n";

$filePath = __DIR__ . '/public/file/bku2024.pdf';

if (!file_exists($filePath)) {
    echo "ERROR: File not found: $filePath\n";
    exit(1);
}

echo "✓ PDF file found: " . basename($filePath) . "\n";
echo "✓ File size: " . number_format(filesize($filePath)) . " bytes\n\n";

$apiKey = env('ZAI_API_KEY');
if (empty($apiKey)) {
    echo "ERROR: ZAI_API_KEY not found in .env\n";
    exit(1);
}

echo "✓ Z AI API Key found\n\n";

/**
 * STEP 1: Extract text
 */
echo "Extracting text from PDF...\n";
$parser = new PdfParser();
$pdf = $parser->parseFile($filePath);
$text = $pdf->getText();

/**
 * STEP 2: CLEAN & CUT TEXT (INI WAJIB)
 */
$text = preg_replace('/\s+/', ' ', $text); // rapikan
$text = mb_substr($text, 0, 8000);          // POTONG

echo "✓ PDF text cleaned & trimmed (" . strlen($text) . " chars)\n\n";

/**
 * STEP 3: PROMPT RINGKAS & ANTI-HALU
 */
$prompt = <<<PROMPT
You are a data extraction engine.

Extract ONLY BKU (Buku Kas Umum) table rows.
Ignore headers, footers, page numbers, signatures.

Return ONLY a valid JSON array.
NO explanation. NO markdown.

JSON format:
[
  {
    "tanggal": "DD/MM/YYYY",
    "uraian": "...",
    "penerimaan": "Rp X" or "-",
    "pengeluaran": "Rp X" or "-",
    "saldo": "Rp X"
  }
]

PDF TEXT:
{$text}
PROMPT;

$apiUrl = 'https://open.bigmodel.cn/api/paas/v4/chat/completions';

echo "Sending request to Z AI...\n";
echo "Model: glm-4-flash\n";
echo "Max output tokens: 800\n\n";

try {
    $startTime = microtime(true);

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
        'Content-Type'  => 'application/json',
    ])
        ->timeout(180)
        ->retry(2, 2000)
        ->post($apiUrl, [
            'model' => 'glm-4.7',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0,
            'max_output_tokens' => 800,
        ]);

    $duration = round(microtime(true) - $startTime, 2);

    if (!$response->successful()) {
        echo "ERROR: Z AI API failed\n";
        echo "Status: {$response->status()}\n";
        echo "Body: {$response->body()}\n";
        exit(1);
    }

    echo "✓ Request success ({$duration}s)\n\n";

    $result = $response->json();

    if (!isset($result['choices'][0]['message']['content'])) {
        echo "ERROR: Invalid response structure\n";
        print_r($result);
        exit(1);
    }

    $content = $result['choices'][0]['message']['content'];

    /**
     * STEP 4: PARSE JSON
     */
    if (!preg_match('/\[[\s\S]*\]/', $content, $matches)) {
        echo "ERROR: JSON array not found\n";
        echo $content;
        exit(1);
    }

    $data = json_decode($matches[0], true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "ERROR: JSON decode failed: " . json_last_error_msg() . "\n";
        exit(1);
    }

    echo "✓ Extracted " . count($data) . " BKU rows\n\n";
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

    echo "\n✓ TEST FINISHED SUCCESSFULLY\n";
    exit(0);
} catch (\Exception $e) {
    echo "ERROR: Exception\n";
    echo $e->getMessage() . "\n";
    exit(1);
}

<?php
/**
 * Test TUS Upload Functionality
 * This script tests the complete TUS upload flow to S3
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

echo "Testing TUS Upload to S3...\n\n";

// Test 1: Create upload session
echo "1. Creating upload session...\n";
$content = "This is a test file for TUS upload";
$contentSize = strlen($content);

try {
    $client = new \GuzzleHttp\Client();
    $response = $client->post('http://localhost:8000/tus/uploads', [
        'headers' => [
            'Tus-Resumable' => '1.0.0',
            'Upload-Length' => (string)$contentSize,
            'Upload-Metadata' => 'filename dGVzdC5jc3Y=', // Base64 encoded 'test.csv'
        ],
        'body' => ''
    ]);

    $status = $response->getStatusCode();
    if ($status === 201) {
        $uploadUrl = $response->getHeaderLine('Location');
        echo "   ✓ Upload session created\n";
        echo "   Upload URL: {$uploadUrl}\n";
    } else {
        echo "   ✗ Failed to create upload session\n";
        echo "   Status: {$status}\n";
        echo "   Body: {$response->getBody()}\n";
        exit(1);
    }
} catch (\Exception $e) {
    echo "   ✗ Error: {$e->getMessage()}\n";
    echo "   Trace: {$e->getTraceAsString()}\n";
    exit(1);
}

// Extract upload ID from URL
$uploadId = basename(parse_url($uploadUrl, PHP_URL_PATH));
echo "   Upload ID: {$uploadId}\n\n";

// Test 2: Upload file in one chunk
echo "2. Uploading file content ({$contentSize} bytes)...\n";
try {
    $response = $client->patch($uploadUrl, [
        'headers' => [
            'Tus-Resumable' => '1.0.0',
            'Content-Type' => 'application/offset+octet-stream',
            'Upload-Offset' => '0',
        ],
        'body' => $content,
    ]);

    $status = $response->getStatusCode();
    if ($status === 204) {
        $offset = $response->getHeaderLine('Upload-Offset');
        echo "   ✓ Chunk uploaded successfully\n";
        echo "   Upload offset: {$offset}\n";
    } else {
        echo "   ✗ Failed to upload chunk\n";
        echo "   Status: {$status}\n";
        echo "   Body: {$response->getBody()}\n";
        exit(1);
    }
} catch (\Exception $e) {
    echo "   ✗ Error: {$e->getMessage()}\n";
    exit(1);
}

// Wait a moment for processing
sleep(1);
echo "\n";

// Test 3: Get completed upload info
echo "3. Retrieving completed upload information...\n";
try {
    $response = $client->get("http://localhost:8000/tus/uploads/{$uploadId}/info");

    $status = $response->getStatusCode();
    if ($status === 200) {
        $data = json_decode($response->getBody(), true);
        echo "   ✓ Upload completed successfully\n";
        echo "   S3 Path: {$data['path']}\n";
        echo "   Filename: {$data['filename']}\n";
        echo "   Size: {$data['size']} bytes\n";
        echo "   Completed at: {$data['completed_at']}\n";
    } else {
        echo "   ✗ Failed to retrieve upload info\n";
        echo "   Status: {$status}\n";
        echo "   Body: {$response->getBody()}\n";
        exit(1);
    }
} catch (\Exception $e) {
    echo "   ✗ Error: {$e->getMessage()}\n";
    exit(1);
}

echo "\n✓ All TUS upload tests passed successfully!\n";
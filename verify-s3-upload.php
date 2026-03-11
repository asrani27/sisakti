<?php
/**
 * Verify TUS Upload in S3
 * This script verifies the uploaded file exists in S3
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;

echo "Verifying TUS Upload in S3...\n\n";

$uploadId = '9cd82279-b574-4187-a30a-23b33f8b6204';
$expectedPath = "aturan/test.csv";

echo "1. Checking if file exists in S3...\n";
if (Storage::disk('s3')->exists($expectedPath)) {
    echo "   ✓ File exists in S3\n";
    echo "   Path: {$expectedPath}\n";
} else {
    echo "   ✗ File not found in S3\n";
    exit(1);
}

echo "\n2. Getting file information...\n";
$size = Storage::disk('s3')->size($expectedPath);
$lastModified = Storage::disk('s3')->lastModified($expectedPath);

echo "   Size: {$size} bytes\n";
echo "   Last Modified: " . date('Y-m-d H:i:s', $lastModified) . "\n";

echo "\n3. Reading file content...\n";
$content = Storage::disk('s3')->get($expectedPath);
echo "   Content: {$content}\n";

echo "\n4. Listing all files in aturan directory...\n";
$files = Storage::disk('s3')->files('aturan');
echo "   Total files: " . count($files) . "\n";
foreach ($files as $file) {
    echo "   - {$file}\n";
}

echo "\n✓ Verification completed successfully!\n";
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestS3Connection extends Command
{
    protected $signature = 's3:test';
    protected $description = 'Test S3 connection and basic operations';

    public function handle()
    {
        $this->info('Testing S3 Connection...');
        $this->line('');

        // Test 1: Check if S3 disk is configured
        $this->info('1. Checking S3 disk configuration...');
        if (!config('filesystems.disks.s3')) {
            $this->error('S3 disk is not configured!');
            return 1;
        }
        $this->info('✓ S3 disk is configured');
        $this->line('');

        // Test 2: Check environment variables
        $this->info('2. Checking environment variables...');
        $this->line('   AWS_ACCESS_KEY_ID: ' . (config('filesystems.disks.s3.key') ? '✓ Set (' . substr(config('filesystems.disks.s3.key'), 0, 8) . '...)' : '✗ Not set'));
        $this->line('   AWS_SECRET_ACCESS_KEY: ' . (config('filesystems.disks.s3.secret') ? '✓ Set' : '✗ Not set'));
        $this->line('   AWS_DEFAULT_REGION: ' . config('filesystems.disks.s3.region'));
        $this->line('   AWS_BUCKET: ' . config('filesystems.disks.s3.bucket'));
        $this->line('   AWS_URL: ' . (config('filesystems.disks.s3.url') ?: 'Not set'));
        $this->line('   AWS_ENDPOINT: ' . (config('filesystems.disks.s3.endpoint') ?: 'Not set'));
        $this->line('   AWS_USE_PATH_STYLE_ENDPOINT: ' . (config('filesystems.disks.s3.use_path_style_endpoint') ? 'true' : 'false'));
        $this->line('');

        // Test 4: Test file upload
        $this->info('4. Testing file upload...');
        try {
            $testContent = 'This is a test file for S3 connection';
            $testPath = 'test/' . uniqid() . '.txt';
            Storage::disk('s3')->put($testPath, $testContent);
            $this->info('✓ File uploaded successfully: ' . $testPath);
        } catch (\Exception $e) {
            $this->error('✗ File upload failed: ' . $e->getMessage());
            return 1;
        }
        $this->line('');

        // Test 5: Test file download
        $this->info('5. Testing file download...');
        try {
            $content = Storage::disk('s3')->get($testPath);
            if ($content === $testContent) {
                $this->info('✓ File downloaded successfully');
            } else {
                $this->error('✗ File content mismatch');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('✗ File download failed: ' . $e->getMessage());
            return 1;
        }
        $this->line('');

        // Test 6: Test file exists
        $this->info('6. Testing file exists...');
        try {
            $exists = Storage::disk('s3')->exists($testPath);
            if ($exists) {
                $this->info('✓ File exists check successful');
            } else {
                $this->error('✗ File does not exist');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('✗ File exists check failed: ' . $e->getMessage());
            return 1;
        }
        $this->line('');

        // Test 7: Test file size
        $this->info('7. Testing file size...');
        try {
            $size = Storage::disk('s3')->size($testPath);
            $this->info('✓ File size: ' . $size . ' bytes');
        } catch (\Exception $e) {
            $this->error('✗ File size check failed: ' . $e->getMessage());
            return 1;
        }
        $this->line('');

        // Test 8: Cleanup test file
        $this->info('8. Cleaning up test file...');
        try {
            Storage::disk('s3')->delete($testPath);
            $this->info('✓ Test file deleted successfully');
        } catch (\Exception $e) {
            $this->error('✗ File deletion failed: ' . $e->getMessage());
            return 1;
        }
        $this->line('');

        $this->info('✓ All S3 tests passed successfully!');
        return 0;
    }
}
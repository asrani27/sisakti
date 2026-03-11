<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TusUploadController extends Controller
{
    protected $chunkSize = 512 * 1024; // 512KB in bytes

    /**
     * Handle TUS protocol requests
     */
    public function handle(Request $request, $uploadId = null)
    {
        $method = $request->method();

        // Handle TUS protocol methods
        switch ($method) {
            case 'OPTIONS':
                return $this->handleOptions();
            case 'HEAD':
                return $this->handleHead($uploadId);
            case 'POST':
                return $this->handlePost($request);
            case 'PATCH':
                return $this->handlePatch($request, $uploadId);
            default:
                return response('', 405)->header('Allow', 'OPTIONS, HEAD, POST, PATCH');
        }
    }

    /**
     * Handle OPTIONS request for CORS
     */
    protected function handleOptions()
    {
        return response('', 204)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'OPTIONS, HEAD, POST, PATCH')
            ->header('Access-Control-Allow-Headers', 'Tus-Resumable, Upload-Length, Upload-Metadata, Upload-Offset, Content-Type')
            ->header('Tus-Version', '1.0.0')
            ->header('Tus-Resumable', '1.0.0')
            ->header('Tus-Extension', 'creation,termination');
    }

    /**
     * Handle HEAD request to get upload info
     */
    protected function handleHead($uploadId)
    {
        $uploadData = cache()->get("tus_upload_{$uploadId}");

        if (!$uploadData) {
            return response('', 404);
        }

        return response('', 200)
            ->header('Tus-Resumable', '1.0.0')
            ->header('Upload-Length', $uploadData['total_size'])
            ->header('Upload-Offset', $uploadData['uploaded_size']);
    }

    /**
     * Handle POST request to create new upload
     */
    protected function handlePost(Request $request)
    {
        $uploadLength = $request->header('Upload-Length');
        $uploadMetadata = $request->header('Upload-Metadata');

        if (!$uploadLength) {
            return response('', 400);
        }

        $uploadId = Str::uuid()->toString();
        $filename = $this->getFilenameFromMetadata($uploadMetadata);

        // Store upload metadata in cache
        cache()->put("tus_upload_{$uploadId}", [
            'total_size' => $uploadLength,
            'uploaded_size' => 0,
            'filename' => $filename,
            'created_at' => now(),
        ], now()->addHours(24));

        $uploadUrl = route('tus.upload', $uploadId);

        return response('', 201)
            ->header('Tus-Resumable', '1.0.0')
            ->header('Location', $uploadUrl)
            ->header('Access-Control-Expose-Headers', 'Location, Tus-Resumable');
    }

    /**
     * Handle PATCH request to upload chunks
     */
    protected function handlePatch(Request $request, $uploadId)
    {
        $uploadData = cache()->get("tus_upload_{$uploadId}");

        if (!$uploadData) {
            return response('', 404);
        }

        $uploadOffset = $request->header('Upload-Offset');
        $contentType = $request->header('Content-Type');

        if ($uploadOffset != $uploadData['uploaded_size']) {
            return response('', 409)
                ->header('Tus-Resumable', '1.0.0')
                ->header('Upload-Offset', $uploadData['uploaded_size']);
        }

        if ($contentType !== 'application/offset+octet-stream') {
            return response('', 415);
        }

        // Get chunk data
        $chunk = $request->getContent();
        $chunkSize = strlen($chunk);

        if ($chunkSize > $this->chunkSize) {
            return response('', 413);
        }

        // Use local storage for temporary file (supports appending)
        $tempPath = storage_path("app/tus_uploads/temp_{$uploadId}");
        
        // Ensure directory exists
        if (!is_dir(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        // Append chunk to temporary file
        $file = fopen($tempPath, $uploadData['uploaded_size'] === 0 ? 'w' : 'a');
        fwrite($file, $chunk);
        fclose($file);

        // Update upload data
        $newOffset = $uploadData['uploaded_size'] + $chunkSize;
        $uploadData['uploaded_size'] = $newOffset;
        
        cache()->put("tus_upload_{$uploadId}", $uploadData, now()->addHours(24));

        // Check if upload is complete
        if ($newOffset >= $uploadData['total_size']) {
            // Move file to S3
            $finalPath = "aturan/{$uploadData['filename']}";
            Storage::disk('s3')->put($finalPath, file_get_contents($tempPath));
            
            // Delete temporary file
            unlink($tempPath);
            
            // Store final file path in cache
            cache()->put("tus_complete_{$uploadId}", [
                'path' => $finalPath,
                'filename' => $uploadData['filename'],
                'size' => $newOffset,
                'completed_at' => now(),
            ], now()->addHours(24));

            // Remove upload data
            cache()->forget("tus_upload_{$uploadId}");
        }

        return response('', 204)
            ->header('Tus-Resumable', '1.0.0')
            ->header('Upload-Offset', $newOffset);
    }

    /**
     * Get filename from TUS metadata
     */
    protected function getFilenameFromMetadata($metadata)
    {
        if (!$metadata) {
            return 'file_' . time() . '.bin';
        }

        $metadataParts = explode(',', $metadata);
        foreach ($metadataParts as $part) {
            if (strpos($part, 'filename') === 0) {
                $filename = base64_decode(substr($part, 9));
                return $filename ?: 'file_' . time() . '.bin';
            }
        }

        return 'file_' . time() . '.bin';
    }

    /**
     * Get completed upload information
     */
    public function getCompletedUpload(Request $request, $uploadId)
    {
        $uploadData = cache()->get("tus_complete_{$uploadId}");

        if (!$uploadData) {
            return response()->json([
                'error' => 'Upload not found or not completed'
            ], 404);
        }

        return response()->json([
            'path' => $uploadData['path'],
            'filename' => $uploadData['filename'],
            'size' => $uploadData['size'],
            'completed_at' => $uploadData['completed_at']->toISOString(),
        ]);
    }
}
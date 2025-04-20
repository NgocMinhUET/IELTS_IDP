<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;

class S3Helper
{
    /**
     * Validate an S3 file path.
     *
     * @param string $path
     * @param string $type | 'image' or 'video'
     * @param int $size | Size in MB
     * @return bool
     */
    public static function validateS3Path($path, $type, $size): bool
    {
        try {
            if (!Storage::disk('s3')->exists($path)) {
                return false;
            }

            $s3Client = Storage::disk('s3')->getClient();

            // Check if the file exists
            $result = $s3Client->headObject([
                'Bucket' => config('filesystems.disks.s3.bucket'),
                'Key' => $path,
            ]);

            // Check file size
            $fileSize = $result['ContentLength'];
            if ($fileSize > $size * 1024 * 1024) {
                return false;
            }

            // Check file type
            $contentType = $result['ContentType'];
            if ($type == 'image' && !in_array($contentType, ['image/jpeg', 'image/png', 'image/jpg'])) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('S3 validation error: ' . $e->getMessage());
            return false;
        }
    }
}

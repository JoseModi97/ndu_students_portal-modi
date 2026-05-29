<?php

namespace app\modules\CautionRefund\helpers;

use Yii;
use yii\web\UploadedFile;

class UploadHelper
{
    /** Maximum allowed file size in bytes (2 MB) */
    const MAX_FILE_SIZE = 2097152;

    /** Allowed file extensions */
    const ALLOWED_EXTENSIONS = [
        'pdf',
        'jpg',
        'jpeg',
        'jfif',
        'png',
        'gif',
        'bmp',
        'webp',
        'tif',
        'tiff',
        'jp2',
        'jps',
        'psd',
        'svg',
        'ai',
        'eps',
        'raw',
        'cr2',
        'ico',
        'ppm',
        'tga',
        'xcf',
    ];

    const ALLOWED_MIMES = [
        'application/pdf',
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/bmp',
        'image/webp',
        'image/tiff',
        'image/svg+xml',
        'image/x-icon',
        'image/vnd.adobe.photoshop',
        'image/x-raw',
        'image/x-canon-cr2',
        'application/postscript', // ai, eps
        'image/jp2',
        'image/x-portable-pixmap',
        'image/x-targa',
        'image/x-xcf',
    ];

    public static function processUpload(UploadedFile $file, string $regNo, string $documentName): array
    {
        // --- 1. Size check ---
        if ($file->size > self::MAX_FILE_SIZE) {
            return self::fail('File size must be less than 2 MB.');
        }

        // --- 2. Extension check ---
        $ext = strtolower($file->extension);
        if (!in_array($ext, self::ALLOWED_EXTENSIONS, true)) {
            return self::fail('Only PDFs and image files are allowed.');
        }

        // --- 3. MIME type check (reads actual file bytes) ---
        $mime = mime_content_type($file->tempName);
        if ($mime === false || !in_array($mime, self::ALLOWED_MIMES, true)) {
            return self::fail('File content does not match an allowed type.');
        }

        $safeReg  = str_replace('/', '_', $regNo);
        $safeName = preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ', '', ucwords($documentName)));
        $dir      = Yii::getAlias('@app') . '/uploads/' . $safeReg;
        $filename = $safeName . '_' . $safeReg . '.' . $ext;
        $fullPath = $dir . '/' . $filename;

        // e.g. "A_B1_2024_001/NationalId_A_B1_2024_001.pdf"
        $storedPath = $safeReg . '/' . $filename;

        // --- 5. Create student directory if needed ---
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            return self::fail('Could not create upload directory.');
        }

        // --- 6. Save file ---
        if (!$file->saveAs($fullPath)) {
            return self::fail('Failed to save file.');
        }

        chmod($fullPath, 0644);

        return ['ok' => true, 'path' => $storedPath, 'error' => null];
    }

    public static function resolveFullPath(string $storedPath): string
    {
        return Yii::getAlias('@app') . '/uploads/' . $storedPath;
    }

    private static function fail(string $message): array
    {
        return ['ok' => false, 'path' => null, 'error' => $message];
    }
}

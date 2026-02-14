<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileUploadService
{
    protected $allowedMimes = [
        'image' => ['jpeg', 'png', 'jpg', 'gif', 'webp', 'svg'],
        'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf'],
        'video' => ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'],
        'audio' => ['mp3', 'wav', 'ogg', 'm4a'],
        'archive' => ['zip', 'rar', 'tar', 'gz', '7z'],
    ];

    protected $maxSizes = [
        'image' => 5,      // 5MB
        'document' => 10,  // 10MB
        'video' => 100,   // 100MB
        'audio' => 50,    // 50MB
        'archive' => 100  // 100MB
    ];

    public function upload(UploadedFile $file, $type = 'general', $path = null, $customOptions = [])
    {
        // Validate file
        $validationResult = $this->validateFile($file, $type, $customOptions);
        if (!$validationResult['valid']) {
            return [
                'success' => false,
                'errors' => $validationResult['errors']
            ];
        }

        // Generate path if not provided
        if (!$path) {
            $path = $this->getDefaultPath($type);
        }

        // Generate unique filename
        $filename = $this->generateFilename($file, $customOptions);
        $fullPath = $path . '/' . $filename;

        // Store the file
        $stored = Storage::disk('public')->put($fullPath, file_get_contents($file->getPathname()));

        if (!$stored) {
            return [
                'success' => false,
                'errors' => ['Failed to store file']
            ];
        }

        return [
            'success' => true,
            'filename' => $filename,
            'path' => $fullPath,
            'url' => Storage::url($fullPath),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
        ];
    }

    public function uploadMultiple(array $files, $type = 'general', $path = null, $customOptions = [])
    {
        $results = [];
        $successCount = 0;
        $errorCount = 0;

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $result = $this->upload($file, $type, $path, $customOptions);
                $results[] = $result;
                
                if ($result['success']) {
                    $successCount++;
                } else {
                    $errorCount++;
                }
            }
        }

        return [
            'success' => $errorCount === 0,
            'total' => count($files),
            'successful' => $successCount,
            'failed' => $errorCount,
            'results' => $results
        ];
    }

    public function validateFile(UploadedFile $file, $type = 'general', $customOptions = [])
    {
        $errors = [];

        // Check if file is valid
        if (!$file->isValid()) {
            $errors[] = 'File upload error: ' . $file->getError();
            return ['valid' => false, 'errors' => $errors];
        }

        // Get file extension
        $extension = strtolower($file->getClientOriginalExtension());

        // Check allowed extensions
        $allowedExtensions = $this->getAllowedExtensions($type);
        if (!in_array($extension, $allowedExtensions)) {
            $errors[] = "File type '{$extension}' is not allowed for {$type} files.";
        }

        // Check file size
        $maxSize = $this->getMaxSize($type, $customOptions);
        $fileSizeInMB = $file->getSize() / 1024 / 1024; // Convert bytes to MB
        if ($fileSizeInMB > $maxSize) {
            $errors[] = "File size ({$fileSizeInMB} MB) exceeds maximum allowed size ({$maxSize} MB) for {$type} files.";
        }

        // Additional custom validation if provided
        if (isset($customOptions['custom_validator']) && is_callable($customOptions['custom_validator'])) {
            $customValidation = $customOptions['custom_validator']($file);
            if ($customValidation !== true) {
                $errors[] = $customValidation;
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    public function delete($path)
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    public function bulkDelete(array $paths)
    {
        $deletedCount = 0;
        $totalCount = count($paths);

        foreach ($paths as $path) {
            if ($this->delete($path)) {
                $deletedCount++;
            }
        }

        return [
            'deleted' => $deletedCount,
            'total' => $totalCount,
            'success' => $deletedCount === $totalCount
        ];
    }

    public function getFileUrl($path)
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }
        return null;
    }

    public function getFileSize($path)
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::size($path);
        }
        return null;
    }

    public function getFileInfo($path)
    {
        if (!Storage::disk('public')->exists($path)) {
            return null;
        }

        return [
            'path' => $path,
            'url' => Storage::url($path),
            'size' => Storage::size($path),
            'mime_type' => Storage::mimeType($path),
            'last_modified' => Storage::lastModified($path)
        ];
    }

    protected function getDefaultPath($type)
    {
        $datePath = now()->format('Y/m');
        return "uploads/{$type}/{$datePath}";
    }

    protected function getAllowedExtensions($type)
    {
        if (isset($this->allowedMimes[$type])) {
            return $this->allowedMimes[$type];
        }

        // Return all allowed extensions if type is not specified
        $allExtensions = [];
        foreach ($this->allowedMimes as $extensions) {
            $allExtensions = array_merge($allExtensions, $extensions);
        }
        return array_unique($allExtensions);
    }

    protected function getMaxSize($type, $customOptions)
    {
        if (isset($customOptions['max_size'])) {
            return $customOptions['max_size'];
        }

        return $this->maxSizes[$type] ?? $this->maxSizes['general'] ?? 10; // Default to 10MB
    }

    protected function generateFilename(UploadedFile $file, $customOptions = [])
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        if (isset($customOptions['preserve_name']) && $customOptions['preserve_name']) {
            // Use original name with timestamp to avoid conflicts
            $filename = Str::slug($originalName) . '_' . time() . '.' . $extension;
        } else {
            // Generate unique name
            $filename = Str::random(20) . '_' . time() . '.' . $extension;
        }

        // Apply custom prefix if provided
        if (isset($customOptions['prefix'])) {
            $filename = $customOptions['prefix'] . '_' . $filename;
        }

        return $filename;
    }

    public function resizeImage($path, $width = null, $height = null, $quality = 80)
    {
        // Check if the file is an image
        $mimeType = Storage::mimeType($path);
        if (!str_starts_with($mimeType, 'image/')) {
            return $path; // Only process image files
        }

        // If no dimensions provided, return original path
        if (!$width && !$height) {
            return $path;
        }

        try {
            // Get the image content
            $imageContent = Storage::get($path);
            
            // Create image resource based on file type
            $imageInfo = getimagesizefromstring($imageContent);
            $imageType = $imageInfo[2];
            
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg('data://application/octet-stream;base64,' . base64_encode($imageContent));
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng('data://application/octet-stream;base64,' . base64_encode($imageContent));
                    break;
                case IMAGETYPE_GIF:
                    $image = imagecreatefromgif('data://application/octet-stream;base64,' . base64_encode($imageContent));
                    break;
                case IMAGETYPE_WEBP:
                    if (function_exists('imagecreatefromwebp')) {
                        $image = imagecreatefromwebp('data://application/octet-stream;base64,' . base64_encode($imageContent));
                    } else {
                        return $path; // WebP not supported, return original
                    }
                    break;
                default:
                    return $path; // Unsupported image type
            }

            // Calculate new dimensions maintaining aspect ratio if only one dimension is provided
            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);

            if ($width && !$height) {
                $height = ($width / $originalWidth) * $originalHeight;
            } elseif ($height && !$width) {
                $width = ($height / $originalHeight) * $originalWidth;
            } elseif ($width && $height) {
                // Maintain aspect ratio
                $ratio = min($width / $originalWidth, $height / $originalHeight);
                $width = $originalWidth * $ratio;
                $height = $originalHeight * $ratio;
            }

            // Create new image with calculated dimensions
            $resizedImage = imagecreatetruecolor($width, $height);

            // Preserve transparency for PNG and GIF
            if ($imageType === IMAGETYPE_PNG || $imageType === IMAGETYPE_GIF) {
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
                imagefilledrectangle($resizedImage, 0, 0, $width, $height, $transparent);
            }

            // Resize the image
            imagecopyresampled(
                $resizedImage,
                $image,
                0, 0, 0, 0,
                $width, $height,
                $originalWidth, $originalHeight
            );

            // Generate new filename for resized image
            $pathInfo = pathinfo($path);
            $resizedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_resized_' . $width . 'x' . $height . '.' . $pathInfo['extension'];

            // Save the resized image based on type
            $tempResource = fopen('php://temp', 'r+');
            
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    imagejpeg($resizedImage, $tempResource, $quality);
                    break;
                case IMAGETYPE_PNG:
                    // PNG compression is 0-9 (reverse scale: 0 = best quality, 9 = smallest size)
                    $compression = floor((100 - $quality) / 10);
                    imagepng($resizedImage, $tempResource, $compression);
                    break;
                case IMAGETYPE_GIF:
                    imagegif($resizedImage, $tempResource);
                    break;
                case IMAGETYPE_WEBP:
                    if (function_exists('imagewebp')) {
                        imagewebp($resizedImage, $tempResource, $quality);
                    } else {
                        imagedestroy($resizedImage);
                        return $path; // WebP not supported, return original
                    }
                    break;
                default:
                    imagedestroy($resizedImage);
                    return $path; // Unsupported image type
            }
            
            // Get the image data from temp resource
            rewind($tempResource);
            $resizedImageData = stream_get_contents($tempResource);
            fclose($tempResource);
            
            // Save to storage
            Storage::put($resizedPath, $resizedImageData);

            // Free up memory
            imagedestroy($image);
            imagedestroy($resizedImage);

            return $resizedPath;
        } catch (\Exception $e) {
            \Log::error("Failed to resize image: " . $e->getMessage());
            return $path; // Return original path if resize fails
        }
    }
}

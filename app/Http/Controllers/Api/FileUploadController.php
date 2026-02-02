<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessImageUploadJob;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FileUploadController extends Controller
{
    /**
     * Maximum file size in bytes (10MB)
     */
    const MAX_FILE_SIZE = 10 * 1024 * 1024;

    /**
     * Allowed file types
     */
    const ALLOWED_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    /**
     * Upload single file
     */
    public function upload(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|max:' . (self::MAX_FILE_SIZE / 1024),
                'type' => 'nullable|string|in:avatar,document,image,report',
                'resize' => 'nullable|array',
                'resize.width' => 'nullable|integer|min:1|max:2000',
                'resize.height' => 'nullable|integer|min:1|max:2000',
                'create_thumbnail' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $file = $request->file('file');
            $type = $request->get('type', 'document');
            $options = $request->get('resize', []);
            $createThumbnail = $request->get('create_thumbnail', false);

            // Validate file type
            if (!in_array($file->getMimeType(), self::ALLOWED_TYPES)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File type not allowed',
                    'allowed_types' => self::ALLOWED_TYPES,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Generate unique filename
            $filename = $this->generateUniqueFilename($file);
            $tempPath = $file->store('temp', 'public');
            $finalPath = $this->getFinalPath($type, $filename);

            // Process image if it's an image file
            if (str_starts_with($file->getMimeType(), 'image/')) {
                $imageOptions = [
                    'resize_width' => $options['width'] ?? null,
                    'resize_height' => $options['height'] ?? null,
                    'create_thumbnail' => $createThumbnail,
                    'optimize' => true,
                ];

                ProcessImageUploadJob::dispatch($tempPath, $finalPath, $imageOptions);
            } else {
                // Move non-image files directly
                Storage::disk('public')->move($tempPath, $finalPath);
            }

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => [
                    'filename' => $filename,
                    'path' => $finalPath,
                    'url' => Storage::disk('public')->url($finalPath),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'type' => $type,
                ],
            ], Response::HTTP_CREATED);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Upload multiple files
     */
    public function uploadMultiple(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'files' => 'required|array|max:10',
                'files.*' => 'required|file|max:' . (self::MAX_FILE_SIZE / 1024),
                'type' => 'nullable|string|in:avatar,document,image,report',
                'resize' => 'nullable|array',
                'resize.width' => 'nullable|integer|min:1|max:2000',
                'resize.height' => 'nullable|integer|min:1|max:2000',
                'create_thumbnail' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $files = $request->file('files');
            $type = $request->get('type', 'document');
            $options = $request->get('resize', []);
            $createThumbnail = $request->get('create_thumbnail', false);

            $results = [];
            $errors = [];

            foreach ($files as $index => $file) {
                try {
                    // Validate file type
                    if (!in_array($file->getMimeType(), self::ALLOWED_TYPES)) {
                        $errors[$index] = 'File type not allowed';
                        continue;
                    }

                    $filename = $this->generateUniqueFilename($file);
                    $tempPath = $file->store('temp', 'public');
                    $finalPath = $this->getFinalPath($type, $filename);

                    // Process image if it's an image file
                    if (str_starts_with($file->getMimeType(), 'image/')) {
                        $imageOptions = [
                            'resize_width' => $options['width'] ?? null,
                            'resize_height' => $options['height'] ?? null,
                            'create_thumbnail' => $createThumbnail,
                            'optimize' => true,
                        ];

                        ProcessImageUploadJob::dispatch($tempPath, $finalPath, $imageOptions);
                    } else {
                        // Move non-image files directly
                        Storage::disk('public')->move($tempPath, $finalPath);
                    }

                    $results[$index] = [
                        'filename' => $filename,
                        'path' => $finalPath,
                        'url' => Storage::disk('public')->url($finalPath),
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                    ];

                } catch (\Exception $e) {
                    $errors[$index] = $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Files processed',
                'data' => [
                    'uploaded' => $results,
                    'errors' => $errors,
                    'total_files' => count($files),
                    'successful' => count($results),
                    'failed' => count($errors),
                ],
            ], Response::HTTP_CREATED);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete file
     */
    public function delete(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'path' => 'required|string',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $path = $request->get('path');

            if (!Storage::disk('public')->exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found',
                ], Response::HTTP_NOT_FOUND);
            }

            // Delete main file
            Storage::disk('public')->delete($path);

            // Delete thumbnail if exists
            $thumbnailPath = $this->getThumbnailPath($path);
            if (Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully',
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get file information
     */
    public function info(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'path' => 'required|string',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $path = $request->get('path');

            if (!Storage::disk('public')->exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found',
                ], Response::HTTP_NOT_FOUND);
            }

            $fullPath = Storage::disk('public')->path($path);
            $fileInfo = [
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'size' => Storage::disk('public')->size($path),
                'last_modified' => Storage::disk('public')->lastModified($path),
                'mime_type' => mime_content_type($fullPath),
                'exists' => true,
            ];

            // Check if thumbnail exists
            $thumbnailPath = $this->getThumbnailPath($path);
            if (Storage::disk('public')->exists($thumbnailPath)) {
                $fileInfo['thumbnail'] = [
                    'path' => $thumbnailPath,
                    'url' => Storage::disk('public')->url($thumbnailPath),
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $fileInfo,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get file info',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Generate unique filename
     */
    protected function generateUniqueFilename($file): string
    {
        $extension = $file->getClientOriginalExtension();
        $basename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $timestamp = now()->format('YmdHis');
        $random = str_random(6);

        return "{$basename}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Get final path based on type
     */
    protected function getFinalPath(string $type, string $filename): string
    {
        $directory = match($type) {
            'avatar' => 'avatars',
            'document' => 'documents',
            'image' => 'images',
            'report' => 'reports',
            default => 'uploads',
        };

        return "{$directory}/{$filename}";
    }

    /**
     * Get thumbnail path
     */
    protected function getThumbnailPath(string $originalPath): string
    {
        $pathInfo = pathinfo($originalPath);
        return $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
    }
}

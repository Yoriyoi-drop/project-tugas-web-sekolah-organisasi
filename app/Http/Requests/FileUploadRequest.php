<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class FileUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $maxSize = config('filesystems.max_upload_size', 10240); // 10MB default

        return [
            'file' => 'required|file|max:' . $maxSize,
            'type' => 'nullable|string|in:avatar,document,image,report',
            'resize' => 'nullable|array',
            'resize.width' => 'nullable|integer|min:1|max:2000',
            'resize.height' => 'nullable|integer|min:1|max:2000',
            'create_thumbnail' => 'nullable|boolean',
            'quality' => 'nullable|integer|min:1|max:100',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Please select a file to upload.',
            'file.file' => 'The uploaded file is invalid.',
            'file.max' => 'The file may not be greater than :max kilobytes.',
            'type.in' => 'The selected file type is invalid.',
            'resize.width.integer' => 'The resize width must be an integer.',
            'resize.width.min' => 'The resize width must be at least 1 pixel.',
            'resize.width.max' => 'The resize width may not be greater than 2000 pixels.',
            'resize.height.integer' => 'The resize height must be an integer.',
            'resize.height.min' => 'The resize height must be at least 1 pixel.',
            'resize.height.max' => 'The resize height may not be greater than 2000 pixels.',
            'quality.integer' => 'The quality must be an integer.',
            'quality.min' => 'The quality must be at least 1.',
            'quality.max' => 'The quality may not be greater than 100.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'file' => 'file',
            'type' => 'file type',
            'resize.width' => 'resize width',
            'resize.height' => 'resize height',
            'quality' => 'image quality',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isEmpty()) {
                $this->validateFileType($validator);
                $this->validateImageDimensions($validator);
            }
        });
    }

    /**
     * Validate file type
     */
    protected function validateFileType($validator)
    {
        $file = $this->file('file');
        if (!$file) return;

        $allowedTypes = config('filesystems.allowed_types', [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);

        if (!in_array($file->getMimeType(), $allowedTypes)) {
            $validator->errors()->add('file', 'The file type is not allowed.');
        }
    }

    /**
     * Validate image dimensions
     */
    protected function validateImageDimensions($validator)
    {
        $file = $this->file('file');
        if (!$file || !str_starts_with($file->getMimeType(), 'image/')) return;

        try {
            $imageInfo = getimagesize($file->getPathname());
            if (!$imageInfo) {
                $validator->errors()->add('file', 'Unable to process image file.');
                return;
            }

            [$width, $height] = $imageInfo;
            $maxDimensions = config('filesystems.max_image_dimensions', 4000);

            if ($width > $maxDimensions || $height > $maxDimensions) {
                $validator->errors()->add('file', "Image dimensions may not exceed {$maxDimensions}x{$maxDimensions} pixels.");
            }
        } catch (\Exception $e) {
            $validator->errors()->add('file', 'Unable to validate image dimensions.');
        }
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new ValidationException($validator, $this->formatValidationErrors($validator));
    }

    /**
     * Format validation errors for API response.
     */
    protected function formatValidationErrors(Validator $validator): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'File upload validation failed',
            'errors' => $validator->errors(),
            'timestamp' => now()->toISOString(),
        ], 422);
    }

    /**
     * Get upload options
     */
    public function getUploadOptions(): array
    {
        return [
            'type' => $this->get('type', 'document'),
            'resize' => $this->get('resize', []),
            'create_thumbnail' => $this->boolean('create_thumbnail', false),
            'quality' => $this->get('quality', 85),
        ];
    }
}

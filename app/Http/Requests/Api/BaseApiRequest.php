<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

abstract class BaseApiRequest extends FormRequest
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
    abstract public function rules(): array;

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'email' => 'The :attribute must be a valid email address.',
            'unique' => 'The :attribute has already been taken.',
            'max' => 'The :attribute may not be greater than :max characters.',
            'min' => 'The :attribute must be at least :min characters.',
            'string' => 'The :attribute must be a string.',
            'integer' => 'The :attribute must be an integer.',
            'date' => 'The :attribute must be a valid date.',
            'boolean' => 'The :attribute field must be true or false.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [];
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
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
            'timestamp' => now()->toISOString(),
        ], 422);
    }

    /**
     * Get sanitized input data.
     */
    public function getSanitized(): array
    {
        return $this->validated();
    }

    /**
     * Get specific fields from validated data.
     */
    public function getOnly(array $fields): array
    {
        return array_intersect_key($this->validated(), array_flip($fields));
    }

    /**
     * Get all validated data except specified fields.
     */
    public function getExcept(array $fields): array
    {
        return array_diff_key($this->validated(), array_flip($fields));
    }
}

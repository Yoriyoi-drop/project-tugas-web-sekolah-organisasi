<?php

namespace App\Http\Requests\Api;

class SearchStudentRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'q' => 'required|string|min:2|max:100',
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'q.required' => 'Search query is required.',
            'q.min' => 'Search query must be at least 2 characters.',
            'q.max' => 'Search query may not be greater than 100 characters.',
            'per_page.integer' => 'Per page must be an integer.',
            'per_page.min' => 'Per page must be at least 1.',
            'per_page.max' => 'Per page may not be greater than 100.',
            'page.integer' => 'Page must be an integer.',
            'page.min' => 'Page must be at least 1.',
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'q' => 'search query',
            'per_page' => 'per page',
            'page' => 'page',
        ];
    }

    /**
     * Get search parameters.
     */
    public function getSearchParams(): array
    {
        return [
            'query' => $this->validated()['q'],
            'per_page' => $this->validated()['per_page'] ?? 15,
            'page' => $this->validated()['page'] ?? 1,
        ];
    }
}

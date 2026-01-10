<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'type' => 'required|max:255',
            'description' => 'required',
            'icon' => 'required|max:255',
            'color' => 'nullable|string|max:50',
            'tagline' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'programs' => 'nullable|string',
            'leadership_names' => 'nullable|array',
            'leadership_names.*' => 'nullable|string|max:255',
            'leadership_positions' => 'nullable|array',
            'leadership_positions.*' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean'
        ];
    }
}

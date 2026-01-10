<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Admin middleware handles auth
    }

    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'excerpt' => 'required|max:500',
            'content' => 'required',
            'category' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'nullable|in:published,draft,on', 
        ];
    }
}

<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email:rfc,dns|ends_with:gmail.com|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ];

        // Add nik/nis rules only if columns exist to prevent SQL errors
        if (Schema::hasColumn('users', 'nik')) {
            $rules['nik'] = 'required|string|digits_between:8,20|unique:users,nik';
        } else {
            $rules['nik'] = 'nullable|string';
        }

        if (Schema::hasColumn('users', 'nis')) {
            $rules['nis'] = 'required|string|digits_between:5,20|unique:users,nis';
        } else {
            $rules['nis'] = 'nullable|string';
        }

        return $rules;
    }
}

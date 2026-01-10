<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PPDBRequest extends FormRequest
{
    public function rules()
    {
        $ppdbId = $this->route('ppdb') ? $this->route('ppdb')->id : null;

        return [
            'name' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:ppdb,nik,' . $ppdbId,
            'email' => 'required|email|max:255|unique:ppdb,email,' . $ppdbId,
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'previous_school' => 'required|string|max:255',
            'desired_major' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,approved,rejected'
        ];
    }

    public function authorize()
    {
        return true;
    }
}

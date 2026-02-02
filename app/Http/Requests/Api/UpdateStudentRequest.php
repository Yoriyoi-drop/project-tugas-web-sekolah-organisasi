<?php

namespace App\Http\Requests\Api;

class UpdateStudentRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $studentId = $this->route('id');

        return [
            'name' => 'required|string|max:255',
            'nis' => 'required|string|max:20|unique:students,nis,' . $studentId,
            'email' => 'required|email|max:255|unique:students,email,' . $studentId,
            'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'class' => 'nullable|string|max:50',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'name.required' => 'Student name is required.',
            'name.max' => 'Student name may not be greater than 255 characters.',
            'nis.required' => 'NIS (Nomor Induk Siswa) is required.',
            'nis.unique' => 'This NIS is already registered.',
            'nis.max' => 'NIS may not be greater than 20 characters.',
            'email.required' => 'Email address is required.',
            'email.unique' => 'This email is already registered.',
            'email.email' => 'Please provide a valid email address.',
            'phone.regex' => 'Please provide a valid phone number.',
            'class.max' => 'Class name may not be greater than 50 characters.',
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'student name',
            'nis' => 'NIS',
            'email' => 'email address',
            'phone' => 'phone number',
            'class' => 'class',
        ];
    }
}

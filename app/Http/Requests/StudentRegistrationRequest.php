<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'nik' => 'required|string|unique:student_registrations,nik|regex:/^[0-9]{16}$/',
            'email' => 'required|email|unique:student_registrations,email|unique:users,email',
            'birth_date' => 'required|date|before:today',
            'birth_place' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'phone' => 'required|string|regex:/^[0-9]{10,15}$/',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|regex:/^[0-9]{10,15}$/',
            'previous_school' => 'required|string|max:255',
            'desired_major' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nik.regex' => 'NIK harus 16 digit angka',
            'phone.regex' => 'Nomor telepon harus 10-15 digit angka',
            'parent_phone.regex' => 'Nomor telepon orang tua harus 10-15 digit angka',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini',
            'email.unique' => 'Email sudah terdaftar',
            'nik.unique' => 'NIK sudah terdaftar',
        ];
    }
}

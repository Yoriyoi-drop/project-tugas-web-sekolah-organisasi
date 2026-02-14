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
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s\.\-]+$/',
            'nik' => 'required|string|unique:student_registrations,nik|regex:/^[0-9]{16}$/',
            'email' => 'required|email|unique:student_registrations,email|unique:users,email',
            'birth_date' => 'required|date|before:today',
            'birth_place' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'address' => 'required|string|max:1000',
            'phone' => 'required|string|regex:/^[0-9\-\+\(\)\s]+$/|min:10|max:15',
            'parent_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\.\-]+$/',
            'parent_phone' => 'required|string|regex:/^[0-9\-\+\(\)\s]+$/|min:10|max:15',
            'previous_school' => 'required|string|max:255',
            'desired_major' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'Nama hanya boleh berisi huruf, spasi, titik, dan tanda hubung',
            'nik.regex' => 'NIK harus 16 digit angka',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka, tanda hubung, tanda kurung, dan spasi',
            'phone.min' => 'Nomor telepon minimal 10 digit',
            'phone.max' => 'Nomor telepon maksimal 15 digit',
            'parent_phone.regex' => 'Nomor telepon orang tua hanya boleh berisi angka, tanda hubung, tanda kurung, dan spasi',
            'parent_phone.min' => 'Nomor telepon orang tua minimal 10 digit',
            'parent_phone.max' => 'Nomor telepon orang tua maksimal 15 digit',
            'parent_name.regex' => 'Nama orang tua hanya boleh berisi huruf, spasi, titik, dan tanda hubung',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini',
            'email.unique' => 'Email sudah terdaftar',
            'nik.unique' => 'NIK sudah terdaftar',
            'address.max' => 'Alamat terlalu panjang, maksimal 1000 karakter',
        ];
    }
}

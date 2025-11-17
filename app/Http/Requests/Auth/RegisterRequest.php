<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Rules\EmailDomainAllowed;
use App\Rules\NikFormat;
use App\Rules\NisFormat;

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
            'email' => ['required','string','email','max:255','unique:users,email'],
            'password' => 'required|string|min:6|confirmed',
        ];

        // Add nik/nis rules only if columns exist to prevent SQL errors
        if (Schema::hasColumn('users', 'nik')) {
            $rules['nik'] = ['required','string', new NikFormat()];
        } else {
            $rules['nik'] = 'nullable|string';
        }

        if (Schema::hasColumn('users', 'nis')) {
            $rules['nis'] = ['required','string', new NisFormat()];
        } else {
            $rules['nis'] = 'nullable|string';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.string' => 'Email harus berupa teks.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.string' => 'NIK harus berupa teks.',
            'nis.required' => 'NIS wajib diisi.',
            'nis.string' => 'NIS harus berupa teks.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nik' => $this->normalizeId($this->input('nik')),
            'nis' => $this->normalizeId($this->input('nis')),
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            // Enforce uniqueness by hash since the stored values are encrypted
            $nik = $this->input('nik');
            $nis = $this->input('nis');

            if ($nik && Schema::hasColumn('users', 'nik_hash')) {
                $nikHash = hash('sha256', $nik);
                $existsNik = DB::table('users')->where('nik_hash', $nikHash)->exists();
                if ($existsNik) {
                    $v->errors()->add('nik', 'NIK sudah terdaftar.');
                }
            }

            if ($nis && Schema::hasColumn('users', 'nis_hash')) {
                $nisHash = hash('sha256', $nis);
                $existsNis = DB::table('users')->where('nis_hash', $nisHash)->exists();
                if ($existsNis) {
                    $v->errors()->add('nis', 'NIS sudah terdaftar.');
                }
            }
        });
    }

    private function normalizeId(?string $value): string
    {
        if (!$value) return '';
        $value = trim($value);
        return preg_replace('/[^A-Za-z0-9]/', '', $value);
    }
}

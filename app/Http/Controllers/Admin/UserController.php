<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Rules\EmailDomainAllowed;
use App\Rules\NikFormat;
use App\Rules\NisFormat;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('id')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        // Don't perform runtime schema checks. 
        // We assume the application is deployed with the correct migrations.
        // If feature toggles are needed, use config or a proper feature flag service.
        $hasNik = true; 
        $hasNis = true;
        return view('admin.users.create', compact('hasNik', 'hasNis'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required','string','email','max:255', 'unique:users,email'],
            'password' => 'required|string|min:6',
            'nik' => ['nullable','string', new NikFormat()], 
            'nis' => ['nullable','string', new NisFormat()],
        ];

        // Normalize inputs first so validation runs on clean data
        $request->merge([
            'nik' => self::normalizeId($request->input('nik')),
            'nis' => self::normalizeId($request->input('nis')),
        ]);
        
        $data = $request->validate($rules);

        // Note: The User model has Mutators (setNikAttribute/setNisAttribute)
        // that automatically encrypt the value AND generate the hash (nik_hash/nis_hash).
        // We do typically need to check uniqueness of the HASH manually if we can't use standard validators.
        // But doing it here duplicates logic. 
        // A better approach for the future is to create a custom Rule `UniqueEncrypted`
        // For now, we keep the uniqueness check but simplify it.
        
        if (!empty($data['nik'])) {
            // We use the same normalization logic as the model to check uniqueness
            $nikHash = hash('sha256', $data['nik']);
            if (User::where('nik_hash', $nikHash)->exists()) {
               return back()->withErrors(['nik' => 'NIK sudah terdaftar.'])->withInput();
            }
        }
        
        if (!empty($data['nis'])) {
             $nisHash = hash('sha256', $data['nis']);
             if (User::where('nis_hash', $nisHash)->exists()) {
                return back()->withErrors(['nis' => 'NIS sudah terdaftar.'])->withInput();
             }
        }

        $payload = [
            'name' => strip_tags($data['name']),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'nik' => $data['nik'] ?? null,
            'nis' => $data['nis'] ?? null,
        ];

        User::create($payload);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    private static function normalizeId(?string $value): string
    {
        if (!$value) return '';
        $value = trim($value);
        return preg_replace('/[^A-Za-z0-9]/', '', $value);
    }
}

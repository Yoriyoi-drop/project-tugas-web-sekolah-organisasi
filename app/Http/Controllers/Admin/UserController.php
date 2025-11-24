<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Rules\EmailDomainAllowed;
use App\Rules\NikFormat;
use App\Rules\NisFormat;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('id')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $hasNik = Schema::hasColumn('users', 'nik');
        $hasNis = Schema::hasColumn('users', 'nis');
        return view('admin.users.create', compact('hasNik', 'hasNis'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required','string','email','max:255','unique:users,email'],
            'password' => 'required|string|min:6',
        ];

        if (Schema::hasColumn('users', 'nik')) {
            $rules['nik'] = ['nullable','string', new NikFormat()];
        }
        if (Schema::hasColumn('users', 'nis')) {
            $rules['nis'] = ['nullable','string', new NisFormat()];
        }

        // Normalize before validation
        $request->merge([
            'nik' => self::normalizeId($request->input('nik')),
            'nis' => self::normalizeId($request->input('nis')),
        ]);

        $data = $request->validate($rules);

        // Enforce uniqueness via hashes because nik/nis are encrypted at rest
        if (!empty($data['nik']) && Schema::hasColumn('users', 'nik_hash')) {
            $nikHash = hash('sha256', $data['nik']);
            if (DB::table('users')->where('nik_hash', $nikHash)->exists()) {
                return back()->withErrors(['nik' => 'NIK sudah terdaftar.'])->withInput();
            }
        }
        if (!empty($data['nis']) && Schema::hasColumn('users', 'nis_hash')) {
            $nisHash = hash('sha256', $data['nis']);
            if (DB::table('users')->where('nis_hash', $nisHash)->exists()) {
                return back()->withErrors(['nis' => 'NIS sudah terdaftar.'])->withInput();
            }
        }

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ];

        if (isset($data['nik'])) { $payload['nik'] = $data['nik']; }
        if (isset($data['nis'])) { $payload['nis'] = $data['nis']; }

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

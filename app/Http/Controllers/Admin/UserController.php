<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

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
            'email' => 'required|string|email:rfc,dns|ends_with:gmail.com|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ];

        if (Schema::hasColumn('users', 'nik')) {
            $rules['nik'] = 'required|string|digits_between:8,20|unique:users,nik';
        }
        if (Schema::hasColumn('users', 'nis')) {
            $rules['nis'] = 'required|string|digits_between:5,20|unique:users,nis';
        }

        $data = $request->validate($rules);

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
}

<?php

namespace App\Http\Controllers;

use App\Models\StudentRegistration;
use App\Models\User;
use App\Http\Requests\StudentRegistrationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class StudentRegistrationController extends Controller
{
    public function index()
    {
        return view('pages.student-registration.index');
    }

    public function create()
    {
        return view('pages.student-registration.form');
    }

    public function store(StudentRegistrationRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            
            // Basic sanitization
            $validated['name'] = strip_tags($validated['name']);
            $validated['birth_place'] = strip_tags($validated['birth_place']);
            $validated['address'] = strip_tags($validated['address']);
            $validated['parent_name'] = strip_tags($validated['parent_name']);
            $validated['previous_school'] = strip_tags($validated['previous_school']);
            $validated['desired_major'] = strip_tags($validated['desired_major'] ?? '');
            
            $validated['status'] = 'pending';

            $registration = StudentRegistration::create($validated);

            DB::commit();

            return redirect()->route('student-registration.success')
                ->with('registration_name', $registration->name);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses pendaftaran Anda. Silakan coba lagi.');
        }
    }

    public function success()
    {
        if (!session('registration_name')) {
            return redirect()->route('student-registration.create');
        }
        return view('pages.student-registration.success');
    }

    public function adminIndex()
    {
        $registrations = StudentRegistration::with(['approvedBy', 'rejectedBy'])
            ->latest()
            ->paginate(10);

        return view('pages.admin.student-registration.index', compact('registrations'));
    }

    public function show(StudentRegistration $registration)
    {
        $registration->load(['approvedBy', 'rejectedBy']);
        return view('pages.admin.student-registration.show', compact('registration'));
    }

    public function approve(Request $request, StudentRegistration $registration)
    {
        if ($registration->status !== 'pending') {
            return back()->with('error', 'Pendaftaran tidak dapat disetujui.');
        }

        try {
            DB::beginTransaction();

            // Create user account
            $password = Str::random(8);
            $user = User::create([
                'name' => $registration->name,
                'email' => $registration->email,
                'password' => Hash::make($password),
                'nik' => $registration->nik,
                'birth_date' => $registration->birth_date,
                'gender' => $registration->gender,
                'address' => $registration->address,
                'phone' => $registration->phone,
                'is_active' => true,
            ]);

            // Assign "calon siswa" role
            $calonSiswaRole = Role::firstOrCreate(['name' => 'calon siswa', 'guard_name' => 'web']);
            $user->assignRole($calonSiswaRole);

            // Approve registration
            $registration->approve(auth()->user(), $request->notes);

            DB::commit();

            return back()->with('success', "Pendaftaran disetujui. Akun telah dibuat dengan password: {$password}");
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyetujui pendaftaran: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, StudentRegistration $registration)
    {
        if ($registration->status !== 'pending') {
            return back()->with('error', 'Pendaftaran tidak dapat ditolak.');
        }

        $registration->reject(auth()->user(), $request->notes);

        return back()->with('success', 'Pendaftaran ditolak.');
    }
}

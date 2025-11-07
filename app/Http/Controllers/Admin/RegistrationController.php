<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function index()
    {
        $registrations = Registration::with('organization')
                                   ->latest()
                                   ->paginate(15);
        return view('admin.registrations.index', compact('registrations'));
    }

    public function show(Registration $registration)
    {
        $registration->load('organization');
        return view('admin.registrations.show', compact('registration'));
    }

    public function updateStatus(Request $request, Registration $registration)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        DB::transaction(function () use ($request, $registration) {
            $registration->update(['status' => $request->status]);

            if ($request->status === 'approved') {
                // Resolve student record robustly
                $student = null;

                if (!empty($registration->email)) {
                    $student = Student::where('email', $registration->email)->first();
                }

                if (!$student) {
                    $student = Student::where('name', $registration->name)
                        ->where('phone', $registration->phone)
                        ->first();
                }

                if (!$student) {
                    $student = Student::create([
                        'name' => $registration->name,
                        'nis' => Student::generateNis(),
                        'email' => $registration->email,
                        'phone' => $registration->phone,
                        'class' => $registration->class,
                        'address' => $registration->address,
                    ]);
                }

                // Attach to organization as member (many-to-many)
                $registration->organization->students()->syncWithoutDetaching([$student->id]);
            }
        });

        return redirect()->route('admin.registrations.index')
                        ->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    public function destroy(Registration $registration)
    {
        $registration->delete();
        return redirect()->route('admin.registrations.index')
                        ->with('success', 'Data pendaftaran berhasil dihapus.');
    }
}

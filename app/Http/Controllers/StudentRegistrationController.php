<?php

namespace App\Http\Controllers;

use App\Models\StudentRegistration;
use App\Models\User;
use App\Http\Requests\StudentRegistrationRequest;
use App\Notifications\StudentRegistrationStatusNotification;
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

    public function adminIndex(Request $request)
    {
        $query = StudentRegistration::with(['approvedBy', 'rejectedBy']);

        // Apply filters based on request parameters
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('nik', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('parent_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('previous_school', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $registrations = $query->latest()->paginate(10);
        $registrations->appends($request->query());

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

            // Generate a stronger password
            $password = $this->generateStrongPassword();
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

            // Send notification email to the student
            $registration->notify(new StudentRegistrationStatusNotification(
                $registration, 
                'approved', 
                $password
            ));

            DB::commit();

            return back()->with('success', "Pendaftaran disetujui. Akun telah dibuat dan notifikasi telah dikirim ke email: {$registration->email}");
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

        // Send notification email to the student
        $registration->notify(new StudentRegistrationStatusNotification(
            $registration, 
            'rejected', 
            $request->notes
        ));

        return back()->with('success', 'Pendaftaran ditolak dan notifikasi telah dikirim ke email: ' . $registration->email);
    }

    /**
     * Generate a strong password with mixed case letters, numbers, and symbols
     */
    private function generateStrongPassword($length = 12)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';
        $password = '';
        
        // Ensure at least one character from each category
        $password .= substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 1);
        $password .= substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 1);
        $password .= substr(str_shuffle('0123456789'), 0, 1);
        $password .= substr(str_shuffle('!@#$%^&*()_+-=[]{}|;:,.<>?'), 0, 1);
        
        // Fill the rest randomly
        for ($i = strlen($password); $i < $length; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }
        
        // Shuffle the password to randomize character positions
        return str_shuffle($password);
    }

    /**
     * Export student registrations to CSV or Excel
     */
    public function export(Request $request)
    {
        $format = $request->query('format', 'csv');
        $registrations = StudentRegistration::with(['approvedBy', 'rejectedBy'])->get();

        $headers = [
            'ID',
            'Nama',
            'NIK',
            'Email',
            'Tanggal Lahir',
            'Tempat Lahir',
            'Jenis Kelamin',
            'Alamat',
            'Telepon',
            'Nama Orang Tua',
            'Telepon Orang Tua',
            'Sekolah Asal',
            'Jurusan Diinginkan',
            'Status',
            'Catatan',
            'Disetujui Pada',
            'Ditolak Pada',
            'Disetujui Oleh',
            'Ditolak Oleh',
            'Dibuat Pada',
            'Diperbarui Pada'
        ];

        $rows = [];
        foreach ($registrations as $registration) {
            $rows[] = [
                $registration->id,
                $registration->name,
                $registration->nik,
                $registration->email,
                $registration->birth_date,
                $registration->birth_place,
                $registration->gender === 'male' ? 'Laki-laki' : 'Perempuan',
                $registration->address,
                $registration->phone,
                $registration->parent_name,
                $registration->parent_phone,
                $registration->previous_school,
                $registration->desired_major ?? '-',
                $registration->formatted_status,
                $registration->notes ?? '-',
                $registration->approved_at ? $registration->approved_at->format('Y-m-d H:i:s') : '-',
                $registration->rejected_at ? $registration->rejected_at->format('Y-m-d H:i:s') : '-',
                $registration->approvedBy ? $registration->approvedBy->name : '-',
                $registration->rejectedBy ? $registration->rejectedBy->name : '-',
                $registration->created_at->format('Y-m-d H:i:s'),
                $registration->updated_at->format('Y-m-d H:i:s'),
            ];
        }

        if ($format === 'excel') {
            return $this->exportToExcel($headers, $rows);
        } else {
            return $this->exportToCSV($headers, $rows);
        }
    }

    /**
     * Export data to CSV format
     */
    private function exportToCSV($headers, $rows)
    {
        $filename = 'pendaftaran_siswa_' . date('Y-m-d_H-i-s') . '.csv';
        
        $response = response()->streamDownload(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 to ensure proper encoding in Excel
            fwrite($handle, "\xEF\xBB\xBF");
            
            fputcsv($handle, $headers);
            
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            
            fclose($handle);
        }, $filename);

        $response->headers->set('Content-Type', 'text/csv');
        
        return $response;
    }

    /**
     * Export data to Excel format
     */
    private function exportToExcel($headers, $rows)
    {
        // Using basic CSV with Excel-compatible formatting
        $filename = 'pendaftaran_siswa_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        $response = response()->streamDownload(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 to ensure proper encoding in Excel
            fwrite($handle, "\xEF\xBB\xBF");
            
            fputcsv($handle, $headers);
            
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            
            fclose($handle);
        }, $filename);

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        return $response;
    }
}

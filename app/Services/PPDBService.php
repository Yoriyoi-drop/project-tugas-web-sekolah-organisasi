<?php
namespace App\Services;

use App\Models\PPDB;
use App\Models\User;
use App\Mail\PPDBConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PPDBService
{
    public function registerCandidate(array $data)
    {
        // Validate input data
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:ppdb,email|unique:users,email',
            'phone' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'previous_school' => 'required|string|max:255',
            'desired_major' => 'nullable|string|max:255',
            'nisn' => 'required|string|max:20',
            'nik' => 'required|string|max:20|unique:ppdb,nik|unique:users,nik',
            'registration_number' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        // Generate registration number if not provided
        $registrationNumber = $data['registration_number'] ?? $this->generateRegistrationNumber();

        // Create PPDB record
        $ppdb = PPDB::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'birth_date' => $data['birth_date'],
            'birth_place' => $data['birth_place'],
            'gender' => $data['gender'],
            'address' => $data['address'],
            'parent_name' => $data['parent_name'],
            'parent_phone' => $data['parent_phone'],
            'previous_school' => $data['previous_school'],
            'desired_major' => $data['desired_major'] ?? null,
            'nisn' => $data['nisn'],
            'nik' => $data['nik'],
            'registration_number' => $registrationNumber,
            'status' => 'pending',
            'registration_date' => now(),
        ]);

        // Send confirmation email
        Mail::to($ppdb->email)->send(new PPDBConfirmation($ppdb));

        return [
            'success' => true,
            'message' => 'Pendaftaran PPDB berhasil. Silakan cek email Anda untuk konfirmasi.',
            'ppdb' => $ppdb
        ];
    }

    public function approveRegistration($id)
    {
        $ppdb = PPDB::findOrFail($id);

        if ($ppdb->status !== 'pending') {
            return [
                'success' => false,
                'message' => 'Pendaftaran tidak dalam status pending'
            ];
        }

        // Update status
        $ppdb->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id() ?? 1
        ]);

        // Create user account
        $user = User::create([
            'name' => $ppdb->name,
            'email' => $ppdb->email,
            'password' => bcrypt(Str::random(12)),
            'phone' => $ppdb->phone,
            'birth_date' => $ppdb->birth_date,
            'gender' => $ppdb->gender,
            'address' => $ppdb->address,
            'nik' => $ppdb->nik,
            'is_active' => true,
        ]);

        // Assign role
        $user->assignRole('calon siswa');

        return [
            'success' => true,
            'message' => 'Pendaftaran berhasil disetujui dan akun pengguna telah dibuat',
            'ppdb' => $ppdb,
            'user' => $user
        ];
    }

    public function rejectRegistration($id, $reason = null)
    {
        $ppdb = PPDB::findOrFail($id);

        if ($ppdb->status !== 'pending') {
            return [
                'success' => false,
                'message' => 'Pendaftaran tidak dalam status pending'
            ];
        }

        $ppdb->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id() ?? 1,
            'rejection_reason' => $reason
        ]);

        return [
            'success' => true,
            'message' => 'Pendaftaran berhasil ditolak',
            'ppdb' => $ppdb
        ];
    }

    public function getRegistrations($status = null, $perPage = 15)
    {
        $query = PPDB::orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }

    public function searchRegistrations($keyword, $status = null)
    {
        $query = PPDB::query();

        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('email', 'LIKE', "%{$keyword}%")
                  ->orWhere('phone', 'LIKE', "%{$keyword}%")
                  ->orWhere('registration_number', 'LIKE', "%{$keyword}%")
                  ->orWhere('previous_school', 'LIKE', "%{$keyword}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getStatistics()
    {
        return [
            'total' => PPDB::count(),
            'pending' => PPDB::where('status', 'pending')->count(),
            'approved' => PPDB::where('status', 'approved')->count(),
            'rejected' => PPDB::where('status', 'rejected')->count(),
        ];
    }

    public function getRegistrationByPeriod($startDate, $endDate)
    {
        return PPDB::whereBetween('registration_date', [$startDate, $endDate])
                  ->orderBy('registration_date', 'asc')
                  ->get();
    }

    private function generateRegistrationNumber()
    {
        $year = date('Y');
        $month = date('m');
        $day = date('d');

        // Count registrations for today to create sequential number
        $todayCount = PPDB::whereDate('created_at', now())->count() + 1;

        return "PPDB/{$year}{$month}{$day}/" . str_pad((string)$todayCount, 4, '0', STR_PAD_LEFT);
    }

    public function exportRegistrations($status = null)
    {
        $query = PPDB::select([
            'name', 'email', 'phone', 'birth_date', 'birth_place', 'gender',
            'address', 'parent_name', 'parent_phone', 'previous_school',
            'desired_major', 'nisn', 'nik', 'registration_number', 'status',
            'registration_date', 'created_at'
        ]);

        if ($status) {
            $query->where('status', $status);
        }

        $registrations = $query->get();

        // Convert to array format for export
        $data = [];
        foreach ($registrations as $reg) {
            $data[] = [
                'Nama' => $reg->name,
                'Email' => $reg->email,
                'Telepon' => $reg->phone,
                'Tanggal Lahir' => $reg->birth_date,
                'Tempat Lahir' => $reg->birth_place,
                'Jenis Kelamin' => $reg->gender,
                'Alamat' => $reg->address,
                'Nama Orang Tua' => $reg->parent_name,
                'Telepon Orang Tua' => $reg->parent_phone,
                'Sekolah Asal' => $reg->previous_school,
                'Jurusan Tujuan' => $reg->desired_major,
                'NIK' => $reg->nik,
                'Status' => $reg->status,
                'Dibuat Pada' => $reg->created_at,
            ];
        }

        return $data;
    }
}

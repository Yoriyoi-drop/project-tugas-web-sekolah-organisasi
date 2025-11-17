<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StudentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $students = Student::when($request->search, function($query) use ($request) {
                         $query->where('name', 'LIKE', '%' . $request->search . '%')
                               ->orWhere('nis', 'LIKE', '%' . $request->search . '%')
                               ->orWhere('email', 'LIKE', '%' . $request->search . '%');
                     })
                     ->latest()
                     ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    public function show($id): JsonResponse
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $student
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|max:20|unique:students,nis',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'class' => 'nullable|string|max:50',
        ]);

        $student = Student::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $student,
            'message' => 'Student berhasil ditambahkan'
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|max:20|unique:students,nis,' . $id,
            'email' => 'required|email|unique:students,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'class' => 'nullable|string|max:50',
        ]);

        $student->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $student,
            'message' => 'Student berhasil diperbarui'
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student tidak ditemukan'
            ], 404);
        }

        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student berhasil dihapus'
        ]);
    }
}

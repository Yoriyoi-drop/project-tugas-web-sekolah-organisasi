<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\StudentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    protected StudentRepositoryInterface $studentRepository;

    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['name', 'nis', 'email', 'class']);
            $perPage = $request->get('per_page', 15);
            
            $students = $this->studentRepository->getStudentsWithFilters($filters, $perPage);

            return response()->json([
                'success' => true,
                'data' => $students->items(),
                'pagination' => [
                    'current_page' => $students->currentPage(),
                    'last_page' => $students->lastPage(),
                    'per_page' => $students->perPage(),
                    'total' => $students->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve students',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'nis' => 'required|string|max:20|unique:students,nis',
                'email' => 'required|email|unique:students,email',
                'phone' => 'nullable|string|max:20',
                'class' => 'nullable|string|max:50',
            ]);

            $student = $this->studentRepository->createStudent($validated);

            return response()->json([
                'success' => true,
                'data' => $student,
                'message' => 'Student berhasil ditambahkan'
            ], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create student',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $student = $this->studentRepository->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $student
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'nis' => 'required|string|max:20|unique:students,nis,' . $id,
                'email' => 'required|email|unique:students,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'class' => 'nullable|string|max:50',
            ]);

            $student = $this->studentRepository->updateStudent($id, $validated);

            return response()->json([
                'success' => true,
                'data' => $student,
                'message' => 'Student berhasil diperbarui'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update student',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->studentRepository->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Student berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q');
            
            if (!$query) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search query is required'
                ], Response::HTTP_BAD_REQUEST);
            }

            $students = $this->studentRepository->search($query);

            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreStudentRequest;
use App\Http\Requests\Api\UpdateStudentRequest;
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
                ],
                'meta' => [
                    'version' => 'v1',
                    'timestamp' => now()->toISOString()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve students',
                'error' => $e->getMessage(),
                'meta' => [
                    'version' => 'v1',
                    'timestamp' => now()->toISOString()
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreStudentRequest $request): JsonResponse
    {
        try {
            $student = $this->studentRepository->createStudent($request->getSanitized());

            return response()->json([
                'success' => true,
                'data' => $student,
                'message' => 'Student berhasil ditambahkan',
                'meta' => [
                    'version' => 'v1',
                    'timestamp' => now()->toISOString()
                ]
            ], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'meta' => [
                    'version' => 'v1',
                    'timestamp' => now()->toISOString()
                ]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create student',
                'error' => $e->getMessage(),
                'meta' => [
                    'version' => 'v1',
                    'timestamp' => now()->toISOString()
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $student = $this->studentRepository->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $student,
                'meta' => [
                    'version' => 'v1',
                    'timestamp' => now()->toISOString()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student tidak ditemukan',
                'meta' => [
                    'version' => 'v1',
                    'timestamp' => now()->toISOString()
                ]
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function update(UpdateStudentRequest $request, $id): JsonResponse
    {
        try {
            $student = $this->studentRepository->updateStudent($id, $request->getSanitized());

            return response()->json([
                'success' => true,
                'data' => $student,
                'message' => 'Student berhasil diperbarui',
                'meta' => [
                    'version' => 'v1',
                    'timestamp' => now()->toISOString()
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'meta' => [
                    'version' => 'v1',
                    'timestamp' => now()->toISOString()
                ]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update student',
                'error' => $e->getMessage(),
                'meta' => [
                    'version' => 'v1',
                    'timestamp' => now()->toISOString()
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->studentRepository->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Student berhasil dihapus',
                'meta' => [
                    'version' => 'v1',
                    'timestamp' => now()->toISOString()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student tidak ditemukan',
                'meta' => [
                    'version' => 'v1',
                    'timestamp' => now()->toISOString()
                ]
            ], Response::HTTP_NOT_FOUND);
        }
    }
}

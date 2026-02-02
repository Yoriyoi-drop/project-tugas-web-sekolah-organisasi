<?php

namespace App\Http\Controllers\Api\V2;

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
                'links' => [
                    'self' => $request->fullUrl(),
                    'first' => $students->url(1),
                    'last' => $students->url($students->lastPage()),
                    'prev' => $students->previousPageUrl(),
                    'next' => $students->nextPageUrl(),
                ],
                'meta' => [
                    'current_page' => $students->currentPage(),
                    'last_page' => $students->lastPage(),
                    'per_page' => $students->perPage(),
                    'total' => $students->total(),
                    'version' => 'v2',
                    'timestamp' => now()->toISOString(),
                    'api_info' => [
                        'version' => 'v2',
                        'deprecated' => false,
                        'sunset_date' => null,
                        'features' => [
                            'enhanced_pagination',
                            'improved_error_handling',
                            'extended_metadata'
                        ]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'STUDENT_RETRIEVAL_ERROR',
                    'message' => 'Failed to retrieve students',
                    'details' => $e->getMessage()
                ],
                'meta' => [
                    'version' => 'v2',
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
                    'version' => 'v2',
                    'timestamp' => now()->toISOString(),
                    'created_at' => $student->created_at->toISOString()
                ]
            ], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Validation failed',
                    'details' => $e->errors()
                ],
                'meta' => [
                    'version' => 'v2',
                    'timestamp' => now()->toISOString()
                ]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'STUDENT_CREATION_ERROR',
                    'message' => 'Failed to create student',
                    'details' => $e->getMessage()
                ],
                'meta' => [
                    'version' => 'v2',
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
                    'version' => 'v2',
                    'timestamp' => now()->toISOString(),
                    'last_modified' => $student->updated_at->toISOString()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'STUDENT_NOT_FOUND',
                    'message' => 'Student tidak ditemukan',
                    'details' => ['id' => $id]
                ],
                'meta' => [
                    'version' => 'v2',
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
                    'version' => 'v2',
                    'timestamp' => now()->toISOString(),
                    'updated_at' => $student->updated_at->toISOString()
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Validation failed',
                    'details' => $e->errors()
                ],
                'meta' => [
                    'version' => 'v2',
                    'timestamp' => now()->toISOString()
                ]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'STUDENT_UPDATE_ERROR',
                    'message' => 'Failed to update student',
                    'details' => $e->getMessage()
                ],
                'meta' => [
                    'version' => 'v2',
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
                    'version' => 'v2',
                    'timestamp' => now()->toISOString(),
                    'deleted_id' => $id
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'STUDENT_NOT_FOUND',
                    'message' => 'Student tidak ditemukan',
                    'details' => ['id' => $id]
                ],
                'meta' => [
                    'version' => 'v2',
                    'timestamp' => now()->toISOString()
                ]
            ], Response::HTTP_NOT_FOUND);
        }
    }
}

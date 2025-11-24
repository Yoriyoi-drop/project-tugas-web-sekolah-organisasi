<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class ProfileDataController extends Controller
{
    /**
     * Show student profile by id
     */
    public function show($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        // Authorization check: users can only view their own data unless they're admin
        $user = auth()->user();
        if (!$user->is_admin && $student->user_id && $student->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($student);
    }
}

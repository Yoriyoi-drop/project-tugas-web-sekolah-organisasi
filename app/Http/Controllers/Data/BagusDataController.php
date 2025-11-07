<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Student;

class BagusDataController extends Controller
{
    /**
     * Return a simple "bagus" summary: top recent students (example)
     */
    public function index()
    {
        $students = Student::orderBy('created_at', 'desc')->limit(10)->get();
        return response()->json([
            'count' => $students->count(),
            'recent' => $students,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::latest()->paginate(10);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:100',
            'qualification' => 'required|string',
        ]);

        // Ensure a unique NIP is provided for the teacher (migration requires it)
        $validated['nip'] = Teacher::generateNip();

        Teacher::create($validated);
        return redirect()->route('admin.teachers.index')->with('success', 'Teacher created successfully');
    }

    public function show(Teacher $teacher)
    {
        return view('admin.teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:100',
            'qualification' => 'required|string',
        ]);

        $teacher->update($validated);
        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return redirect()->route('admin.teachers.index')->with('success', 'Teacher deleted successfully');
    }
}

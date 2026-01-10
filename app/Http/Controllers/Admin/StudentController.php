<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('organizations')->latest()->paginate(10);
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:students',
            'email' => 'required|email|unique:students',
            'phone' => 'required|string|max:20',
            'class' => 'required|string|max:50',
            'address' => 'required|string',
        ]);

        $validated['name'] = strip_tags($validated['name']);
        $validated['address'] = strip_tags($validated['address']);

        Student::create($validated);
        return redirect()->route('admin.students.index')->with('success', 'Student created successfully');
    }

    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nis' => ['required', 'string', \Illuminate\Validation\Rule::unique('students', 'nis')->ignore($student->id)],
            'email' => ['required', 'email', \Illuminate\Validation\Rule::unique('students', 'email')->ignore($student->id)],
            'phone' => 'required|string|max:20',
            'class' => 'required|string|max:50',
            'address' => 'required|string',
        ]);

        $validated['name'] = strip_tags($validated['name']);
        $validated['address'] = strip_tags($validated['address']);

        $student->update($validated);
        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully');
    }
}

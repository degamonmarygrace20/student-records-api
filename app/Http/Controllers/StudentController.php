<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return response()->json(Student::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'course' => 'required|string|max:255',
            'year_level' => 'required|integer|min:1|max:6',
        ]);

        $student = Student::create($validated);
        return response()->json($student, 201);
    }

    public function show(Student $student)
    {
        return response()->json($student, 200);
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' .$student->id,
            'course' => 'required|string|max:255',
            'year_level' => 'required|integer|min:1|max:6',
        ]);

        $student->update($validated);
        return response()->json($student, 200);
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json(['message' => 'Student Deleted'], 200);
    }
}

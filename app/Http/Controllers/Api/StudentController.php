<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return response()->json(Student::all());
    }

    public function store(Request $request)
    {
        $student = Student::create($request->all());

        return response()->json([
            'message' => 'Student created',
            'data' => $student
        ]);
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);

        return response()->json($student);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->update($request->all());

        return response()->json([
            'message' => 'Student updated',
            'data' => $student
        ]);
    }

    public function destroy($id)
    {
        Student::destroy($id);

        return response()->json([
            'message' => 'Student deleted'
        ]);
    }
}

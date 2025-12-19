<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        return response()->json(Teacher::all());
    }

    public function store(Request $request)
    {
        $teacher = Teacher::create($request->all());

        return response()->json([
            'message' => 'Teacher created',
            'data' => $teacher
        ]);
    }

    public function show($id)
    {
        return response()->json(Teacher::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->update($request->all());

        return response()->json([
            'message' => 'Teacher updated',
            'data' => $teacher
        ]);
    }

    public function destroy($id)
    {
        Teacher::destroy($id);

        return response()->json(['message' => 'Teacher deleted']);
    }
}

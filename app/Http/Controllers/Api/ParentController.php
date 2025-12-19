<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\SppPayment;
use App\Models\PaymentGuide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ParentController extends Controller
{
    // GET: /parent/profile
    public function profile()
    {
        $user = Auth::user();

        // pastikan role = parent
        if ($user->role !== 'parent') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    // PUT: /parent/profile/update
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'parent') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
        ]);

        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }
        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated',
            'data' => $user
        ]);
    }

    // GET: /parent/students
    public function myChildren()
    {
        $user = Auth::user();

        if ($user->role !== 'parent') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $children = Student::where('parent_id', $user->id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $children
        ]);
    }

    // GET: /parent/students/{id}
    public function childDetail($id)
    {
        $user = Auth::user();

        if ($user->role !== 'parent') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $student = Student::where('id', $id)
            ->where('parent_id', $user->id)
            ->first();

        if (!$student) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $student
        ]);
    }

    // 🔹 1. Parent lihat jadwal anak
    public function childSchedule($id)
    {
        $user = Auth::user();
        if ($user->role !== 'parent') return response()->json(['message' => 'Unauthorized'], 403);

        $student = Student::where('id', $id)->where('parent_id', $user->id)->first();
        if (!$student) return response()->json(['message' => 'Not found'], 404);

        $schedule = Schedule::where('class_id', $student->class_id)
            ->with('subject')
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return response()->json(['status' => 'success', 'data' => $schedule]);
    }

    //  Panduan pembayaran SPP (isi dari admin)
    public function paymentGuides()
    {
        $guides = PaymentGuide::all();
        return response()->json(['status' => 'success', 'data' => $guides]);
    }

    //  Cek status pembayaran SPP anak
    public function childSppStatus($id)
    {
        $user = Auth::user();

        $student = Student::where('id', $id)->where('parent_id', $user->id)->first();
        if (!$student) return response()->json(['message' => 'Not found'], 404);

        $payments = SppPayment::where('student_id', $id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return response()->json(['status' => 'success', 'data' => $payments]);
    }
}

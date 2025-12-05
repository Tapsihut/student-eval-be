<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subject;

class ProspectusController extends Controller
{
    public function getStudentSubjects($studentId)
    {
        $student = User::with('course')->find($studentId);

        if (!$student || !$student->course) {
            return response()->json(['message' => 'Student or course not found'], 404);
        }

        $courseCode = $student->course->code;

        $subjects = Subject::whereHas('course', function($query) use ($courseCode) {
            $query->where('code', $courseCode);
        })
        ->orderBy('year_level')
        ->orderBy('semester')
        ->get();

        return response()->json([
            'student' => $student,
            'subjects' => $subjects
        ]);
    }

    public function getStudent($studentId)
    {
        $student = User::with('course')->find($studentId);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }
        return response()->json(['student' => $student]);
    }
}

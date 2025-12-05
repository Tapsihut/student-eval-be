<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;

class StudentController extends Controller
{
    public function getSubjects($id)
    {
        // Find student with grades
        $student = User::with('grades')->find($id);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        // Match student's course code with courses table
        $course = Course::where('code', $student->course)->first();
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        // Get the curriculum for that course
        $curriculum = $course->curriculums()->first();
        if (!$curriculum) {
            return response()->json(['message' => 'Curriculum not found'], 404);
        }

        // Get subjects with prerequisites
        $subjects = $curriculum->subjects()->with('prerequisites')->get();

        // Attach grade and status from grades table
        $subjects = $subjects->map(function($subject) use ($student) {
            $gradeRecord = $student->grades
                ->where('subject_id', $subject->id)
                ->first();

            if ($gradeRecord) {
                $subject->grade = $gradeRecord->type === 'credited' 
                    ? $gradeRecord->grade 
                    : null;
                $subject->status = $gradeRecord->status; // e.g., "Enrolled"
            } else {
                $subject->grade = null;
                $subject->status = null;
            }

            return $subject;
        });

        return response()->json([
            'curriculum' => [
                'id' => $curriculum->id,
                'code' => $curriculum->code,
                'name' => $course->name,
            ],
            'subjects' => $subjects
        ]);
    }

    public function index()
{
    $students = \App\Models\User::where('role', 'student')->get(); // or your student model
    return response()->json($students);
}

}

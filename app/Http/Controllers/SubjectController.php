<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class SubjectController extends Controller
{
    /**
     * GET /api/subjects
     * Fetch all subjects
     */
    public function index()
    {
        try {
            $subjects = Subject::with(['curriculum.course', 'prerequisites'])->get();
            return response()->json($subjects);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error fetching subjects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/subjects
     * Create a new subject
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'curriculum_id' => 'required|exists:curriculums,id',
            'code'          => 'required|unique:subjects,code',
            'name'          => 'required|string',
            'units'         => 'integer|min:1',
            'semester'      => 'nullable|string',
            'year_level'    => 'nullable|integer',
            'prerequisite_ids' => 'array',
        ]);

        $subject = Subject::create($validated);

        if (!empty($validated['prerequisite_ids'])) {
            $subject->prerequisites()->attach($validated['prerequisite_ids']);
        }

        return response()->json($subject->load('prerequisites'), 201);
    }

    /**
     * GET /api/subjects/{id}
     * Show single subject
     */
    public function show($id)
    {
        $subject = Subject::with(['curriculum.course', 'prerequisites'])->findOrFail($id);
        return response()->json($subject);
    }

    /**
     * PUT /api/subjects/{id}
     * Update a subject
     */
    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'curriculum_id' => 'required|exists:curriculums,id',
            'code'          => 'required|unique:subjects,code,' . $id,
            'name'          => 'required|string',
            'units'         => 'integer|min:1',
            'semester'      => 'nullable|string',
            'year_level'    => 'nullable|integer',
            'prerequisite_ids' => 'array',
        ]);

        $subject->update($validated);

        if (isset($validated['prerequisite_ids'])) {
            $subject->prerequisites()->sync($validated['prerequisite_ids']);
        }

        return response()->json($subject->load('prerequisites'));
    }

    /**
     * DELETE /api/subjects/{id}
     */
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return response()->json(['message' => 'Subject deleted successfully']);
    }

    /**
     * GET /api/subjects/curriculum/{curriculum_id}
     * Subjects by curriculum
     */
    public function getByCurriculum($curriculum_id)
    {
        try {
            $subjects = Subject::where('curriculum_id', $curriculum_id)
                ->with(['curriculum.course', 'prerequisites'])
                ->orderBy('year_level')
                ->orderBy('semester')
                ->get();

            return response()->json($subjects);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error fetching subjects for curriculum',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/subjects/course/{courseCode}
     * Subjects by course code (case-insensitive)
     */
   public function getSubjectsByCourse($courseCode)
{
    try {
        // ✅ Trim whitespace and make uppercase
        $courseCode = strtoupper(trim($courseCode));

        $subjects = \App\Models\Subject::with([
            'curriculum.course',
            'prerequisites'
        ])
        ->whereHas('curriculum.course', function ($query) use ($courseCode) {
            $query->whereRaw('UPPER(code) = ?', [$courseCode]);
        })
        ->orderBy('year_level')
        ->orderBy('semester')
        ->get();

        if ($subjects->isEmpty()) {
            return response()->json([
                'message' => 'No subjects found for this course.',
                'course' => $courseCode,
                'subjects' => []
            ], 200);
        }

        return response()->json([
            'message' => 'Subjects found.',
            'course' => $courseCode,
            'count' => $subjects->count(),
            'subjects' => $subjects
        ]);
    } catch (\Throwable $e) {
        \Log::error('getSubjectsByCourse error: ' . $e->getMessage(), [
            'courseCode' => $courseCode
        ]);

        return response()->json([
            'message' => 'Error fetching subjects for course',
            'error' => $e->getMessage()
        ], 500);
    }
    }
}
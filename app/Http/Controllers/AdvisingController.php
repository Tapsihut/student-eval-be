<?php

namespace App\Http\Controllers;

use App\Models\Advising;
use App\Models\UploadedTor;
use App\Models\TorGrade;
use App\Models\User;
use App\Notifications\NewStudentSubmitted;
use App\Notifications\TorSubmittedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AdvisingController extends Controller
{
    /**
     * Save generated advising subjects (NO CHANGE)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tor_id' => 'required|exists:uploaded_tors,id',
            'advising' => 'required|array',
            'advising.first_sem' => 'array',
            'advising.second_sem' => 'array',
            'ocr_records' => 'required|array'
        ]);

        $user = auth('sanctum')->user();
        $tor = UploadedTor::findOrFail($validated['tor_id']);

        DB::beginTransaction();
        try {
            // Delete previous advising
            Advising::where('uploaded_tor_id', $tor->id)->delete();

            // Save new advising
            $advisingRecords = [];
            foreach (['first_sem', 'second_sem'] as $sem) {
                foreach ($validated['advising'][$sem] ?? [] as $subject) {
                    $advisingRecords[] = [
                        'uploaded_tor_id' => $tor->id,
                        'user_id' => $user->id,
                        'semester' => $sem,
                        'subject_id' => $subject['subject_id'] ?? null,
                        'year_level' => $subject['year_level'] ?? null,
                        'subject_code' => $subject['code'] ?? '',
                        'subject_title' => $subject['title'] ?? '',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            $tor->status = 'submitted';
            $tor->save();
            Advising::insert($advisingRecords);

            // Save OCR records
            TorGrade::where('tor_id', $tor->id)->delete();
            $ocrRecords = collect($validated['ocr_records'])->map(function ($r) use ($tor, $user) {
                return [
                    'tor_id' => $tor->id,
                    'user_id' => $user->id,
                    'extracted_code' => $r['code'] ?? '',
                    'credited_id' => $r['credited_id'] ?? null,
                    'credited_code' => $r['credited_code'] ?? null,
                    'title' => $r['title'] ?? '',
                    'credits' => $r['credits'] ?? 0,
                    'grade' => (isset($r['grade']) && is_numeric($r['grade'])) ? $r['grade'] : null,
                    'is_credited' => $r['is_credited'] ?? false,
                    'percent_grade' => $r['percent_grade'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            TorGrade::insert($ocrRecords);


            // 🔥 SAME COURSE NOTIFICATION LOGIC (STORE)
            $user = $tor->user;

            if ($user) {
                $studentCourse = $user->course;
                Log::info("👀 Student course for TOR submission: {$studentCourse}");

                $admins = User::where('role', 'admin')
                    ->where('course', $studentCourse)
                    ->get();

                Log::info("👀 Admins receiving TOR notification: " . $admins->pluck('email'));

                if ($admins->count() > 0) {
                    foreach ($admins as $admin) {
                        $admin->notify(new TorSubmittedNotification($tor, $user));
                    }
                } else {
                    Log::warning("⚠️ No admins found for course {$studentCourse}");
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Advising and OCR records saved successfully.',
                'advising_count' => count($advisingRecords),
                'ocr_count' => count($ocrRecords),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to save advising', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to save advising.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Retrieve advising for a specific TOR
     */
    public function show($torId)
    {
        $advising = Advising::where('uploaded_tor_id', $torId)
            ->select('semester', 'subject_code', 'subject_title', 'units')
            ->get()
            ->groupBy('semester');

        return response()->json($advising);
    }

    /**
     * Handle advising request for NEW students
     * ✅ ONLY THIS HAS BEEN CHANGED (B)
     */
    public function newStudentAdvising(Request $request)
    {
        $validated = $request->validate([
            'curriculum_id' => 'required|exists:curriculums,id',
        ]);

        $user = auth('sanctum')->user();

        DB::beginTransaction();
        try {
            // Create Uploaded TOR
            $uploadedTor = UploadedTor::create([
                'user_id' => $user->id,
                'curriculum_id' => $validated['curriculum_id'],
                'file_path' => null,
                'public_id' => null,
                'status' => 'pending',
            ]);

            // Get subjects
            $subjects = \App\Models\Subject::where('curriculum_id', $validated['curriculum_id'])
                ->select('id', 'code', 'name', 'year_level', 'semester', 'units')
                ->get();

            $firstSem = $subjects->where('year_level', 1)->where('semester', '1st');
            $secondSem = $subjects->where('year_level', 1)->where('semester', '2nd');

            // 🔥 Filter subjects: NEW STUDENT cannot take subjects with prerequisites
            $filteredSubjects = $subjects
                ->where('year_level', 1)
                ->filter(function ($subject) {
                    // Allow only subjects that have NO prerequisites
                    return $subject->prerequisites()->count() === 0;
                })
                ->values();

            // Save advising ONLY for allowed subjects
            $advisingRecords = $filteredSubjects
                ->map(function ($subject) use ($user, $uploadedTor) {

                    $mappedSemester = match ($subject->semester) {
                        '1st' => 'first_sem',
                        '2nd' => 'second_sem',
                        default => strtolower(trim($subject->semester ?? '')),
                    };

                    return [
                        'uploaded_tor_id' => $uploadedTor->id,
                        'user_id' => $user->id,
                        'subject_id' => $subject->id,
                        'semester' => $mappedSemester,
                        'subject_code' => $subject->code,
                        'year_level' => $subject->year_level,
                        'subject_title' => $subject->name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })
                ->toArray();


            Advising::insert($advisingRecords);


            /*
             |--------------------------------------------------------------------------
             | 🔥 B: ONLY notify admins with SAME COURSE for NEW student advising
             |--------------------------------------------------------------------------
            */

            $studentCourse = $user->course;
            Log::info("👀 NEW STUDENT course: {$studentCourse}");

            $admins = User::where('role', 'admin')
                ->where('course', $studentCourse)
                ->get();

            Log::info("👀 NEW STUDENT admins receiving notification: " . $admins->pluck('email'));

            if ($admins->count() > 0) {
                Notification::send($admins, new NewStudentSubmitted($uploadedTor, $user));
            } else {
                Log::warning("⚠️ No admins found for NEW student course {$studentCourse}");
            }

            DB::commit();

            return response()->json([
                'message' => 'New student advising generated and saved successfully.',
                'uploaded_tor_id' => $uploadedTor->id,

                // ✅ Return ONLY subjects allowed (no prerequisites)
                'first_sem' => $filteredSubjects->where('semester', '1st')->values(),
                'second_sem' => $filteredSubjects->where('semester', '2nd')->values(),

                'total_first_sem' => $filteredSubjects->where('semester', '1st')->count(),
                'total_second_sem' => $filteredSubjects->where('semester', '2nd')->count(),

                'total_saved' => count($advisingRecords),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Failed to generate and save new student advising', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to generate and save advising data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

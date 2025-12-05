<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\UploadedTor;
use App\Models\Subject;
use App\Models\TorGrade;
use App\Models\User;
use App\Models\Curriculum;
use App\Notifications\TorSubmittedNotification;
use App\Services\RemainingProgressService;
use App\Services\AdvisingService;
use Illuminate\Support\Str;

class TesseractOcrController extends Controller
{
    public function analyzeTor($torId, $curriculum_id)
    {
        set_time_limit(300);

        $tor = UploadedTor::findOrFail($torId);
        $apiKey = env('TESSERACT_KEY');
        $imageUrl = $tor->file_path;

        Log::info("🟢 Starting OCR + Advising for TOR ID: {$torId}");
        Log::info("🌐 File: {$imageUrl}");

        try {
            /* ---------------------------------------------------------
             * STEP 1 — OCR REQUEST
             * --------------------------------------------------------- */
            $response = Http::timeout(300)
                ->retry(2, 5000)
                ->withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ])
                ->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => 'google/gemma-3-4b-it:free',
                    'messages' => [[
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => "Perform OCR: extract text from this file. Return JSON array only in this exact format:
[{\"code\":\"\",\"title\":\"\",\"grade\":\"\",\"credits\":0}]"
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => ['url' => $imageUrl]
                            ]
                        ]
                    ]]
                ]);

            if ($response->failed()) {
                Log::error("OCR request failed: " . $response->body());
                return response()->json([
                    'error' => 'OCR request failed',
                    'details' => $response->body()
                ], 500);
            }

            /* ---------------------------------------------------------
             * STEP 2 — PARSE OCR JSON
             * --------------------------------------------------------- */
            $result = $response->json();
            $rawText = $result['choices'][0]['message']['content'] ?? '';
            $cleaned = preg_replace('/^```json|```$/m', '', trim($rawText));
            $jsonData = json_decode($cleaned, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $tor->update(['status' => 'failed', 'remarks' => 'Failed to parse OCR JSON.']);
                return response()->json([
                    'tor_id' => $torId,
                    'raw_text' => $cleaned
                ]);
            }

            Log::info(" OCR parsed successfully");

            /* ---------------------------------------------------------
             * STEP 3 — LOAD CURRICULUM SUBJECTS
             * --------------------------------------------------------- */
            $subjects = Subject::where('curriculum_id', $curriculum_id)
                ->get()
                ->keyBy(fn($item) => strtolower(str_replace(' ', '', $item->code)));

            /* ---------------------------------------------------------
             * STEP 4 — MATCH SUBJECTS + PATHFIT NORMALIZATION
             * --------------------------------------------------------- */
            $records = collect($jsonData)->map(function ($record) use ($subjects) {

                $code = strtoupper(str_replace(' ', '', $record['code'] ?? ''));
                $title = strtoupper(trim($record['title'] ?? ''));

                $recordCodeNorm = strtolower($code);
                $recordTitleNorm = strtolower($title);

                /* ---------------------------------------------------------
                 * NEW STRICT PATHFIT / PE NORMALIZATION
                 * --------------------------------------------------------- */
                $normalizedCode = null;

                // RULE A: Strict PE/PHED code pattern
                if (preg_match('/^PE\s*0*([1-4])$/i', $code, $m) ||
                    preg_match('/^PHED\s*0*([1-4])$/i', $code, $m) ||
                    preg_match('/^[0-9]*PHED\s*0*([1-4])$/i', $code, $m)) {

                    $lvl = intval($m[1]);
                    $normalizedCode = "PATHFIT$lvl";
                    Log::info(" PATHFIT NORMALIZED by CODE: {$code} → {$normalizedCode}");
                }

                // RULE B: Strict FITNESS title detection
                elseif (preg_match('/physical activity.*fitness\s*([1-4])$/i', $recordTitleNorm, $m)) {
                    $lvl = intval($m[1]);
                    $normalizedCode = "PATHFIT$lvl";
                    Log::info(" PATHFIT NORMALIZED by TITLE: {$title} → {$normalizedCode}");
                }

                if ($normalizedCode) {
                    $record['code'] = $normalizedCode;
                    $recordCodeNorm = strtolower($normalizedCode);
                }

                /* ---------------------------------------------------------
                 * NORMAL SUBJECT MATCHING
                 * --------------------------------------------------------- */
                $matchType = 'none';
                $matchedSubject = null;

                foreach ($subjects as $subject) {
                    $subjectCodeNorm = strtolower(str_replace(' ', '', $subject->code));
                    $subjectTitleNorm = strtolower(trim($subject->name));

                    // Exact code
                    if ($recordCodeNorm === $subjectCodeNorm) {
                        $matchType = 'exact_code';
                        $matchedSubject = $subject;
                        break;
                    }

                    // Partial code match
                    if (Str::startsWith($recordCodeNorm, $subjectCodeNorm) ||
                        Str::startsWith($subjectCodeNorm, $recordCodeNorm)) {
                        $matchType = 'partial_code';
                        $matchedSubject = $subject;
                        break;
                    }

                    // Fuzzy code similarity
                    similar_text($recordCodeNorm, $subjectCodeNorm, $percentCode);
                    if ($percentCode >= 90) {
                        $matchType = 'fuzzy_code';
                        $matchedSubject = $subject;
                        break;
                    }

                    // Title similarity
                    similar_text($recordTitleNorm, $subjectTitleNorm, $percentTitle);
                    if ($percentTitle >= 85) {
                        $matchType = 'fuzzy_title';
                        $matchedSubject = $subject;
                        break;
                    }
                }

                if ($matchedSubject) {
                    $record['credited_id'] = $matchedSubject->id;
                    $record['credited_code'] = $matchedSubject->code;
                    $record['is_credited'] = true;

                    Log::info("Matched '{$record['code']}' → {$matchedSubject->code} via {$matchType}");
                } else {
                    $record['credited_id'] = null;
                    $record['credited_code'] = null;
                    $record['is_credited'] = false;

                    Log::warning("No match for '{$record['code']}'");
                }

                return $record;
            });

            /* ---------------------------------------------------------
             * STEP 5 — SAVE WITH GRADE CONVERSION
             * --------------------------------------------------------- */
            $records = $records->map(function ($rec) use ($tor) {
                $rawGrade = $rec['grade'] ?? null;
                $converted = null;
                $percent = null;

                if (is_numeric($rawGrade)) {
                    $percent = floatval($rawGrade);

                    // Percentage → numeric grade
                    if ($percent > 5) {
                        if ($percent >= 97) $converted = 1.00;
                        elseif ($percent >= 94) $converted = 1.25;
                        elseif ($percent >= 91) $converted = 1.50;
                        elseif ($percent >= 88) $converted = 1.75;
                        elseif ($percent >= 85) $converted = 2.00;
                        elseif ($percent >= 82) $converted = 2.25;
                        elseif ($percent >= 79) $converted = 2.50;
                        elseif ($percent >= 76) $converted = 2.75;
                        elseif ($percent >= 75) $converted = 3.00;
                        else $converted = 5.00;
                    } else {
                        $converted = $percent;
                    }
                }

                // If failing grade → not credited
                if ($converted === null || $converted > 3.00) {
                    $rec['is_credited'] = false;
                    $rec['credited_id'] = null;
                    $rec['credited_code'] = null;
                }

                TorGrade::create([
                    'tor_id'        => $tor->id,
                    'user_id'       => $tor->user_id,
                    'extracted_code'=> $rec['code'],
                    'credited_id'   => $rec['credited_id'],
                    'credited_code' => $rec['credited_code'],
                    'is_credited'   => $rec['is_credited'] ? 1 : 0,
                    'title'         => $rec['title'] ?? '',
                    'grade'         => $converted,
                    'percent_grade' => $percent,
                    'credits'       => $rec['credits'] ?? 0,
                ]);

                return $rec;
            });

            /* ---------------------------------------------------------
             * STEP 6 — ADVISING
             * --------------------------------------------------------- */
            $curriculum = Curriculum::find($curriculum_id);

            $ocrRecordsFormatted = TorGrade::where('user_id', $tor->user_id)
                ->whereNotNull('extracted_code')
                ->get()
                ->map(fn($g) => [
                    'subject_code' => $g->credited_code ?? $g->extracted_code,
                    'grade' => $g->grade,
                ]);

            $advisingService = new AdvisingService();
            $advising = $advisingService->generateAdvising($curriculum, $ocrRecordsFormatted);

            $firstResult = [
                'subjects' => $advising['first_sem'],
                'total_units' => array_sum(array_column($advising['first_sem'], 'units')),
            ];

            $secondResult = [
                'subjects' => $advising['second_sem'],
                'total_units' => array_sum(array_column($advising['second_sem'], 'units')),
            ];

            /* ---------------------------------------------------------
             * STEP 7 — REMAINING PROGRESS
             * --------------------------------------------------------- */
            $remainingProgressService = new RemainingProgressService();
            $remainingProgress = $remainingProgressService->compute($tor, $curriculum_id);

            /* ---------------------------------------------------------
             * STEP 8 — RESPONSE
             * --------------------------------------------------------- */
            return response()->json([
                'message' => 'TOR analyzed and advising generated successfully.',
                'tor_id' => $tor->id,
                'ocr_records' => $records,
                'advising' => [
                    'first_sem' => $firstResult['subjects'],
                    'first_sem_total_units' => $firstResult['total_units'],
                    'second_sem' => $secondResult['subjects'],
                    'second_sem_total_units' => $secondResult['total_units'],
                ],
                'remaining_progress' => $remainingProgress
            ]);

        } catch (\Exception $e) {
            Log::error(" OCR error for TOR {$torId}: " . $e->getMessage());
            $tor->update(['status' => 'failed', 'remarks' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

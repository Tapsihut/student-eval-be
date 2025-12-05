<?php

namespace App\Services;

use App\Models\Subject;
use Illuminate\Support\Collection;

class AdvisingService
{
    /**
     * Generate advising list based on curriculum and OCR records.
     *
     * @param  \App\Models\Curriculum  $curriculum
     * @param  \Illuminate\Support\Collection  $ocrRecords
     * @return array
     */
    public function generateAdvising($curriculum, Collection $ocrRecords): array
    {
        // 🧾 Get all curriculum subjects
        $subjects = Subject::where('curriculum_id', $curriculum->id)
            ->with('prerequisites')
            ->get();

        // 🟢 Determine passed subjects (from OCR)
        $passedCodes = collect($ocrRecords)
            ->filter(fn($r) => isset($r['grade']) && is_numeric($r['grade']) && $r['grade'] <= 3.0)
            ->pluck('subject_code')
            ->map(fn($code) => strtoupper(trim($code)))
            ->toArray();

        // 🧮 Compute total curriculum units
        $totalUnits = $subjects->sum('units');

        // 🟢 Compute credited units (OCR subjects with grade)
        $creditedUnits = collect($ocrRecords)
            ->filter(fn($r) => isset($r['grade']) && is_numeric($r['grade'])) // has grade
            ->map(function ($r) use ($subjects) {
                $sub = $subjects->firstWhere('code', trim($r['subject_code']));
                return $sub ? $sub->units : 0;
            })
            ->sum();

        // 70% unit requirement for OJT
        $requiredUnits = $totalUnits * 0.70;
        $has70PercentUnits = $creditedUnits >= $requiredUnits;

        // ------------------------------------

        $eligible = [
            'first_sem' => [],
            'second_sem' => [],
        ];

        $unitCount = ['first_sem' => 0, 'second_sem' => 0];
        $maxUnits = 27;

        foreach ($subjects as $subject) {

            $code = strtoupper(trim($subject->code));

            // 🛑 Skip if already passed
            if (in_array($code, $passedCodes)) continue;

            // 🟡 Check prerequisites
            $canEnroll = true;
            foreach ($subject->prerequisites as $pre) {
                $preCode = strtoupper(trim($pre->code));
                if (!in_array($preCode, $passedCodes)) {
                    $canEnroll = false;
                    break;
                }
            }
            if (!$canEnroll) continue;

            // 🛑 SPECIAL RULE FOR OJT / PRACTICUM (always 4th year, 2nd sem)
            $name = strtolower($subject->name);

            $isOjt =
                str_contains($name, 'ojt') ||
                str_contains($name, 'practicum') ||
                str_contains($name, 'on-the-job') ||
                str_contains($name, 'on the job') ||
                str_contains($name, 'job training') ||
                str_contains($name, 'on-the-job training') ||
                str_contains($name, 'training (486 hours)');
                


            if ($isOjt && $subject->year_level == 4) {
                if (!$has70PercentUnits) {
                    // ❌ DO NOT ADVISE OJT unless 70% of curriculum units are credited
                    continue;
                }
            }

            // 🎯 Semester Parsing
            $semRaw = strtolower(trim($subject->semester));

            $firstSemValues = ['first', '1st', 'first semester', '1'];
            $secondSemValues = ['second', '2nd', 'second semester', '2'];

            if (in_array($semRaw, $firstSemValues)) {
                $semKey = 'first_sem';
            } elseif (in_array($semRaw, $secondSemValues)) {
                $semKey = 'second_sem';
            } else {
                $semKey = str_contains($semRaw, '1') ? 'first_sem' : 'second_sem';
            }

            // 🧮 Check unit cap
            if ($unitCount[$semKey] + $subject->units <= $maxUnits) {

                $eligible[$semKey][] = [
                    'subject_id' => $subject->id,
                    'code' => $subject->code,
                    'title' => $subject->name,
                    'units' => $subject->units,
                    'year_level' => $subject->year_level,
                    'prerequisites' => $subject->prerequisites->pluck('code')->values(),
                ];

                $unitCount[$semKey] += $subject->units;
            }
        }

        return $eligible;
    }
}

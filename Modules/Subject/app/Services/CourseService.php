<?php

namespace Modules\Subject\Services;

use App\Helpers\DbHelper;
use Modules\Subject\Models\CourseOffering;
use Modules\Subject\Models\Subject;
use Illuminate\Support\Collection;

class CourseService
{
    public function getNameFromCode($courseCode)
    {
        if (empty($courseCode)) {
            return is_array($courseCode) || $courseCode instanceof Collection
                ? collect()
                : null;
        }

        // Handle multiple codes
        if (is_array($courseCode) || $courseCode instanceof Collection) {
            $codes = is_array($courseCode) ? $courseCode : $courseCode->toArray();

            return Subject::whereIn('code', $codes)
                ->pluck('name', 'code'); // Returns [code => name]
        }

        // Handle single code
        return Subject::where('code', $courseCode)->value('name');
    }

    public function getInstructorNameBySubject($subjects)
    {
        $termCode = DbHelper::getCurrentTerm();
        
        // Resolve Term ID from Code if possible, or join with Terms table
        // Assuming DbHelper returns the Code (e.g. F25)
        
        $isSingle = !is_array($subjects) && !($subjects instanceof Collection);
        $subjectCodes = $isSingle ? [$subjects] : $subjects;

        if (empty($subjectCodes)) {
            return $isSingle ? null : [];
        }

        // Fetch Offerings for these subjects in current term
        $offerings = CourseOffering::with(['subject', 'teacher', 'term'])
            ->whereHas('subject', function($q) use ($subjectCodes) {
                $q->whereIn('code', $subjectCodes);
            })
            ->whereHas('term', function($q) use ($termCode) {
                $q->where('code', $termCode)
                  ->orWhere('is_active', true); // Fallback if termCode mismatch
            })
            ->get();

        $mapped = [];
        foreach ($offerings as $offering) {
            $code = $offering->subject->code;
            
            if (!isset($mapped[$code])) {
                $mapped[$code] = [];
            }

            // Mimic original array structure: array of sections
            $mapped[$code][] = [
                'name' => $offering->teacher ? $offering->teacher->name : 'Staff',
                'id' => $offering->teacher_id,
                'section' => $offering->section_number,
                'room' => $offering->room
            ];
        }

        if ($isSingle) {
            // Return array of instructors for this subject
            return $mapped[$subjects] ?? [];
        }

        return $mapped;
    }
}

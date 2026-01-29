<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class StudentCollection extends Collection
{
    private function getCurrentSectionName($student): ?string
    {
        $today = now('Asia/Manila')->toDateString();

        return $student->sections->first(function ($section) use ($today) {
            return $section->semester->start_date <= $today && $section->semester->end_date >= $today;
        })?->name ?? null;
    }

    public function studentSummary(): SupportCollection
    {
        return $this->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->user->email ?? null,
                'student_number' => $student->student_number,
                'course' => $student->course->name ?? null,
                'research_groups' => $student->researchGroups->pluck('id'),
                'sections' => $student->sections->pluck('id'),
                'current_section' => $this->getCurrentSectionName($student),
            ];
        });
    }
}

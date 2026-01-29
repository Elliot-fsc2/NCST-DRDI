<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class SectionCollection extends Collection
{
    public function summary(): SupportCollection
    {
        return $this->map(function ($section) {
            return [
                'id' => $section->id,
                'name' => $section->name,
                'course' => $section->course->name ?? null,
                'teacher' => $section->teacher->name ?? null,
                'semester' => [
                    'name' => $section->semester->name ?? null,
                    'start_date' => $section->semester->start_date ?? null,
                    'end_date' => $section->semester->end_date ?? null,
                ],
                'student_count' => $section->students->count(),
                // 'students' => $section->students->map(fn ($s) => ['id' => $s->id, 'name' => $s->name]),
                'is_active' => optional($section->semester)->start_date <= now()->toDateString()
                              && optional($section->semester)->end_date >= now()->toDateString(),
            ];
        });
    }
}

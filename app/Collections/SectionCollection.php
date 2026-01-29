<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class SectionCollection extends Collection
{
    public function sectionSummary(): SupportCollection
    {
        return $this->map(function ($section) {
            return [
                'id' => $section->id,
                'name' => $section->name,
                'course' => $section->course->name ?? null,
                'teacher' => $section->teacher->name ?? null,
                'semester' => [
                    'id' => $section->semester->id ?? null,
                    'name' => $section->semester->name ?? null,
                    'start_date' => $section->semester->start_date ?? null,
                    'end_date' => $section->semester->end_date ?? null,
                ],
                'student_ids' => $section->students->pluck('id'),
                'student_count' => $section->students->count(),
                'students' => $section->students->map(fn ($s) => ['id' => $s->id, 'name' => $s->name]),
                'is_active' => optional($section->semester)->start_date <= now()->toDateString()
                              && optional($section->semester)->end_date >= now()->toDateString(),
            ];
        });
    }

    public function onlyCurrent(): self
    {
        $today = now()->toDateString();

        return $this->filter(function ($section) use ($today) {
            return optional($section->semester)->start_date <= $today
                && optional($section->semester)->end_date >= $today;
        })->values();
    }

    // Optional: flatten students across sections into a summary
    public function studentsSummary(): SupportCollection
    {
        return $this->flatMap(function ($section) {
            return $section->students->map(fn ($s) => [
                'section_id' => $section->id,
                'student_id' => $s->id,
                'student_name' => $s->name,
            ]);
        })->values();
    }
}

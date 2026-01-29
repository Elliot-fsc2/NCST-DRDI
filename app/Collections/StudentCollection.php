<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class StudentCollection extends Collection
{
    public function summary(): SupportCollection
    {
        return $this->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->user->email ?? null,
                'student_number' => $student->student_number,
                'course' => $student->course->name ?? null,
                'research_groups' => $student->researchGroups->pluck('id'),
                'sections' => $student->sections->pluck('name'),
            ];
        });
    }
}

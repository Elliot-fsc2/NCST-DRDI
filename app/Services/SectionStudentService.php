<?php

namespace App\Services;

use App\Models\SectionStudent;

class SectionStudentService
{
    public function __construct(protected SectionStudent $ss) {}

    public function addStudents(array $data): void
    {
        // dd($data);
        $sectionId = $data['section_id'];
        $students = $data['students'];

        foreach ($students as $student) {
            $this->ss->create([
                'section_id' => $sectionId,
                'student_name' => $student['name'],
                'email' => $student['email'],
                'contact_number' => $student['contact_number'],
            ]);
        }
    }
}

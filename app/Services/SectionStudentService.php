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

    public function updateStudents(array $data): void
    {
        // dd($data);
        $this->ss->whereId($data['id'])->update([
            'student_name' => $data['name'],
            'email' => $data['email'],
            'contact_number' => $data['contact_number'],
        ]);
    }

    public function deleteStudents(array $data): void
    {
        // dd($data);
        $this->ss->whereId($data['id'])->delete();
    }
}

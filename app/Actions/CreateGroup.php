<?php

namespace App\Actions;

use App\Models\ResearchGroup;
use DB;

class CreateGroup
{
    /**
     * Create a new class instance.
     */
    public function handle(array $data)
    {
        // dd($data);
        DB::transaction(function () use ($data) {
            $group = ResearchGroup::create([
                'section_id' => $data['section_id'],
                'leader_id' => $data['leader_id'],
            ]);

            $studentIds = $data['student_ids'] ?? [];

            $group->students()->attach($studentIds);

        });
    }
}

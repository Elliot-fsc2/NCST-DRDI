<?php

namespace App\Services;

use App\Models\ResearchGroup;

class GroupService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(array $group)
    {
        ResearchGroup::create([
            'name' => $group['name'],
            'course_id' => $group['course_id'],
            'section_id' => $group['section_id'],
        ]);
    }

    public function delete(int $groupId)
    {
        $group = ResearchGroup::findOrFail($groupId);
        $group->delete();
        // $group->members()->delete();
    }
}

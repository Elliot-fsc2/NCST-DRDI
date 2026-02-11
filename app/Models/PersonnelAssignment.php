<?php

namespace App\Models;

use App\Enums\PersonnelAssignmentRole;
use Illuminate\Database\Eloquent\Model;

class PersonnelAssignment extends Model
{
    protected $fillable = [
        'teacher_id',
        'research_group_id',
        'role',
    ];

    protected function casts(): array
    {
        return [
            'role' => PersonnelAssignmentRole::class,
        ];
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function group()
    {
        return $this->belongsTo(ResearchGroup::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'research_group_id',
        'student_id',
        'is_leader',
    ];

    protected $casts = [
        'is_leader' => 'boolean',
    ];

    public function researchGroup()
    {
        return $this->belongsTo(ResearchGroup::class);
    }

    public function student()
    {
        return $this->belongsTo(SectionStudent::class);
    }
}

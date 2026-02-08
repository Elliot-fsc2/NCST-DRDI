<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionStudent extends Model
{
    protected $fillable = [
        'section_id',
        'student_name',
        'contact_number',
        'email',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ResearchGroup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'leader_id',
        'section_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'section_id' => 'integer',
            'leader_id' => 'integer',
        ];
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'leader_id');
    }

    protected static function booted()
    {
        static::deleting(function ($researchGroup) {
            $researchGroup->students()->detach();
        });
    }
}

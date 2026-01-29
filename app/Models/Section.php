<?php

namespace App\Models;

use App\Collections\SectionCollection;
use Illuminate\Database\Eloquent\Attributes\CollectedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[CollectedBy(SectionCollection::class)]
class Section extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'course_id',
        'semester_id',
        'teacher_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'course_id' => 'integer',
            'semester_id' => 'integer',
            'teacher_id' => 'integer',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }

    protected function scopeWithCourse(Builder $query)
    {
        return $query->with('course');
    }

    protected function scopeWithSemester(Builder $query)
    {
        return $query->with('semester');
    }

    protected function scopeWithTeacher(Builder $query)
    {
        return $query->with('teacher');
    }

    protected function scopeWithStudents(Builder $query)
    {
        return $query->with('students');
    }

    protected function scopeActiveSections(Builder $query)
    {
        return $query->whereHas('semester', function (Builder $q) {
            $today = now()->toDateString();
            $q->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today);
        });
    }
}

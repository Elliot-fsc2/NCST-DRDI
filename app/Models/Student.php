<?php

namespace App\Models;

use App\Collections\StudentCollection;
use Illuminate\Database\Eloquent\Attributes\CollectedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[CollectedBy(StudentCollection::class)]
class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'course_id',
        'student_number',
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
        ];
    }

    public function user()
    {
        return $this->morphOne(User::class, 'profileable');
    }

    public function researchGroups(): BelongsToMany
    {
        return $this->belongsToMany(ResearchGroup::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class);
    }

    public function scopeWithUser(Builder $query): Builder
    {
        return $query->with('user');
    }

    public function scopeWithCourse(Builder $query): Builder
    {
        return $query->with('course');
    }

    public function scopeWithResearch(Builder $query): Builder
    {
        return $query->with('researchGroups');
    }

    public function scopeWithSections(Builder $query): Builder
    {
        return $query->with('sections.semester');
    }
}

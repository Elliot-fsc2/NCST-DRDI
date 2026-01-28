<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /**
     * Retrieves the current active section for the student based on the current date.
     *
     * This method queries the 'sections' relationship and filters it to find a section
     * where the related 'semester' has a start_date less than or equal to today and an
     * end_date greater than or equal to today. It returns the first matching section,
     * which represents the student's current section in the ongoing semester.
     *
     * @return \App\Models\Section|null The first matching section if found, or null otherwise.
     */
    public function currentSection(): ?Section
    {
        $today = now('Asia/Manila')->toDateString();

        return $this->sections()
            ->whereRelation('semester', 'start_date', '<=', $today)
            ->whereRelation('semester', 'end_date', '>=', $today)
            ->first();
    }
}

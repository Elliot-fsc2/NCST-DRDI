<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
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
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function current(): ?Semester
    {
        $today = now('Asia/Manila')->toDateString();

        return static::query()
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();
    }
}

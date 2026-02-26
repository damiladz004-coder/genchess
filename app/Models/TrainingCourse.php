<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingCourse extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duration_weeks',
        'duration_hours',
        'duration_minutes',
        'price_kobo',
        'currency',
        'discount_price_kobo',
        'active',
    ];

    public function activeCohort()
    {
        return $this->hasOne(TrainingCohort::class, 'course_id')->where('status', 'ongoing');
    }

    protected function casts(): array
    {
        return [
            'duration_weeks' => 'integer',
            'duration_hours' => 'integer',
            'duration_minutes' => 'integer',
            'price_kobo' => 'integer',
            'discount_price_kobo' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function getDurationLabelAttribute(): string
    {
        $hours = (int) ($this->duration_hours ?? 0);
        $minutes = (int) ($this->duration_minutes ?? 0);

        if ($hours > 0 || $minutes > 0) {
            $parts = [];

            if ($hours > 0) {
                $parts[] = $hours . ' ' . str('hour')->plural($hours);
            }

            if ($minutes > 0) {
                $parts[] = $minutes . ' ' . str('minute')->plural($minutes);
            }

            return implode(' ', $parts);
        }

        $weeks = (int) ($this->duration_weeks ?? 0);

        if ($weeks > 0) {
            return $weeks . ' ' . str('week')->plural($weeks);
        }

        return 'N/A';
    }

    public function cohorts()
    {
        return $this->hasMany(TrainingCohort::class, 'course_id');
    }

    public function modules()
    {
        return $this->hasMany(TrainingModule::class, 'course_id')->orderBy('module_number');
    }

    public function payments()
    {
        return $this->hasMany(TrainingPayment::class, 'course_id');
    }
}

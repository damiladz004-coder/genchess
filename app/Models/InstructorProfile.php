<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'screening_id',
        'genchess_instructor_id',
        'passport_photo_path',
        'full_name',
        'address',
        'city',
        'state',
        'country',
        'email',
        'phone',
    ];

    protected static function booted(): void
    {
        static::created(function (InstructorProfile $profile): void {
            if ($profile->genchess_instructor_id) {
                return;
            }

            $profile->forceFill([
                'genchess_instructor_id' => 'GEN-INST-'.str_pad((string) $profile->id, 4, '0', STR_PAD_LEFT),
            ])->saveQuietly();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function screening()
    {
        return $this->belongsTo(InstructorScreening::class, 'screening_id');
    }
}

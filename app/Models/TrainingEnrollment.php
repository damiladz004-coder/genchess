<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingEnrollment extends Model
{
    protected $fillable = [
        'cohort_id',
        'user_id',
        'status',
        'enrollment_status',
        'payment_status',
        'amount_due_kobo',
        'amount_paid_kobo',
        'paid_at',
        'completed_at',
        'quizzes_completed',
        'assignments_completed',
        'teaching_practice_completed',
        'mentor_approved',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'paid_at' => 'datetime',
            'amount_due_kobo' => 'integer',
            'amount_paid_kobo' => 'integer',
            'quizzes_completed' => 'boolean',
            'assignments_completed' => 'boolean',
            'teaching_practice_completed' => 'boolean',
            'mentor_approved' => 'boolean',
        ];
    }

    public function cohort()
    {
        return $this->belongsTo(TrainingCohort::class, 'cohort_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function certification()
    {
        return $this->hasOne(Certification::class, 'enrollment_id');
    }

    public function topicProgress()
    {
        return $this->hasMany(TrainingEnrollmentTopicProgress::class, 'enrollment_id');
    }

    public function assignmentSubmissions()
    {
        return $this->hasMany(TrainingAssignmentSubmission::class, 'enrollment_id');
    }

    public function capstoneReview()
    {
        return $this->hasOne(TrainingCapstoneReview::class, 'enrollment_id');
    }

    public function quizAttempts()
    {
        return $this->hasMany(TrainingTopicQuizAttempt::class, 'enrollment_id');
    }

    public function payments()
    {
        return $this->hasMany(TrainingPayment::class, 'enrollment_id');
    }

    public function isEligibleForCertification(): bool
    {
        return $this->quizzes_completed
            && $this->assignments_completed
            && $this->teaching_practice_completed
            && $this->mentor_approved;
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid' && $this->enrollment_status === 'enrolled';
    }
}

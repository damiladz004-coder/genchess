<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityConsultation extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_COMPLETED = 'completed';
    public const APPLICANT_TYPES = [
        'parent_guardian',
        'individual',
        'community_estate_representative',
        'organization_ngo',
        'youth_group',
        'religious_organization',
        'other',
    ];
    public const PURPOSE_OPTIONS = [
        'child_lessons',
        'personal_training',
        'community_center',
        'organization_program',
        'youth_development',
        'religious_program',
        'community_event',
        'other',
    ];
    public const MEETING_TYPES = [
        'zoom',
        'google_meet',
        'physical',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'location',
        'applicant_type',
        'purpose',
        'meeting_type',
        'preferred_date',
        'preferred_time',
        'message',
        'status',
        'meeting_link',
        'meeting_id',
        'meeting_passcode',
        'meeting_location',
        'scheduled_at',
        'confirmation_sent_at',
        'invitation_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'preferred_time' => 'datetime:H:i',
            'scheduled_at' => 'datetime',
            'confirmation_sent_at' => 'datetime',
            'invitation_sent_at' => 'datetime',
        ];
    }

    public static function applicantTypeLabels(): array
    {
        return [
            'parent_guardian' => 'Parent / Guardian',
            'individual' => 'Individual (Want to Learn Chess)',
            'community_estate_representative' => 'Community / Estate Representative',
            'organization_ngo' => 'Organization / NGO',
            'youth_group' => 'Youth Group',
            'religious_organization' => 'Religious Organization',
            'other' => 'Other',
        ];
    }

    public static function purposeLabels(): array
    {
        return [
            'child_lessons' => 'Chess lessons for my child',
            'personal_training' => 'Personal chess training',
            'community_center' => 'Starting a chess recreational center in my community',
            'organization_program' => 'Introducing chess in our organization or NGO',
            'youth_development' => 'Chess program for youth development',
            'religious_program' => 'Chess program for religious organizations',
            'community_event' => 'Community chess tournament or event',
            'other' => 'Other',
        ];
    }

    public static function meetingTypeLabels(): array
    {
        return [
            'zoom' => 'Zoom Meeting',
            'google_meet' => 'Google Meet',
            'physical' => 'Physical Meeting',
        ];
    }
}

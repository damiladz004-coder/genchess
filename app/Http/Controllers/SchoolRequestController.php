<?php

namespace App\Http\Controllers;

use App\Models\SchoolRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'program_type' => 'required|in:school,community,home',
            'school_type' => 'required|in:private,public',
            'class_system' => 'required|in:primary_jss_ss,grade_1_12,year_1_12',
            'address_line' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => ['required', 'string', 'max:100', Rule::in(config('nigeria.states', []))],
            'student_count' => 'nullable|integer|min:1',
            'message' => 'nullable|string',
            'applicant_type' => 'nullable|in:parent_home,community_estate,church,ngo,youth_org,school_non_formal',
            'session_type' => 'nullable|in:offline,online,hybrid',
            'physical_location' => 'nullable|string|max:255',
            'children_count' => 'nullable|string|max:10',
            'children_ages' => 'nullable|string|max:255',
            'chess_level' => 'nullable|in:beginner,intermediate,advanced,no_experience',
            'preferred_schedule' => 'nullable|in:weekdays,weekends,flexible',
            'parent_preferred_time' => 'nullable|date_format:H:i',
            'organization_name' => 'nullable|string|max:255',
            'participants_estimate' => 'nullable|in:5-10,10-20,20-50,50+',
            'age_group' => 'nullable|in:nursery,primary,secondary,mixed',
            'org_program_type' => 'nullable|in:weekly_classes,weekend_program,holiday_bootcamp,tournament_only,long_term_partnership',
            'consultation_needed' => 'nullable|in:yes,no',
            'meeting_type' => 'nullable|in:physical,virtual',
            'meeting_date' => 'nullable|date',
            'meeting_time' => 'nullable|date_format:H:i',
            'consent' => 'nullable|boolean',
        ]);

        $data = $request->only([
            'school_name',
            'contact_person',
            'email',
            'phone',
            'program_type',
            'student_count',
            'message',
            'school_type',
            'class_system',
            'address_line',
            'city',
            'state',
            'applicant_type',
            'session_type',
            'physical_location',
            'children_count',
            'children_ages',
            'chess_level',
            'preferred_schedule',
            'parent_preferred_time',
            'organization_name',
            'participants_estimate',
            'age_group',
            'org_program_type',
            'meeting_type',
            'meeting_date',
            'meeting_time',
        ]);

        $data['consultation_needed'] = $request->consultation_needed === null
            ? null
            : $request->consultation_needed === 'yes';
        $data['consent'] = (bool) $request->boolean('consent');

        SchoolRequest::create($data);

        return redirect()->back()->with('success', 'Your enrollment request has been submitted successfully!');
    }
}

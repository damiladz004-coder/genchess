@extends('layouts.public')

@section('content')
<section class="py-16">
    <div class="max-w-3xl mx-auto px-6">
        <div class="gc-panel p-6">
            <h1 class="text-3xl gc-heading mb-3">Instructor Screening Result</h1>
            @if(session('success'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('warning'))
                <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-amber-800">
                    {{ session('warning') }}
                </div>
            @endif
            <p class="text-slate-700"><strong>Name:</strong> {{ $screening->name }}</p>
            <p class="text-slate-700"><strong>Score:</strong> {{ $screening->score }} / {{ $screening->total_questions }} ({{ $screening->percentage }}%)</p>
            <p class="text-slate-700"><strong>Status:</strong>
                @if($screening->passed)
                    <span class="text-emerald-700 font-semibold">Passed</span>
                @else
                    <span class="text-rose-700 font-semibold">Not Passed</span>
                @endif
            </p>

            @if($screening->passed)
                <div class="mt-5 space-y-3 rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <h2 class="text-lg font-semibold text-slate-900">Screening Route Stages</h2>
                    <p class="text-sm text-slate-700">Stage 1: Online screening test <span class="font-semibold text-emerald-700">(passed)</span></p>
                    <p class="text-sm text-slate-700">Stage 2: Zoom/Google Meet interview (chess knowledge) - <strong>{{ strtoupper($screening->stage_two_status ?? 'pending') }}</strong></p>
                    <p class="text-sm text-slate-700">Stage 3: Zoom/Physical interview (classroom management & teaching) - <strong>{{ strtoupper($screening->stage_three_status ?? 'pending') }}</strong></p>
                    <p class="text-sm text-slate-700">Final decision - <strong>{{ strtoupper(str_replace('_', ' ', $screening->final_status ?? 'pending')) }}</strong></p>
                </div>

                @if($screening->final_status === 'approved')
                    <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                        Congratulations. You are approved as a Genchess instructor candidate.
                        @if($screening->instructorProfile)
                            Your profile is completed with ID <strong>{{ $screening->instructorProfile->genchess_instructor_id }}</strong>. You can access the Instructor Dashboard.
                        @else
                            Complete your biodata to generate your Genchess Instructor ID and activate dashboard access.
                        @endif
                    </div>

                    @if(!$screening->instructorProfile && !empty($onboardingUrl))
                        <div class="mt-4">
                            <a href="{{ $onboardingUrl }}" class="gc-btn-primary">Complete Instructor Biodata</a>
                        </div>
                    @endif
                @elseif($screening->invitation_sent_at)
                    <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                        You passed Stage 1. Interview invitation has been sent to <strong>{{ $screening->email }}</strong>
                        for a {{ $screening->interview_mode === 'physical' ? 'physical' : 'Zoom' }} interview.
                    </div>
                @else
                    <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-amber-800">
                        You passed Stage 1. Our team will schedule Stage 2 and Stage 3 interviews shortly.
                    </div>
                @endif
            @else
                <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-amber-800">
                    You did not meet the 80% pass mark. We recommend joining our Genchess Certified Chess Instructor Program (GCCIP).
                </div>
                <div class="mt-4">
                    <a href="{{ route('instructor.training') }}" class="gc-btn-primary">Go to GCCIP</a>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

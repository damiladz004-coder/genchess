@extends('layouts.public')

@section('content')
<section class="py-16">
    <div class="max-w-3xl mx-auto px-6">
        <div class="gc-panel p-6">
            <h1 class="text-3xl gc-heading mb-3">Instructor Screening Result</h1>
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
                @if($screening->invitation_sent_at)
                    <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                        Congratulations. An interview invitation has been sent to <strong>{{ $screening->email }}</strong>
                        for a {{ $screening->interview_mode === 'physical' ? 'physical' : 'Zoom' }} interview.
                    </div>
                @else
                    <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-amber-800">
                        Congratulations, you passed. We have received your result and our team will contact you shortly with interview details.
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

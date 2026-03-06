@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Enrollment Details</h2>

    @if(session('warning'))
        <div class="bg-amber-100 text-amber-800 p-3 rounded mb-4">
            {{ session('warning') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-100 text-rose-800 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    @if(session('info'))
        <div class="bg-sky-100 text-sky-800 p-3 rounded mb-4">
            {{ session('info') }}
        </div>
    @endif

    <p><strong>School Name:</strong> {{ $schoolRequest->school_name }}</p>
    <p><strong>Contact Person:</strong> {{ $schoolRequest->contact_person }}</p>
    <p><strong>Email:</strong> {{ $schoolRequest->email }}</p>
    <p><strong>Phone:</strong> {{ $schoolRequest->phone }}</p>
    <p><strong>Program Type:</strong> {{ ucfirst($schoolRequest->program_type) }}</p>
    <p><strong>School Type:</strong> {{ $schoolRequest->school_type ? ucfirst($schoolRequest->school_type) : 'N/A' }}</p>
    <p><strong>Class System:</strong> {{ $schoolRequest->class_system ?? 'N/A' }}</p>
    <p><strong>Address:</strong> {{ $schoolRequest->address_line ?? 'N/A' }}</p>
    <p><strong>City:</strong> {{ $schoolRequest->city ?? 'N/A' }}</p>
    <p><strong>State:</strong> {{ $schoolRequest->state ?? 'N/A' }}</p>
    <p><strong>Estimated Students:</strong> {{ $schoolRequest->student_count ?? 'N/A' }}</p>
    <p><strong>Message:</strong> {{ $schoolRequest->message ?? 'None' }}</p>
    <p><strong>Status:</strong> {{ ucfirst($schoolRequest->status) }}</p>

    @if($schoolRequest->status === 'pending')
        <form method="POST" action="{{ route('admin.enrollments.approve', $schoolRequest->id) }}">
            @csrf
            <button type="submit">Approve Enrollment</button>
        </form>
    @elseif($schoolRequest->status === 'approved' && $schoolRequest->school_id)
        <a href="{{ route('admin.schools.index') }}" class="gc-btn-primary text-xs px-3 py-1.5">
            Go to Schools
        </a>
    @endif

    <br>
    <a href="{{ route('admin.enrollments.index') }}">&larr; Back</a>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Enrollment Details</h2>

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
    @endif

    <br>
    <a href="{{ route('admin.enrollments.index') }}">← Back</a>
</div>
@endsection

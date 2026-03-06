@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">School Enrollment Requests</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
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

    <table border="1" cellpadding="10" width="100%">
        <thead>
            <tr>
                <th>School Name</th>
                <th>Program</th>
                <th>Contact</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $request)
                <tr>
                    <td>{{ $request->school_name }}</td>
                    <td>{{ ucfirst($request->program_type) }}</td>
                    <td>{{ $request->contact_person }}</td>
                    <td>{{ ucfirst($request->status) }}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.enrollments.show', $request->id) }}" class="gc-btn-secondary text-xs px-3 py-1.5">
                                View Details
                            </a>

                            @if($request->status === 'pending')
                                <form method="POST" action="{{ route('admin.enrollments.approve', $request->id) }}">
                                    @csrf
                                    <button type="submit" class="gc-btn-primary text-xs px-3 py-1.5">
                                        Approve
                                    </button>
                                </form>
                            @elseif($request->status === 'approved' && $request->school_id)
                                <a href="{{ route('admin.schools.index') }}" class="gc-btn-primary text-xs px-3 py-1.5">
                                    Go to Schools
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No enrollment requests yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

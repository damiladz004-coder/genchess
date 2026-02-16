@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">School Enrollment Requests</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
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
                        <a href="{{ route('admin.enrollments.show', $request->id) }}">
                            View
                        </a>
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

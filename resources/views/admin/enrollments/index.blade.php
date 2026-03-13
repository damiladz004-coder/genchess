@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="gc-heading text-3xl">School and Community Requests</h2>
            <p class="text-sm text-slate-600">Approve school onboarding, review community or home bookings, and schedule consultations.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="gc-btn-secondary">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="gc-panel border-emerald-200 bg-emerald-50 p-3 text-emerald-700">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="gc-panel border-amber-200 bg-amber-50 p-3 text-amber-700">{{ session('warning') }}</div>
    @endif
    @if(session('error'))
        <div class="gc-panel border-rose-200 bg-rose-50 p-3 text-rose-700">{{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="gc-panel border-sky-200 bg-sky-50 p-3 text-sky-700">{{ session('info') }}</div>
    @endif

    @if($requests->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No enrollment requests yet.</div>
    @else
        <div class="hidden overflow-x-auto gc-panel lg:block">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Request</th>
                        <th>Program</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Portal / Consultation</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                        <tr>
                            <td>
                                <div class="font-semibold text-slate-900">{{ $request->school_name }}</div>
                                <div class="text-xs text-slate-500">{{ $request->created_at?->format('Y-m-d H:i') }}</div>
                            </td>
                            <td>{{ ucfirst($request->program_type) }}</td>
                            <td>{{ $request->contact_person }}<div class="text-xs text-slate-500">{{ $request->email }} · {{ $request->phone }}</div></td>
                            <td>{{ ucfirst($request->status) }}</td>
                            <td>
                                @if(in_array(strtolower((string) $request->program_type), ['community', 'home'], true))
                                    {{ $request->consultation_invitation_sent_at ? 'Consultation sent '.$request->consultation_invitation_sent_at->format('Y-m-d') : 'Pending schedule' }}
                                @else
                                    {{ $request->portal_link_sent_at ? 'Portal link sent '.$request->portal_link_sent_at->format('Y-m-d') : 'Portal link pending' }}
                                @endif
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.enrollments.show', $request) }}" class="gc-btn-secondary px-3 py-1.5 text-xs">View</a>
                                    @if($request->status === 'pending')
                                        <form method="POST" action="{{ route('admin.enrollments.approve', $request) }}">
                                            @csrf
                                            <button type="submit" class="gc-btn-primary px-3 py-1.5 text-xs">Approve</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid gap-4 lg:hidden">
            @foreach($requests as $request)
                <article class="gc-panel p-4 space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="font-semibold text-slate-900">{{ $request->school_name }}</h3>
                            <p class="text-sm text-slate-500">{{ ucfirst($request->program_type) }} · {{ $request->contact_person }}</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ ucfirst($request->status) }}</span>
                    </div>
                    <div class="grid gap-2 text-sm text-slate-700">
                        <div><span class="text-slate-500">Email:</span> {{ $request->email }}</div>
                        <div><span class="text-slate-500">Phone:</span> {{ $request->phone }}</div>
                        <div><span class="text-slate-500">Follow-up:</span> {{ in_array(strtolower((string) $request->program_type), ['community', 'home'], true) ? ($request->consultation_invitation_sent_at ? 'Consultation sent' : 'Pending consultation') : ($request->portal_link_sent_at ? 'Portal link sent' : 'Portal link pending') }}</div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.enrollments.show', $request) }}" class="gc-btn-secondary">View</a>
                        @if($request->status === 'pending')
                            <form method="POST" action="{{ route('admin.enrollments.approve', $request) }}">
                                @csrf
                                <button type="submit" class="gc-btn-primary">Approve</button>
                            </form>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        {{ $requests->links() }}
    @endif
</div>
@endsection

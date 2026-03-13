@extends('layouts.app')

@section('content')
<div class="space-y-6">
    @php($profile = $instructor->instructorProfile)
    <div class="flex items-center justify-between gap-3">
        <div>
            <h2 class="text-3xl gc-heading">{{ $profile?->full_name ?? $instructor->name }}</h2>
            <p class="text-slate-600">{{ $profile?->email ?? $instructor->email }}</p>
        </div>
        <a href="{{ route('admin.instructors.index') }}" class="gc-btn-secondary">Back to Instructors</a>
    </div>

    <div class="gc-panel p-4">
        <div class="grid gap-4 md:grid-cols-3 xl:grid-cols-4">
            <div>
                <div class="text-sm text-slate-500">Instructor ID</div>
                <div>{{ $profile?->genchess_instructor_id ?? '-' }}</div>
            </div>
            <div>
                <div class="text-sm text-slate-500">Phone</div>
                <div>{{ $profile?->phone ?? $instructor->phone ?? '-' }}</div>
            </div>
            <div>
                <div class="text-sm text-slate-500">WhatsApp</div>
                <div>{{ $profile?->whatsapp_phone ?? $profile?->phone ?? $instructor->phone ?? '-' }}</div>
            </div>
            <div>
                <div class="text-sm text-slate-500">Status</div>
                <div>{{ ucfirst($instructor->status ?? 'active') }}</div>
            </div>
            <div>
                <div class="text-sm text-slate-500">Location</div>
                <div>{{ $profile?->location ?? '-' }}</div>
            </div>
            <div>
                <div class="text-sm text-slate-500">City</div>
                <div>{{ $profile?->city ?? '-' }}</div>
            </div>
            <div>
                <div class="text-sm text-slate-500">State</div>
                <div>{{ $profile?->state ?? '-' }}</div>
            </div>
            <div>
                <div class="text-sm text-slate-500">Date Certified</div>
                <div>{{ optional($profile?->screening?->certified_at)->format('Y-m-d') ?? '-' }}</div>
            </div>
        </div>
    </div>

    @if($profile)
        <div class="grid gap-4 xl:grid-cols-2">
            <div class="gc-panel p-4">
                <div class="text-sm text-slate-500">Biography</div>
                <p class="mt-2 text-slate-700">{{ $profile->short_biography ?? '-' }}</p>
            </div>
            <div class="gc-panel p-4">
                <div class="text-sm text-slate-500">Areas of Specialization</div>
                <p class="mt-2 text-slate-700">{{ $profile->areas_of_specialization ?? '-' }}</p>
            </div>
        </div>
    @endif

    <h3 class="text-lg font-semibold">Assigned Classes</h3>
    @if($instructor->teachingClasses->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No class assignments.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>School</th>
                        <th>Class</th>
                        <th>Level</th>
                        <th>Chess Mode</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($instructor->teachingClasses as $classroom)
                        <tr>
                            <td>{{ $classroom->school->school_name ?? 'N/A' }}</td>
                            <td>{{ $classroom->name }}</td>
                            <td>{{ ucfirst($classroom->level) }}</td>
                            <td>{{ ucfirst($classroom->chess_mode) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

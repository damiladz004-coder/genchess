@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="gc-heading text-3xl">Instructor Management</h2>
            <p class="text-sm text-slate-600">Manage certified instructor accounts, biodata, and portal access status.</p>
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

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Active</div><div class="text-2xl font-bold text-emerald-700">{{ $totals['active'] }}</div></div>
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Suspended</div><div class="text-2xl font-bold text-amber-700">{{ $totals['suspended'] }}</div></div>
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Inactive</div><div class="text-2xl font-bold text-slate-800">{{ $totals['inactive'] }}</div></div>
        <div class="gc-panel p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Profiles Completed</div><div class="text-2xl font-bold text-brand-800">{{ $totals['profiled'] }}</div></div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.2fr_2fr]">
        <div class="gc-panel p-4">
            <h3 class="mb-3 text-lg font-semibold">Create Instructor</h3>
            <form method="POST" action="{{ route('admin.instructors.store') }}" class="grid gap-3">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Certification Level</label>
                    <input type="text" name="certification_level" value="{{ old('certification_level') }}">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Status</label>
                    <select name="status" required>
                        @foreach($statusOptions as $statusOption)
                            <option value="{{ $statusOption }}">{{ ucfirst($statusOption) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-600">Password</label>
                        <input type="password" name="password" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-600">Confirm Password</label>
                        <input type="password" name="password_confirmation" required>
                    </div>
                </div>
                <button type="submit" class="gc-btn-primary">Create Instructor</button>
            </form>
        </div>

        <div class="gc-panel p-4">
            <form method="GET" class="grid gap-3 md:grid-cols-[1.5fr_1fr_auto]">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Search</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="ID, name, email, city, state">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-600">Status</label>
                    <select name="status">
                        <option value="all">All statuses</option>
                        @foreach($statusOptions as $statusOption)
                            <option value="{{ $statusOption }}" @selected(request('status') == $statusOption)>{{ ucfirst($statusOption) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="gc-btn-primary">Filter</button>
                    <a href="{{ route('admin.instructors.index') }}" class="gc-btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    @if($instructors->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No instructors found.</div>
    @else
        <div class="hidden overflow-x-auto gc-panel lg:block">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Instructor ID</th>
                        <th>Passport</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone / WhatsApp</th>
                        <th>Location</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Status</th>
                        <th>Date Certified</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($instructors as $instructor)
                        @php($profile = $instructor->instructorProfile)
                        <tr>
                            <td>{{ $profile?->genchess_instructor_id ?? 'Pending' }}</td>
                            <td>
                                @if($profile?->passport_photo_path)
                                    <img src="{{ asset('storage/'.$profile->passport_photo_path) }}" alt="{{ $profile->full_name }}" class="h-12 w-12 rounded-full object-cover">
                                @else
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-xs text-slate-500">N/A</div>
                                @endif
                            </td>
                            <td>
                                <a class="font-semibold text-brand-700 underline" href="{{ route('admin.instructors.show', $instructor) }}">
                                    {{ $profile?->full_name ?? $instructor->name }}
                                </a>
                            </td>
                            <td>{{ $profile?->email ?? $instructor->email }}</td>
                            <td>{{ $profile?->phone ?? $instructor->phone ?? '-' }} / {{ $profile?->whatsapp_phone ?? $profile?->phone ?? $instructor->phone ?? '-' }}</td>
                            <td>{{ $profile?->location ?? $profile?->address ?? '-' }}</td>
                            <td>{{ $profile?->city ?? '-' }}</td>
                            <td>{{ $profile?->state ?? '-' }}</td>
                            <td>{{ ucfirst($instructor->status ?? 'active') }}</td>
                            <td>{{ optional($profile?->screening?->certified_at)->format('Y-m-d') ?? '-' }}</td>
                            <td class="space-y-2">
                                <form method="POST" action="{{ route('admin.instructors.status', $instructor) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status">
                                        @foreach($statusOptions as $option)
                                            <option value="{{ $option }}" @selected($instructor->status === $option)>{{ ucfirst($option) }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="gc-btn-secondary px-3 py-1.5 text-xs">Update</button>
                                </form>
                                <form method="POST" action="{{ route('admin.instructors.reset-link', $instructor) }}">
                                    @csrf
                                    <button type="submit" class="gc-btn-primary px-3 py-1.5 text-xs">Send Reset Link</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid gap-4 lg:hidden">
            @foreach($instructors as $instructor)
                @php($profile = $instructor->instructorProfile)
                <article class="gc-panel p-4 space-y-4">
                    <div class="flex items-start gap-3">
                        @if($profile?->passport_photo_path)
                            <img src="{{ asset('storage/'.$profile->passport_photo_path) }}" alt="{{ $profile->full_name }}" class="h-16 w-16 rounded-2xl object-cover">
                        @else
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-xs text-slate-500">No photo</div>
                        @endif
                        <div class="min-w-0">
                            <a href="{{ route('admin.instructors.show', $instructor) }}" class="font-semibold text-brand-700 underline">{{ $profile?->full_name ?? $instructor->name }}</a>
                            <p class="text-sm text-slate-500">{{ $profile?->genchess_instructor_id ?? 'Pending ID' }}</p>
                            <p class="text-sm text-slate-600">{{ ucfirst($instructor->status ?? 'active') }}</p>
                        </div>
                    </div>
                    <div class="grid gap-2 text-sm text-slate-700 sm:grid-cols-2">
                        <div><span class="text-slate-500">Email:</span> {{ $profile?->email ?? $instructor->email }}</div>
                        <div><span class="text-slate-500">Phone:</span> {{ $profile?->phone ?? $instructor->phone ?? '-' }}</div>
                        <div><span class="text-slate-500">WhatsApp:</span> {{ $profile?->whatsapp_phone ?? $profile?->phone ?? $instructor->phone ?? '-' }}</div>
                        <div><span class="text-slate-500">Location:</span> {{ $profile?->location ?? '-' }}</div>
                        <div><span class="text-slate-500">City:</span> {{ $profile?->city ?? '-' }}</div>
                        <div><span class="text-slate-500">State:</span> {{ $profile?->state ?? '-' }}</div>
                        <div><span class="text-slate-500">Certified:</span> {{ optional($profile?->screening?->certified_at)->format('Y-m-d') ?? '-' }}</div>
                    </div>
                    <form method="POST" action="{{ route('admin.instructors.status', $instructor) }}" class="grid gap-2 sm:grid-cols-[1fr_auto]">
                        @csrf
                        @method('PATCH')
                        <select name="status">
                            @foreach($statusOptions as $option)
                                <option value="{{ $option }}" @selected($instructor->status === $option)>{{ ucfirst($option) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="gc-btn-secondary">Update Status</button>
                    </form>
                </article>
            @endforeach
        </div>

        {{ $instructors->links() }}
    @endif
</div>
@endsection

<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-3xl gc-heading">
                School Registrations
            </h1>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.schools.create') }}"
                   class="gc-btn-primary">
                    Create School
                </a>
                <a href="{{ route('admin.dashboard') }}"
                   class="gc-btn-secondary">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="gc-panel p-4">
            <form method="GET" action="{{ route('admin.schools.index') }}" class="flex flex-wrap items-end gap-3">
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-600 mb-1">Filter by status</label>
                    <select name="status" id="status" class="min-w-[180px]">
                        <option value="all" @if($status === 'all') selected @endif>All</option>
                        @foreach($statuses as $statusOption)
                            <option value="{{ $statusOption }}" @if($status === $statusOption) selected @endif>
                                {{ ucfirst($statusOption) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="gc-btn-primary">Apply</button>
            </form>
        </div>

        @if(session('success'))
            <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if($schools->count() === 0)
            <div class="gc-panel p-6 text-slate-600">No schools found for this filter.</div>
        @else
            <div class="gc-panel overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>School Name</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Status</th>
                            <th>Contact Person</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schools as $school)
                            <tr>
                                <td class="font-medium text-slate-800">{{ $school->school_name }}</td>
                                <td>{{ $school->city }}</td>
                                <td>{{ $school->state }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($school->status) {
                                            'active' => 'bg-emerald-100 text-emerald-700',
                                            'suspended' => 'bg-rose-100 text-rose-700',
                                            default => 'bg-amber-100 text-amber-700',
                                        };
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                        {{ ucfirst($school->status ?? 'unknown') }}
                                    </span>
                                </td>
                                <td>{{ $school->contact_person }}</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        @if($school->status === 'pending')
                                            <form method="POST" action="{{ route('admin.schools.approve', $school->id) }}">
                                                @csrf
                                                <button type="submit" class="gc-btn-primary text-xs px-3 py-1.5">
                                                    Approve
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('admin.schools.status', $school->id) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="text-sm">
                                                @foreach($statusOptions as $option)
                                                    <option value="{{ $option }}" @if($school->status === $option) selected @endif>
                                                        {{ ucfirst($option) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="gc-btn-secondary text-xs px-3 py-1.5">
                                                Update
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>

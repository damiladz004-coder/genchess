@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h2 class="text-3xl gc-heading">Grading Configuration</h2>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.grading.configuration.index') }}" class="gc-panel p-4 grid md:grid-cols-3 gap-3">
        <div>
            <label class="block text-sm font-medium mb-1">School Override</label>
            <select name="school_id">
                <option value="">Global Default Only</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ (string) $selectedSchoolId === (string) $school->id ? 'selected' : '' }}>
                        {{ $school->school_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2 flex items-end">
            <button type="submit" class="gc-btn-secondary">Load Configuration</button>
        </div>
    </form>

    <div class="grid md:grid-cols-2 gap-6">
        <form method="POST" action="{{ route('admin.grading.configuration.components.update') }}" class="gc-panel p-4 space-y-4">
            @csrf
            @method('PATCH')
            <h3 class="text-lg font-semibold">Global Component Weights</h3>
            @foreach($components as $index => $component)
                <div class="grid grid-cols-2 gap-3 items-center">
                    <div class="font-medium">{{ ucfirst($component->name) }}</div>
                    <div>
                        <input type="hidden" name="components[{{ $index }}][name]" value="{{ $component->name }}">
                        <input type="number" step="0.01" min="0" max="100" name="components[{{ $index }}][weight_percentage]" value="{{ $component->weight_percentage }}" required>
                    </div>
                </div>
            @endforeach
            <button type="submit" class="gc-btn-primary">Save Global Weights</button>
        </form>

        <form method="POST" action="{{ route('admin.grading.configuration.scales.update') }}" class="gc-panel p-4 space-y-4">
            @csrf
            @method('PATCH')
            <h3 class="text-lg font-semibold">Grade Scale</h3>
            @foreach($scales as $index => $scale)
                <div class="grid grid-cols-3 gap-2">
                    <input type="hidden" name="scales[{{ $index }}][id]" value="{{ $scale->id }}">
                    <input type="number" step="0.01" min="0" max="100" name="scales[{{ $index }}][min_percentage]" value="{{ $scale->min_percentage }}" required>
                    <input type="number" step="0.01" min="0" max="100" name="scales[{{ $index }}][max_percentage]" value="{{ $scale->max_percentage }}" required>
                    <input type="text" maxlength="5" name="scales[{{ $index }}][letter_grade]" value="{{ $scale->letter_grade }}" required>
                </div>
            @endforeach
            <button type="submit" class="gc-btn-primary">Save Grade Scale</button>
        </form>
    </div>

    @if($selectedSchool)
        <div class="gc-panel p-4 space-y-4">
            <div class="flex items-center justify-between gap-3">
                <h3 class="text-lg font-semibold">School Override: {{ $selectedSchool->school_name }}</h3>
                <form method="POST" action="{{ route('admin.grading.configuration.components.reset-school') }}" onsubmit="return confirm('Reset this school override to global defaults?');">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="school_id" value="{{ $selectedSchool->id }}">
                    <button type="submit" class="gc-btn-secondary text-rose-700">Reset Override</button>
                </form>
            </div>
            <form method="POST" action="{{ route('admin.grading.configuration.components.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" name="school_id" value="{{ $selectedSchool->id }}">
                @foreach($schoolComponentRows as $index => $row)
                    <div class="grid grid-cols-2 gap-3 items-center">
                        <div class="font-medium">
                            {{ ucfirst($row['name']) }}
                            @if($row['is_override'])
                                <span class="text-xs text-emerald-700">(override)</span>
                            @else
                                <span class="text-xs text-slate-500">(global default)</span>
                            @endif
                        </div>
                        <div>
                            <input type="hidden" name="components[{{ $index }}][name]" value="{{ $row['name'] }}">
                            <input type="number" step="0.01" min="0" max="100" name="components[{{ $index }}][weight_percentage]" value="{{ $row['weight_percentage'] }}" required>
                        </div>
                    </div>
                @endforeach
                <button type="submit" class="gc-btn-primary">Save School Override</button>
            </form>
        </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h2 class="text-3xl gc-heading">Scheme of Work</h2>

    <form method="GET" class="gc-panel p-4">
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Class</label>
                <select name="class_id">
                    <option value="">All classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Term</label>
                <select name="term">
                    <option value="">All terms</option>
                    @foreach($terms as $term)
                        <option value="{{ $term }}" @selected(request('term') == $term)>{{ $term }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="gc-btn-primary">Filter</button>
                <a href="{{ route('instructor.scheme.index') }}" class="gc-btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    @if($items->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No scheme items available for your classes.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Term</th>
                        <th>Week</th>
                        <th>Topic</th>
                        <th>Objectives</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->classroom->name ?? 'N/A' }}</td>
                            <td>{{ $item->term }}</td>
                            <td>{{ $item->week_number }}</td>
                            <td class="font-medium text-slate-800">{{ $item->topic }}</td>
                            <td>{{ $item->objectives ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

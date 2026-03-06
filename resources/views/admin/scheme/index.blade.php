@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6 space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Scheme of Work</h2>
        <a href="{{ route('admin.scheme.create') }}" class="gc-btn-primary">New Item</a>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if($items->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No scheme items yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Term</th>
                        <th>Week</th>
                        <th>Topic</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->classroom->name ?? 'N/A' }}</td>
                            <td>{{ $item->term }}</td>
                            <td>{{ $item->week_number }}</td>
                            <td>{{ $item->topic }}</td>
                            <td>
                                <a href="{{ route('admin.scheme.edit', $item) }}" class="text-brand-700 underline mr-3">Edit</a>
                                <form method="POST" action="{{ route('admin.scheme.destroy', $item) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 underline"
                                        onclick="return confirm('Delete this item?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

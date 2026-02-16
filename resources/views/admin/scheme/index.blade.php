@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Scheme of Work</h2>
        <a href="{{ route('admin.scheme.create') }}" class="bg-emerald-700 text-white px-3 py-2 rounded">
            New Item
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 text-green-700 bg-green-50 border border-green-200 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($items->isEmpty())
        <p class="text-gray-600">No scheme items yet.</p>
    @else
        <div class="overflow-x-auto bg-white border rounded">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2 border-b">Class</th>
                        <th class="text-left px-4 py-2 border-b">Term</th>
                        <th class="text-left px-4 py-2 border-b">Week</th>
                        <th class="text-left px-4 py-2 border-b">Topic</th>
                        <th class="text-left px-4 py-2 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $item->classroom->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $item->term }}</td>
                            <td class="px-4 py-2">{{ $item->week_number }}</td>
                            <td class="px-4 py-2">{{ $item->topic }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('admin.scheme.edit', $item) }}" class="text-blue-600 underline mr-3">Edit</a>
                                <form method="POST" action="{{ route('admin.scheme.destroy', $item) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 underline"
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

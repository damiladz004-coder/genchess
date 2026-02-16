@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-6">
    <h2 class="text-xl font-bold mb-4">Edit Scheme Item</h2>

    <form method="POST" action="{{ route('admin.scheme.update', $item) }}" class="space-y-4">
        @csrf
        @method('PATCH')

        <div>
            <label class="block text-sm font-medium">Class</label>
            <select name="class_id" class="border w-full px-3 py-2" required>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @selected($item->class_id == $class->id)>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium">Term</label>
                <select name="term" class="border w-full px-3 py-2" required>
                    @foreach($terms as $term)
                        <option value="{{ $term }}" @selected($item->term === $term)>{{ $term }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Week</label>
                <input type="number" name="week_number" class="border w-full px-3 py-2" min="1" max="20" required
                    value="{{ $item->week_number }}">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Topic</label>
            <input type="text" name="topic" class="border w-full px-3 py-2" required value="{{ $item->topic }}">
        </div>

        <div>
            <label class="block text-sm font-medium">Objectives</label>
            <textarea name="objectives" rows="3" class="border w-full px-3 py-2">{{ $item->objectives }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-emerald-700 text-white px-3 py-2 rounded">Update</button>
            <a href="{{ route('admin.scheme.index') }}" class="text-gray-700 underline">Cancel</a>
        </div>
    </form>
</div>
@endsection

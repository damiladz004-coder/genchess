@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-6 space-y-6">
    <h2 class="text-3xl gc-heading">Edit Scheme Item</h2>

    <form method="POST" action="{{ route('admin.scheme.update', $item) }}" class="gc-panel p-4 space-y-4">
        @csrf
        @method('PATCH')

        <div>
            <label class="block text-sm font-medium">Class</label>
            <select name="class_id" class="w-full" required>
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
                <select name="term" class="w-full" required>
                    @foreach($terms as $term)
                        <option value="{{ $term }}" @selected($item->term === $term)>{{ $term }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Week</label>
                <input type="number" name="week_number" class="w-full" min="1" max="20" required
                    value="{{ $item->week_number }}">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Topic</label>
            <input type="text" name="topic" class="w-full" required value="{{ $item->topic }}">
        </div>

        <div>
            <label class="block text-sm font-medium">Objectives</label>
            <textarea name="objectives" rows="3" class="w-full">{{ $item->objectives }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="gc-btn-primary">Update</button>
            <a href="{{ route('admin.scheme.index') }}" class="gc-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

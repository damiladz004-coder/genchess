@extends('layouts.app')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Settings</h2>
        <a href="{{ route('admin.dashboard') }}" class="gc-btn-secondary">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="gc-panel p-4 space-y-6">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Organization Name</label>
            <input type="text" name="organization_name"
                   value="{{ $settings['organization_name']->value ?? '' }}"
                   class="w-full">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Support Email</label>
            <input type="email" name="support_email"
                   value="{{ $settings['support_email']->value ?? '' }}"
                   class="w-full">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Support Phone</label>
            <input type="text" name="support_phone"
                   value="{{ $settings['support_phone']->value ?? '' }}"
                   class="w-full">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Default Currency</label>
            <input type="text" name="default_currency"
                   value="{{ $settings['default_currency']->value ?? '' }}"
                   class="w-full">
        </div>

        <div class="pt-2 border-t border-slate-200">
            <h3 class="text-lg font-semibold mb-3">Chess in Schools Images</h3>
            <p class="text-sm text-slate-600 mb-4">
                Upload real classroom photos to replace homepage and service page placeholders.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Hero Classroom Image</label>
                    <input type="file" name="chess_school_hero_image" accept=".jpg,.jpeg,.png,.webp" class="w-full">
                    @if(!empty($settings['chess_school_hero_image']->value))
                        <img src="{{ $settings['chess_school_hero_image']->value }}" alt="Hero classroom image preview" class="mt-2 h-28 w-full object-cover rounded border border-slate-200">
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Lesson Image</label>
                    <input type="file" name="chess_school_lesson_image" accept=".jpg,.jpeg,.png,.webp" class="w-full">
                    @if(!empty($settings['chess_school_lesson_image']->value))
                        <img src="{{ $settings['chess_school_lesson_image']->value }}" alt="Lesson image preview" class="mt-2 h-28 w-full object-cover rounded border border-slate-200">
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Students Playing Image</label>
                    <input type="file" name="chess_school_play_image" accept=".jpg,.jpeg,.png,.webp" class="w-full">
                    @if(!empty($settings['chess_school_play_image']->value))
                        <img src="{{ $settings['chess_school_play_image']->value }}" alt="Students playing image preview" class="mt-2 h-28 w-full object-cover rounded border border-slate-200">
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Puzzle Session Image</label>
                    <input type="file" name="chess_school_puzzle_image" accept=".jpg,.jpeg,.png,.webp" class="w-full">
                    @if(!empty($settings['chess_school_puzzle_image']->value))
                        <img src="{{ $settings['chess_school_puzzle_image']->value }}" alt="Puzzle image preview" class="mt-2 h-28 w-full object-cover rounded border border-slate-200">
                    @endif
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-600 mb-1">Competition Image</label>
                    <input type="file" name="chess_school_competition_image" accept=".jpg,.jpeg,.png,.webp" class="w-full">
                    @if(!empty($settings['chess_school_competition_image']->value))
                        <img src="{{ $settings['chess_school_competition_image']->value }}" alt="Competition image preview" class="mt-2 h-28 w-full object-cover rounded border border-slate-200">
                    @endif
                </div>
            </div>
        </div>

        <button type="submit" class="gc-btn-primary">Save Settings</button>
    </form>
</div>
@endsection

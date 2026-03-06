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

    <form method="POST" action="{{ route('admin.settings.update') }}" class="gc-panel p-4 space-y-4">
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

        <button type="submit" class="gc-btn-primary">Save Settings</button>
    </form>
</div>
@endsection

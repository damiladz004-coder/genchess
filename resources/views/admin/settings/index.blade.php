@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-3xl">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Settings</h2>
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 underline">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" class="bg-white border rounded p-4 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Organization Name</label>
            <input type="text" name="organization_name"
                   value="{{ $settings['organization_name']->value ?? '' }}"
                   class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Support Email</label>
            <input type="email" name="support_email"
                   value="{{ $settings['support_email']->value ?? '' }}"
                   class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Support Phone</label>
            <input type="text" name="support_phone"
                   value="{{ $settings['support_phone']->value ?? '' }}"
                   class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Default Currency</label>
            <input type="text" name="default_currency"
                   value="{{ $settings['default_currency']->value ?? '' }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded">Save Settings</button>
    </form>
</div>
@endsection

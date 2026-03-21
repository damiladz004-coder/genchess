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

    @if($errors->any())
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">
            <ul class="list-disc pl-5 space-y-1 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
            <h3 class="text-lg font-semibold mb-3">Public Page Images</h3>
            <p class="text-sm text-slate-600 mb-4">
                These images show automatically on the public pages after you save.
            </p>

            <div class="space-y-6">
                @foreach($pageImageSections as $sectionTitle => $fields)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <h4 class="text-base font-semibold text-slate-900 mb-3">{{ $sectionTitle }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($fields as $field)
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-1">{{ $field['label'] }}</label>
                                    <input type="file" name="{{ $field['key'] }}" accept=".jpg,.jpeg,.png,.webp" class="w-full">
                                    @if(!empty($settings[$field['key']]->value))
                                        <img
                                            src="{{ $settings[$field['key']]->value }}"
                                            alt="{{ $field['alt'] }}"
                                            class="mt-2 h-28 w-full object-cover rounded border border-slate-200"
                                        >
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="pt-2 border-t border-slate-200">
            <h3 class="text-lg font-semibold mb-3">Chess in Schools Images</h3>
            <p class="text-sm text-slate-600 mb-4">
                Upload real classroom photos to replace homepage and service page placeholders.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($schoolImageFields as $field)
                    <div class="{{ $field['key'] === 'chess_school_competition_image' ? 'md:col-span-2' : '' }}">
                        <label class="block text-sm font-medium text-slate-600 mb-1">{{ $field['label'] }}</label>
                        <input type="file" name="{{ $field['key'] }}" accept=".jpg,.jpeg,.png,.webp" class="w-full">
                        @if(!empty($settings[$field['key']]->value))
                            <img
                                src="{{ $settings[$field['key']]->value }}"
                                alt="{{ $field['alt'] }}"
                                class="mt-2 h-28 w-full object-cover rounded border border-slate-200"
                            >
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="pt-2 border-t border-slate-200 text-sm text-slate-600 space-y-2">
            <p>Store category and product images are managed from their own pages:</p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.store.categories.index') }}" class="gc-btn-secondary">Manage Store Categories</a>
                <a href="{{ route('admin.store.products.index') }}" class="gc-btn-secondary">Manage Store Products</a>
            </div>
        </div>

        <button type="submit" class="gc-btn-primary">Save Settings</button>
    </form>
</div>
@endsection

<x-app-layout>
    <div class="max-w-4xl space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl gc-heading">Create School</h1>
            <a href="{{ route('admin.schools.index') }}"
               class="gc-btn-secondary">
                Back to Schools
            </a>
        </div>

        @if($errors->any())
            <div class="gc-panel p-4 border-rose-200 bg-rose-50 text-rose-700">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.schools.store') }}" class="gc-panel p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">School Name</label>
                    <input name="school_name" value="{{ old('school_name') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">School Type</label>
                    <select name="school_type" required>
                        <option value="">Select</option>
                        <option value="private" @selected(old('school_type') === 'private')>Private</option>
                        <option value="public" @selected(old('school_type') === 'public')>Public</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Class System</label>
                <select name="class_system" required>
                    <option value="">Select</option>
                    <option value="primary_jss_ss" @selected(old('class_system') === 'primary_jss_ss')>Primary / JSS / SS</option>
                    <option value="grade_1_12" @selected(old('class_system') === 'grade_1_12')>Grade 1-12</option>
                    <option value="year_1_12" @selected(old('class_system') === 'year_1_12')>Year 1-12</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Address</label>
                <input name="address_line" value="{{ old('address_line') }}">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">City</label>
                    <input name="city" value="{{ old('city') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">State</label>
                    <x-nigeria-state-select name="state" :value="old('state')" class="w-full" required />
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Contact Person</label>
                <input name="contact_person" value="{{ old('contact_person') }}" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Phone</label>
                    <input name="phone" value="{{ old('phone') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
                <select name="status" required>
                    @foreach($statusOptions as $option)
                        <option value="{{ $option }}" @selected(old('status', 'pending') === $option)>{{ ucfirst($option) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-2">
                <button type="submit" class="gc-btn-primary">Create School</button>
            </div>
        </form>
    </div>
</x-app-layout>

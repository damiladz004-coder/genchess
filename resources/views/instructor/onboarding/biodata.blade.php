@extends('layouts.public')

@section('content')
<section class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-6">
        <div class="gc-panel p-6 md:p-8">
            <h1 class="text-3xl gc-heading mb-2">Complete Instructor Biodata</h1>
            <p class="text-slate-600 mb-6">
                Screening candidate: <strong>{{ $screening->name }}</strong> ({{ $screening->email }}). Complete this form to create your Genchess instructor profile and ID.
            </p>

            @if($errors->any())
                <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 p-4 text-rose-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ request()->fullUrl() }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Passport Photograph</label>
                    <input type="file" name="passport_photo" accept="image/*" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $screening->name) }}" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Address</label>
                    <textarea name="address" rows="3" required>{{ old('address') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Location</label>
                    <input type="text" name="location" value="{{ old('location', $screening->location) }}" placeholder="Area / District" required>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city') }}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">State</label>
                        <input type="text" name="state" value="{{ old('state') }}" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Country</label>
                    <input type="text" name="country" value="{{ old('country', 'Nigeria') }}" required>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $screening->email) }}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $screening->phone) }}" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">WhatsApp Phone Number</label>
                    <input type="text" name="whatsapp_phone" value="{{ old('whatsapp_phone', $screening->phone) }}" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Short Biography</label>
                    <textarea name="short_biography" rows="4" required>{{ old('short_biography') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Areas of Specialization</label>
                    <textarea name="areas_of_specialization" rows="3" placeholder="Beginner coaching, school clubs, tournament prep..." required>{{ old('areas_of_specialization') }}</textarea>
                </div>

                <button type="submit" class="gc-btn-primary">Create Instructor Profile</button>
            </form>
        </div>
    </div>
</section>
@endsection

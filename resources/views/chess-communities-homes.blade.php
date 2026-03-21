@extends('layouts.public')

@section('content')
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Chess in Communities &amp; Homes
            </h1>
            <p class="text-lg text-gray-700">
                Book a consultation with Genchess to discuss home chess lessons, community programs,
                and the best meeting format for your needs.
            </p>
        </div>

        <img
            src="{{ $communitiesHeroImage }}"
            alt="Community chess learning"
            class="w-full rounded-xl shadow-lg object-cover border border-slate-200"
        >
    </div>
</section>

<section class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16 grid lg:grid-cols-[1.1fr_0.9fr] gap-8 items-start">
        <div class="space-y-6">
            <div>
                <h2 class="text-3xl font-semibold text-gray-900 mb-3">Book a Consultation Appointment</h2>
                <p class="text-gray-700 leading-relaxed">
                    Use this form to request a consultation for Chess in Communities &amp; Homes.
                    Tell us who you are and the purpose of your request so our team can prepare the right response and meeting plan.
                </p>
            </div>

            <div class="grid sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow p-5">
                    <h3 class="font-semibold text-gray-900 mb-2">Step 1</h3>
                    <p class="text-sm text-gray-600">Choose your applicant type, tell us the purpose of the request, and share your preferred meeting time.</p>
                </div>
                <div class="bg-white rounded-xl shadow p-5">
                    <h3 class="font-semibold text-gray-900 mb-2">Step 2</h3>
                    <p class="text-sm text-gray-600">Receive an automatic confirmation email from Genchess immediately after submission.</p>
                </div>
                <div class="bg-white rounded-xl shadow p-5">
                    <h3 class="font-semibold text-gray-900 mb-2">Step 3</h3>
                    <p class="text-sm text-gray-600">Our admin team reviews the request and sends the meeting invitation by email.</p>
                </div>
            </div>
        </div>

        <div id="booking-form" class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Consultation Request Form</h2>

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="bg-amber-100 text-amber-800 p-4 rounded mb-4">
                    {{ session('warning') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                    <p class="font-semibold mb-2">Please fix the following:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('community-consultations.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block font-medium mb-1 text-gray-800">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded-lg px-4 py-2" required>
                </div>

                <div>
                    <label class="block font-medium mb-1 text-gray-800">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg px-4 py-2" required>
                </div>

                <div>
                    <label class="block font-medium mb-1 text-gray-800">Phone / WhatsApp Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded-lg px-4 py-2" required>
                </div>

                <div>
                    <label class="block font-medium mb-1 text-gray-800">Location (City / State)</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full border rounded-lg px-4 py-2" required>
                </div>

                <div>
                    <label class="block font-medium mb-1 text-gray-800">Applicant Type</label>
                    <select name="applicant_type" class="w-full border rounded-lg px-4 py-2" required>
                        <option value="">Select applicant type</option>
                        @foreach (\App\Models\CommunityConsultation::applicantTypeLabels() as $value => $label)
                            <option value="{{ $value }}" @selected(old('applicant_type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1 text-gray-800">Purpose of Request</label>
                    <select name="purpose" class="w-full border rounded-lg px-4 py-2" required>
                        <option value="">Select purpose</option>
                        @foreach (\App\Models\CommunityConsultation::purposeLabels() as $value => $label)
                            <option value="{{ $value }}" @selected(old('purpose') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1 text-gray-800">Preferred Meeting Type</label>
                    <select name="meeting_type" class="w-full border rounded-lg px-4 py-2" required>
                        <option value="">Select meeting type</option>
                        @foreach (\App\Models\CommunityConsultation::meetingTypeLabels() as $value => $label)
                            <option value="{{ $value }}" @selected(old('meeting_type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium mb-1 text-gray-800">Preferred Date</label>
                        <input type="date" name="preferred_date" value="{{ old('preferred_date') }}" class="w-full border rounded-lg px-4 py-2" required>
                    </div>
                    <div>
                        <label class="block font-medium mb-1 text-gray-800">Preferred Time</label>
                        <input type="time" name="preferred_time" value="{{ old('preferred_time') }}" class="w-full border rounded-lg px-4 py-2" required>
                    </div>
                </div>

                <div>
                    <label class="block font-medium mb-1 text-gray-800">Additional Message</label>
                    <textarea name="message" rows="4" class="w-full border rounded-lg px-4 py-2">{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                    Submit Consultation Request
                </button>
            </form>
        </div>
    </div>
</section>
@endsection

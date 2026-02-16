@extends('layouts.public')

@section('content')

<!-- HERO -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-6 py-16 text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Enroll Your School with Genchess Academy
        </h1>
        <p class="text-lg text-gray-700 max-w-3xl mx-auto">
            Bring structured chess education to your school, community,
            or home program — designed to build thinking skills, discipline,
            and confidence in students.
        </p>
    </div>
</section>

<!-- WHO THIS IS FOR -->
<section class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <h2 class="text-3xl font-semibold text-center mb-10">
            Who Can Enroll?
        </h2>

        <div class="grid md:grid-cols-3 gap-8 text-center">
            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-xl font-semibold mb-2">Schools</h3>
                <p class="text-gray-600">
                    Primary and secondary schools looking to integrate chess
                    as a subject or club.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-xl font-semibold mb-2">Communities</h3>
                <p class="text-gray-600">
                    Community centers, churches, estates, and NGOs
                    running youth programs.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-xl font-semibold mb-2">Homes</h3>
                <p class="text-gray-600">
                    Parents who want structured chess lessons
                    for their children at home.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ENROLLMENT FORM -->
<section class="bg-white">
    <div class="max-w-4xl mx-auto px-6 py-16">
        <h2 class="text-3xl font-semibold text-center mb-8">
            Enrollment & Inquiry Form
        </h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('school.enroll') }}" class="space-y-6 bg-gray-50 p-8 rounded-xl shadow">
            @csrf

            <div>
                <label class="block font-medium mb-1">School / Organization Name</label>
                <input type="text" name="school_name" value="{{ old('school_name') }}"
                    class="w-full border rounded-lg px-4 py-2" placeholder="Springfield Academy" required>
            </div>

            <div>
                <label class="block font-medium mb-1">Contact Person</label>
                <input type="text" name="contact_person" value="{{ old('contact_person') }}"
                    class="w-full border rounded-lg px-4 py-2" placeholder="Full Name" required>
            </div>

            <div>
                <label class="block font-medium mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border rounded-lg px-4 py-2" placeholder="email@example.com" required>
            </div>

            <div>
                <label class="block font-medium mb-1">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full border rounded-lg px-4 py-2" placeholder="+234..." required>
            </div>

            <div>
                <label class="block font-medium mb-1">Type of Program</label>
                <select name="program_type" class="w-full border rounded-lg px-4 py-2" required>
                    <option value="">Select program</option>
                    <option value="school" @selected(old('program_type') === 'school')>School Program</option>
                    <option value="community" @selected(old('program_type') === 'community')>Community Program</option>
                    <option value="home" @selected(old('program_type') === 'home')>Home Lessons</option>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">School Type</label>
                <select name="school_type" class="w-full border rounded-lg px-4 py-2" required>
                    <option value="">Select type</option>
                    <option value="private" @selected(old('school_type') === 'private')>Private</option>
                    <option value="public" @selected(old('school_type') === 'public')>Public</option>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Class System</label>
                <select name="class_system" class="w-full border rounded-lg px-4 py-2" required>
                    <option value="">Select system</option>
                    <option value="primary_jss_ss" @selected(old('class_system') === 'primary_jss_ss')>Primary / JSS / SS</option>
                    <option value="grade_1_12" @selected(old('class_system') === 'grade_1_12')>Grade 1-12</option>
                    <option value="year_1_12" @selected(old('class_system') === 'year_1_12')>Year 1-12</option>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Address Line (Optional)</label>
                <input type="text" name="address_line" value="{{ old('address_line') }}"
                    class="w-full border rounded-lg px-4 py-2" placeholder="Street / Area">
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city') }}"
                        class="w-full border rounded-lg px-4 py-2" placeholder="City" required>
                </div>
                <div>
                    <label class="block font-medium mb-1">State</label>
                    <x-nigeria-state-select name="state" :value="old('state')" required />
                </div>
            </div>

            <div>
                <label class="block font-medium mb-1">Number of Students (Approx.)</label>
                <input type="number" name="student_count" value="{{ old('student_count') }}"
                    class="w-full border rounded-lg px-4 py-2" min="1">
            </div>

            <div>
                <label class="block font-medium mb-1">Message (Optional)</label>
                <textarea rows="4" name="message" class="w-full border rounded-lg px-4 py-2"
                    placeholder="Tell us about your needs...">{{ old('message') }}</textarea>
            </div>

            <button
                type="submit"
                class="w-full bg-gray-900 text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition">
                Submit Inquiry
            </button>
        </form>
    </div>
</section>

<!-- CONTACT DETAILS -->
<section class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-6 py-16 text-center">
        <h2 class="text-3xl font-semibold mb-4">
            Prefer to Speak With Us Directly?
        </h2>

        <p class="text-gray-300 mb-6">
            Our team is happy to guide you through enrollment and program setup.
        </p>

        <div class="space-y-2 text-lg">
            <p>📧 Email: <strong>info@genchessacademy.com</strong></p>
            <p>📞 Phone / WhatsApp: <strong>+234 XXX XXX XXXX</strong></p>
        </div>
    </div>
</section>

@endsection

@extends('layouts.public')

@section('content')
<section class="bg-white">
    <div class="max-w-3xl mx-auto px-6 py-16">
        <h1 class="text-4xl font-bold mb-4">Register a School</h1>
        <p class="text-gray-700 mb-8">
            Submit your school details and our team will contact you to onboard your chess program.
        </p>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('school.enroll') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="program_type" value="school">

            <div>
                <label class="block text-sm font-medium mb-1">School Name</label>
                <input type="text" name="school_name" value="{{ old('school_name') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">School Type</label>
                    <select name="school_type" class="w-full border rounded px-3 py-2" required>
                        <option value="">Select</option>
                        <option value="private" @selected(old('school_type') === 'private')>Private</option>
                        <option value="public" @selected(old('school_type') === 'public')>Public</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Class System</label>
                    <select name="class_system" class="w-full border rounded px-3 py-2" required>
                        <option value="">Select</option>
                        <option value="primary_jss_ss" @selected(old('class_system') === 'primary_jss_ss')>Primary + JSS + SS</option>
                        <option value="grade_1_12" @selected(old('class_system') === 'grade_1_12')>Grade 1-12</option>
                        <option value="year_1_12" @selected(old('class_system') === 'year_1_12')>Year 1-12</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Address Line</label>
                <input type="text" name="address_line" value="{{ old('address_line') }}" class="w-full border rounded px-3 py-2">
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city') }}" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">State</label>
                    <x-nigeria-state-select name="state" :value="old('state')" class="w-full border rounded px-3 py-2" required />
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Contact Person</label>
                <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Estimated Chess Students</label>
                <input type="number" name="student_count" value="{{ old('student_count') }}" min="1" class="w-full border rounded px-3 py-2">
            </div>
            <button type="submit" class="bg-gray-900 text-white px-5 py-2 rounded">Submit Registration</button>
        </form>
    </div>
</section>
@endsection

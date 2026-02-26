@extends('layouts.public')

@section('content')
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16 md:py-20">
        <div class="max-w-3xl">
            <h1 class="text-4xl md:text-5xl gc-heading leading-tight">Contact {{ $organizationName }}</h1>
            <p class="mt-4 text-lg text-slate-600">
                Reach us for school partnerships, community and home chess programs, instructor opportunities, and general inquiries.
            </p>
        </div>
    </div>
</section>

<section class="bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 md:py-16 grid gap-6 lg:grid-cols-3">
        <article class="gc-panel p-6 lg:col-span-2">
            <h2 class="text-2xl font-semibold text-slate-900">Send Us a Message</h2>
            <p class="mt-2 text-slate-600">Fill this form and our team will respond as soon as possible.</p>

            @if(session('success'))
                <div class="mt-5 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->has('contact'))
                <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4 text-red-800">
                    {{ $errors->first('contact') }}
                </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" class="mt-6 space-y-5">
                @csrf

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Phone (Optional)</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Service</label>
                        <select name="service" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                            <option value="general" @selected(old('service', request('service', 'general')) === 'general')>General Inquiry</option>
                            <option value="school" @selected(old('service', request('service')) === 'school')>School Enrollment</option>
                            <option value="community" @selected(old('service', request('service')) === 'community')>Communities</option>
                            <option value="home" @selected(old('service', request('service')) === 'home')>Home Lessons</option>
                            <option value="instructor" @selected(old('service', request('service')) === 'instructor')>Instructor Application</option>
                            <option value="products" @selected(old('service', request('service')) === 'products')>Products</option>
                        </select>
                        @error('service') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                    @error('subject') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Message</label>
                    <textarea name="message" rows="5" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>{{ old('message') }}</textarea>
                    @error('message') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="gc-btn-primary">Send Message</button>
            </form>
        </article>

        <article class="gc-panel p-6">
            <h2 class="text-xl font-semibold text-slate-900">Email</h2>
            <p class="mt-2 text-slate-600">For general inquiries and support.</p>
            <p class="mt-4">
                <a class="font-semibold text-brand-700 hover:text-brand-800" href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>
            </p>
            <h2 class="mt-8 text-xl font-semibold text-slate-900">Phone / WhatsApp</h2>
            <p class="mt-2 text-slate-600">Call or message us during office hours.</p>
            <p class="mt-4">
                <a class="inline-flex items-center gap-2 font-semibold text-brand-700 hover:text-brand-800" href="https://wa.me/2348078462223" target="_blank" rel="noopener">
                    +2348078462223
                </a>
            </p>
            <h2 class="mt-8 text-xl font-semibold text-slate-900">Office</h2>
            <p class="mt-2 text-slate-600">Lagos, Nigeria</p>
            <p class="mt-4 text-sm text-slate-500">Mon - Fri: 9:00 AM - 5:00 PM</p>
        </article>
    </div>
</section>

<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 md:py-16">
        <h2 class="text-3xl gc-heading">What Do You Need Help With?</h2>
        <div class="mt-8 grid gap-6 md:grid-cols-3">
            <article class="gc-panel p-6">
                <h3 class="text-xl font-semibold text-slate-900">School Enrollment</h3>
                <p class="mt-2 text-slate-600">Register your school for structured chess classes.</p>
                <a href="{{ route('register.school') }}" class="mt-5 gc-btn-secondary">Register School</a>
            </article>

            <article class="gc-panel p-6">
                <h3 class="text-xl font-semibold text-slate-900">Communities & Homes</h3>
                <p class="mt-2 text-slate-600">Book programs for estates, groups, and home lessons.</p>
                <a href="{{ route('chess.communities.homes') }}" class="mt-5 gc-btn-secondary">Book Program</a>
            </article>

            <article class="gc-panel p-6">
                <h3 class="text-xl font-semibold text-slate-900">Become an Instructor</h3>
                <p class="mt-2 text-slate-600">Apply for screening and join our instructor network.</p>
                <a href="{{ route('instructor.screening.create') }}" class="mt-5 gc-btn-secondary">Apply Now</a>
            </article>
        </div>
    </div>
</section>
@endsection

@extends('layouts.public')

@section('content')

<!-- HERO -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-6 py-16 text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Careers at Genchess Academy
        </h1>
        <p class="text-lg text-gray-700 max-w-3xl mx-auto">
            Join a growing educational movement using chess to develop
            critical thinking, discipline, and confidence in young minds.
        </p>
    </div>
</section>

<!-- WHY WORK WITH GENCHESS -->
<section class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <h2 class="text-3xl font-semibold text-center mb-10">
            Why Work With Genchess?
        </h2>

        <div class="grid md:grid-cols-3 gap-8 text-center">
            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-xl font-semibold mb-2">Purpose-Driven Work</h3>
                <p class="text-gray-600">
                    Make a real impact by shaping children’s thinking,
                    decision-making, and life skills through chess.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-xl font-semibold mb-2">Structured Programs</h3>
                <p class="text-gray-600">
                    Work with a clear curriculum, training support,
                    and professional systems.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-xl font-semibold mb-2">Growth Opportunities</h3>
                <p class="text-gray-600">
                    Grow from instructor to coordinator or leadership roles
                    within the Genchess ecosystem.
                </p>
            </div>
        </div>

        <div class="mt-10 grid md:grid-cols-3 gap-6">
            <a href="{{ route('instructor.screening.create') }}" class="block bg-white border rounded-xl p-6 hover:shadow">
                <h3 class="text-xl font-semibold mb-2">Chess Instructors</h3>
                <p class="text-gray-600">Teach structured chess lessons in partner schools.</p>
            </a>
            <a href="{{ route('careers.coordinators') }}" class="block bg-white border rounded-xl p-6 hover:shadow">
                <h3 class="text-xl font-semibold mb-2">Coordinators</h3>
                <p class="text-gray-600">Coordinate school operations, schedules, and delivery quality.</p>
            </a>
            <a href="{{ route('careers.marketers') }}" class="block bg-white border rounded-xl p-6 hover:shadow">
                <h3 class="text-xl font-semibold mb-2">Marketers</h3>
                <p class="text-gray-600">Grow school partnerships and market awareness.</p>
            </a>
        </div>
    </div>
</section>

<!-- OPEN ROLES -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <h2 class="text-3xl font-semibold text-center mb-12">
            Open Opportunities
        </h2>

        @if(isset($jobs) && $jobs->count() > 0)
            <div class="grid md:grid-cols-2 gap-6">
                @foreach($jobs as $job)
                    <div class="border rounded-xl p-6">
                        <h3 class="text-2xl font-semibold mb-2">{{ $job->title }}</h3>
                        <p class="text-sm text-gray-600 mb-2">
                            {{ $job->location ?? 'Location: Flexible' }}
                            @if($job->type) · {{ ucfirst($job->type) }} @endif
                        </p>
                        <p class="text-gray-700 mb-4">
                            {{ \Illuminate\Support\Str::limit(strip_tags($job->description), 160) }}
                        </p>
                        <a href="{{ route('careers.show', $job) }}"
                           class="inline-block bg-gray-900 text-white px-4 py-2 rounded">
                            View & Apply
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-600">
                No openings right now. Please check back soon.
            </div>
        @endif
    </div>
</section>

<!-- APPLICATION PROCESS -->
<section class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-16 text-center">
        <h2 class="text-3xl font-semibold mb-6">
            Application Process
        </h2>

        <div class="grid md:grid-cols-4 gap-6">
            <div class="bg-white p-5 rounded-xl shadow">Apply Online</div>
            <div class="bg-white p-5 rounded-xl shadow">Screening Review</div>
            <div class="bg-white p-5 rounded-xl shadow">Interview</div>
            <div class="bg-white p-5 rounded-xl shadow">Training & Deployment</div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-6 py-16 text-center">
        <h2 class="text-3xl font-semibold mb-4">
            Ready to Join Genchess?
        </h2>
        <p class="text-lg text-gray-300 max-w-2xl mx-auto mb-8">
            Take the next step in building minds and shaping futures
            through chess education.
        </p>

        <a 
            href="{{ url('/contact') }}"
            class="inline-block bg-white text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-200 transition"
        >
            Apply Now
        </a>
    </div>
</section>

@endsection

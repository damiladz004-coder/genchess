@extends('layouts.public')

@section('content')
<section class="bg-white py-16">
    <div class="max-w-6xl mx-auto px-6 space-y-6">
        <nav aria-label="Breadcrumb" class="text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-slate-700">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('chess.in.schools') }}" class="hover:text-slate-700">Services</a>
            <span class="mx-2">/</span>
            <span class="text-slate-700">Chess in Schools</span>
        </nav>
        <h1 class="text-4xl gc-heading">Chess in Schools - Genchess Academy</h1>
        <p class="text-slate-700 text-lg">
            Genchess Academy partners with nursery, primary, and secondary schools across Nigeria to deliver a structured,
            age-appropriate chess curriculum that enhances academic performance, character development, and critical thinking.
        </p>
        <p class="text-slate-700">
            Our program aligns with Nigeria's 9-3-4 education system and integrates chess progressively from early childhood
            to senior secondary level.
        </p>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-3xl gc-heading mb-6">Classroom Image Placeholders</h2>
        <div class="grid gap-6 md:grid-cols-3">
            <figure class="gc-panel p-3">
                <img src="/images/placeholders/chess-in-school-nursery.svg" alt="Nursery classroom placeholder" class="w-full h-48 object-cover rounded-lg">
                <figcaption class="mt-2 text-sm text-slate-600">Nursery 2 chess introduction</figcaption>
            </figure>
            <figure class="gc-panel p-3">
                <img src="/images/placeholders/chess-in-school-primary.svg" alt="Primary classroom placeholder" class="w-full h-48 object-cover rounded-lg">
                <figcaption class="mt-2 text-sm text-slate-600">Primary 1-6 structured lesson</figcaption>
            </figure>
            <figure class="gc-panel p-3">
                <img src="/images/placeholders/chess-in-school-jss.svg" alt="JSS classroom placeholder" class="w-full h-48 object-cover rounded-lg">
                <figcaption class="mt-2 text-sm text-slate-600">JSS strategy and analysis session</figcaption>
            </figure>
        </div>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="max-w-6xl mx-auto px-6 space-y-6">
        <h2 class="text-3xl gc-heading">Understanding the 9-3-4 Education Structure in Nigeria</h2>
        <div class="gc-panel p-6">
            <p class="font-semibold mb-3">Nigeria operates a 9-3-4 system:</p>
            <ul class="space-y-2 text-slate-700">
                <li>9 Years Basic Education</li>
                <li>Nursery and Primary (Foundation Years)</li>
                <li>Primary 1-6</li>
                <li>Junior Secondary School (JSS 1-3)</li>
                <li>3 Years Senior Secondary School (SS1-SS3)</li>
                <li>4 Years Tertiary Education</li>
            </ul>
            <p class="mt-4 text-slate-700">Genchess integrates chess strategically within this structure.</p>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-6 space-y-8">
        <h2 class="text-3xl gc-heading">How Genchess Implements Chess in Schools</h2>

        <article class="gc-panel p-6 space-y-4">
            <h3 class="text-2xl font-semibold">Early Years: Nursery 2</h3>
            <p class="text-slate-700">Chess is introduced from Nursery 2 using play-based learning methods:</p>
            <ul class="space-y-2 text-slate-700">
                <li>Board recognition</li>
                <li>Chess storytelling</li>
                <li>Piece identification through games</li>
                <li>Movement songs and interactive activities</li>
                <li>Simple thinking exercises</li>
            </ul>
            <p class="text-slate-700">At this level, chess builds listening skills, pattern recognition, focus, and discipline.</p>
        </article>

        <article class="gc-panel p-6 space-y-4">
            <h3 class="text-2xl font-semibold">Primary School (Primary 1-6 / Year 1-6)</h3>
            <p class="font-semibold text-slate-800">Chess as a Subject</p>
            <p class="text-slate-700">
                From Primary 1 to Primary 6, chess is taught as a structured academic subject during school hours.
            </p>
            <p class="font-semibold text-slate-800">What Students Learn:</p>
            <ul class="space-y-2 text-slate-700">
                <li>Chess fundamentals and rules</li>
                <li>Board awareness (ranks, files, diagonals)</li>
                <li>Piece movement and values</li>
                <li>Basic tactics (forks, pins, skewers)</li>
                <li>Check and checkmate patterns</li>
                <li>Opening principles</li>
                <li>Mini tournaments and structured practice</li>
            </ul>
            <p class="font-semibold text-slate-800">Learning Outcomes:</p>
            <ul class="space-y-2 text-slate-700">
                <li>Improved mathematics reasoning</li>
                <li>Stronger problem-solving skills</li>
                <li>Better concentration and patience</li>
                <li>Development of sportsmanship</li>
            </ul>
        </article>

        <article class="gc-panel p-6 space-y-4">
            <h3 class="text-2xl font-semibold">Junior Secondary (JSS 1-3 / Grade 7-9)</h3>
            <p class="text-slate-700">
                Chess continues as a formal subject within the Basic Education framework.
                At this level, the focus shifts from fundamentals to strategy and analysis.
            </p>
            <p class="font-semibold text-slate-800">Curriculum Focus:</p>
            <ul class="space-y-2 text-slate-700">
                <li>Advanced tactics</li>
                <li>Strategic planning</li>
                <li>Opening principles and structures</li>
                <li>Endgame fundamentals</li>
                <li>Algebraic notation and recording games</li>
                <li>Game analysis and self-evaluation</li>
                <li>Competitive preparation</li>
            </ul>
            <p class="font-semibold text-slate-800">Skill Development:</p>
            <ul class="space-y-2 text-slate-700">
                <li>Analytical thinking</li>
                <li>Long-term planning</li>
                <li>Decision-making under pressure</li>
                <li>Emotional control</li>
            </ul>
        </article>

        <article class="gc-panel p-6 space-y-4">
            <h3 class="text-2xl font-semibold">Senior Secondary (SS1-SS3 / Grade 10-12)</h3>
            <p class="text-slate-700">At Senior Secondary level, chess is offered as a club activity.</p>
            <p class="font-semibold text-slate-800">Focus Areas:</p>
            <ul class="space-y-2 text-slate-700">
                <li>Competitive excellence</li>
                <li>Inter-school tournaments</li>
                <li>National and international exposure</li>
                <li>Leadership and mentoring</li>
                <li>Peer coaching</li>
            </ul>
            <p class="font-semibold text-slate-800">Students can also:</p>
            <ul class="space-y-2 text-slate-700">
                <li>Train as junior chess instructors</li>
                <li>Assist in primary school chess classes</li>
                <li>Prepare for rated tournaments</li>
            </ul>
        </article>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="max-w-6xl mx-auto px-6 space-y-6">
        <h2 class="text-3xl gc-heading">Why Chess Belongs in Schools</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="gc-panel p-5">
                <h3 class="text-xl font-semibold mb-2">Critical Thinking</h3>
                <p class="text-slate-700">Students learn to analyze situations, plan ahead, and evaluate consequences before acting.</p>
            </div>
            <div class="gc-panel p-5">
                <h3 class="text-xl font-semibold mb-2">Improved Focus</h3>
                <p class="text-slate-700">Chess strengthens concentration, patience, and attention span across subjects.</p>
            </div>
            <div class="gc-panel p-5">
                <h3 class="text-xl font-semibold mb-2">Life Skills Development</h3>
                <p class="text-slate-700">Students develop discipline, resilience, sportsmanship, confidence, and strategic thinking.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-6 space-y-6">
        <h2 class="text-3xl gc-heading">Program Delivery Options</h2>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="gc-panel p-6 space-y-3">
                <h3 class="text-2xl font-semibold">Chess as a Subject</h3>
                <ul class="space-y-2 text-slate-700">
                    <li>Integrated into the academic timetable</li>
                    <li>Weekly structured lessons</li>
                    <li>Continuous assessment</li>
                    <li>Report card grading</li>
                </ul>
                <p class="text-slate-700"><strong>Best suited for:</strong> Nursery 2 to JSS 3</p>
            </div>
            <div class="gc-panel p-6 space-y-3">
                <h3 class="text-2xl font-semibold">Chess as a Club</h3>
                <ul class="space-y-2 text-slate-700">
                    <li>After-school activity</li>
                    <li>Tournament-based</li>
                    <li>Competitive development</li>
                    <li>Flexible participation</li>
                </ul>
                <p class="text-slate-700"><strong>Best suited for:</strong> SS1 to SS3 and schools preferring extracurricular structure</p>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-slate-900 text-white">
    <div class="max-w-6xl mx-auto px-6 space-y-6">
        <h2 class="text-3xl md:text-4xl font-display">The Genchess Advantage</h2>
        <ul class="space-y-2 text-slate-200">
            <li>Structured curriculum aligned with Nigeria's 9-3-4 system</li>
            <li>Age-appropriate progression pathway</li>
            <li>Trained and certified instructors</li>
            <li>Lesson plans and assessment framework</li>
            <li>Interschool tournament opportunities</li>
            <li>Instructor mentorship system</li>
        </ul>
        <div>
            <a href="{{ route('register.school') }}" class="inline-flex items-center justify-center rounded-lg px-6 py-3 font-semibold bg-white text-slate-900">
                Register Your School
            </a>
        </div>
    </div>
</section>
@endsection

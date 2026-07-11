@extends('layouts.public')

@section('title', 'Genchess Elite | Online Chess Coaching for Young Champions')
@section('description', 'Genchess Elite Online Chess Coaching Programme prepares children for state, national and international competition with live coaching, tournament preparation, and mentor-led training.')
@section('keywords', 'Genchess Elite, online chess coaching, chess for kids, tournament preparation')
@section('image', asset('images/hero/genchess-hero.jpg'))

@section('content')

<!-- Sticky Nav -->
<div id="elite-nav" class="sticky top-0 z-50 bg-white/70 backdrop-blur-md border-b border-gray-200 dark:bg-slate-900/70 dark:border-slate-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="/" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-md bg-[#0B1F4D] flex items-center justify-center text-white font-bold">G</div>
                <span class="font-semibold text-[#0B1F4D]">Genchess Elite</span>
            </a>
            <nav class="hidden md:flex items-center gap-6 text-sm text-gray-700">
                <a href="#elite-hero" class="hover:underline">Home</a>
                <a href="#elite-why" class="hover:underline">Why</a>
                <a href="#elite-schedule" class="hover:underline">Schedule</a>
                <a href="#elite-fees" class="hover:underline">Fees</a>
                <a href="https://forms.gle/5zxFtYQiDmVoT7LE7" target="_blank" rel="noopener" class="rounded-full px-4 py-2 bg-[#D4AF37] text-white font-semibold">Register</a>
            </nav>
            <button id="nav-toggle" class="md:hidden p-2 rounded-md text-gray-600">Menu</button>
        </div>
    </div>
</div>

@include('components.elite.hero')

<!-- Welcome -->
<section id="elite-welcome" class="py-16 bg-gray-50 dark:bg-slate-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-display text-[#0B1F4D]">Welcome to Genchess Elite</h2>
            <p class="mt-4 text-lg text-gray-700 max-w-3xl mx-auto">Does your child enjoy chess? Have they shown excitement during lessons, or asked for more practice? Genchess Elite is a selective online programme designed to turn interest into competitive success. Through mentor-led sessions, rigorous practice, and tournament exposure, we prepare students to perform confidently at state, national and international levels.</p>
        </div>

        <div class="mt-8 grid md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-[#0B1F4D]">Who this is for</h3>
                    <ul class="mt-3 space-y-2 text-gray-700">
                        <li>Students who love playing chess and want structured progression.</li>
                        <li>Young players who show exceptional interest in lessons and practice.</li>
                        <li>Families who want their child to compete and represent at higher levels.</li>
                    </ul>
                </div>
            </div>

            <aside>
                <div class="rounded-2xl border-l-4 border-amber-400 bg-white p-5 shadow-sm">
                    <h4 class="font-semibold text-[#0B1F4D]">Note</h4>
                    <p class="mt-2 text-gray-700">This programme is <strong>NOT compulsory</strong>. Enrollment is voluntary and designed for students seeking competitive development.</p>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- Why This Programme -->
<section id="elite-why" class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-display text-[#0B1F4D] text-center">Why This Programme?</h2>
        <p class="mt-3 text-center text-gray-700 max-w-2xl mx-auto">An accelerated pathway for students who are serious about chess competition. Each element is crafted to build skill, resilience, and tournament experience.</p>

        <div class="mt-8 grid sm:grid-cols-2 md:grid-cols-4 gap-6">
            @include('components.elite.icon-card', ['title' => 'Intensive Coaching', 'description' => 'Focused training sessions with experienced coaches.', 'icon' => '<svg class="w-8 h-8 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v2H2V5zM2 9h16v6a2 2 0 01-2 2H4a2 2 0 01-2-2V9z"/></svg>'])
            @include('components.elite.icon-card', ['title' => 'Tournament Preparation', 'description' => 'Structured prep for competitions and match-play.', 'icon' => '<svg class="w-8 h-8 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9 12l2-2 2 2V5h2v10H5V5h2v7z"/></svg>'])
            @include('components.elite.icon-card', ['title' => 'Competitive Experience', 'description' => 'Simulated events and local tournament entries.', 'icon' => '<svg class="w-8 h-8 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 00-.894.553L6 7H3a1 1 0 000 2h3l1.106 4.447A1 1 0 008 14h4a1 1 0 00.894-.553L14 9h3a1 1 0 100-2h-3l-2.106-4.447A1 1 0 0011 2H9z"/></svg>'])
            @include('components.elite.icon-card', ['title' => 'Confidence Building', 'description' => 'Mindset coaching to perform under pressure.', 'icon' => '<svg class="w-8 h-8 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 100 12A6 6 0 0010 2z"/></svg>'])
            @include('components.elite.icon-card', ['title' => 'Advanced Strategy', 'description' => 'Opening, middlegame, and endgame mastery.', 'icon' => '<svg class="w-8 h-8 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11h16v2H2z"/></svg>'])
            @include('components.elite.icon-card', ['title' => 'Regular Practice', 'description' => 'Weekly assignments and performance reviews.', 'icon' => '<svg class="w-8 h-8 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M6 2h8v2H6V2zM3 6h14v10a2 2 0 01-2 2H5a2 2 0 01-2-2V6z"/></svg>'])
            @include('components.elite.icon-card', ['title' => 'Psychological Preparation', 'description' => 'Focus, time management and resilience training.', 'icon' => '<svg class="w-8 h-8 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16A8 8 0 0010 2z"/></svg>'])
        </div>
    </div>
</section>

<!-- Objectives -->
<section id="elite-objectives" class="py-16 bg-gray-50 dark:bg-slate-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-display text-[#0B1F4D] text-center">Programme Objectives</h2>
        <p class="mt-3 text-center text-gray-700">Clear, measurable goals that guide every training block.</p>

        <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @include('components.elite.icon-card', ['title' => 'Develop Opening Repertoires', 'description' => 'Tailored openings to match each player\'s style.'])
            @include('components.elite.icon-card', ['title' => 'Enhance Tactical Vision', 'description' => 'Daily tactical training and timed exercises.'])
            @include('components.elite.icon-card', ['title' => 'Strengthen Endgame Technique', 'description' => 'Master key endgame positions and conversion.'])
            @include('components.elite.icon-card', ['title' => 'Improve Time Management', 'description' => 'Practical tips to handle clock pressure.'])
            @include('components.elite.icon-card', ['title' => 'Performance Analysis', 'description' => 'Detailed reviews of tournament and practice games.'])
            @include('components.elite.icon-card', ['title' => 'Mental Resilience', 'description' => 'Confidence-building and focus routines.'])
        </div>
    </div>
</section>

<!-- Who Should Enrol -->
<section id="elite-who" class="py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-display text-[#0B1F4D] text-center">Who Should Enrol?</h2>
        <div class="mt-6 grid md:grid-cols-2 gap-6 items-start">
            <div>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-emerald-600 mt-1" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"/></svg>
                        <div>
                            <strong class="text-[#0B1F4D]">Students who love chess</strong>
                            <p class="text-gray-600">Passionate players who want clear progression.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-emerald-600 mt-1" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"/></svg>
                        <div>
                            <strong class="text-[#0B1F4D]">Students willing to practise</strong>
                            <p class="text-gray-600">Those ready to commit time to assignments and study.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-emerald-600 mt-1" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"/></svg>
                        <div>
                            <strong class="text-[#0B1F4D]">Students wanting to represent their schools</strong>
                            <p class="text-gray-600">Ambitious players aiming for team selection and representation.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-emerald-600 mt-1" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"/></svg>
                        <div>
                            <strong class="text-[#0B1F4D]">Students willing to play tournaments</strong>
                            <p class="text-gray-600">Ready to test their skills in real competitive settings.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-emerald-600 mt-1" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"/></svg>
                        <div>
                            <strong class="text-[#0B1F4D]">Students committed to improving</strong>
                            <p class="text-gray-600">Consistent learners who respond to feedback.</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div>
                <div class="rounded-2xl border-l-4 border-amber-400 bg-white p-5 shadow-sm">
                    <h4 class="font-semibold text-[#0B1F4D]">Reminder</h4>
                    <p class="mt-2 text-gray-700">This programme is <strong>voluntary</strong>. Students join because they choose to pursue competitive excellence.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Training Schedule -->
<section id="elite-schedule" class="py-16 bg-gray-50 dark:bg-slate-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-display text-[#0B1F4D] text-center">Training Schedule</h2>
        <p class="mt-3 text-center text-gray-700">Hands-on live coaching and structured practice to build tournament-ready skills.</p>

        <div class="mt-8 space-y-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-[#0B1F4D]">Weekly Cadence</h3>
                        <p class="text-gray-600">2–3 coaching sessions weekly · 2–3 hours per session · Live, interactive coaching</p>
                    </div>
                    <div class="text-3xl font-bold text-[#0B1F4D]">2–3× / week</div>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h4 class="font-semibold text-[#0B1F4D]">Technical Focus</h4>
                    <ul class="mt-2 text-gray-600 list-disc list-inside">
                        <li>Opening preparation</li>
                        <li>Middlegame strategy</li>
                        <li>Endgame mastery</li>
                    </ul>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h4 class="font-semibold text-[#0B1F4D]">Practice & Analysis</h4>
                    <ul class="mt-2 text-gray-600 list-disc list-inside">
                        <li>Game analysis</li>
                        <li>Assignments</li>
                        <li>Performance reviews</li>
                    </ul>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h4 class="font-semibold text-[#0B1F4D]">Simulations</h4>
                    <ul class="mt-2 text-gray-600 list-disc list-inside">
                        <li>Tournament simulations</li>
                        <li>Match preparation</li>
                        <li>Time control practice</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tournament Preparation -->
<section id="elite-tournaments" class="py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-display text-[#0B1F4D] text-center">Tournament Preparation</h2>
        <p class="mt-3 text-center text-gray-700">We guide players through local, state, national and online competitions with targeted preparation and entry support.</p>

        <div class="mt-8 grid md:grid-cols-2 gap-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h4 class="font-semibold text-[#0B1F4D]">Competition Types</h4>
                <ul class="mt-3 text-gray-600 space-y-2">
                    <li>Local tournaments</li>
                    <li>State championships</li>
                    <li>National tournaments</li>
                    <li>Online rapid events</li>
                </ul>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h4 class="font-semibold text-[#0B1F4D]">Long-term Targets</h4>
                <ul class="mt-3 text-gray-600 space-y-2">
                    <li>Ecobank National School Team Championship</li>
                    <li>ChessMaster Tournament</li>
                    <li>National Youth Championships</li>
                    <li>Other major national competitions</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Online Tournament Participation -->
<section id="elite-online" class="py-16 bg-gray-50 dark:bg-slate-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-display text-[#0B1F4D] text-center">Online Tournament Participation</h2>
        <div class="mt-8 grid md:grid-cols-3 gap-6">
            <div class="rounded-2xl bg-white p-6 shadow-sm text-center">
                <h4 class="font-semibold text-[#0B1F4D]">Play</h4>
                <p class="text-gray-600 mt-2">Regular online matches to sharpen instincts.</p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm text-center">
                <h4 class="font-semibold text-[#0B1F4D]">Analyse</h4>
                <p class="text-gray-600 mt-2">Post-game reviews with coach feedback.</p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm text-center">
                <h4 class="font-semibold text-[#0B1F4D]">Improve</h4>
                <p class="text-gray-600 mt-2">Targeted drills for consistent progress.</p>
            </div>
        </div>
    </div>
</section>

<!-- Benefits -->
<section id="elite-benefits" class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-display text-[#0B1F4D] text-center">Benefits of Enrolment</h2>
        <div class="mt-8 grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @include('components.elite.icon-card', ['title' => 'Professional Coaching', 'description' => 'Experienced instructors with competitive track records.'])
            @include('components.elite.icon-card', ['title' => 'Advanced Openings', 'description' => 'Tailored repertoires for each student.'])
            @include('components.elite.icon-card', ['title' => 'Tactical Training', 'description' => 'Daily tactics to sharpen calculation.'])
            @include('components.elite.icon-card', ['title' => 'Endgame Mastery', 'description' => 'Techniques to convert advantages reliably.'])
            @include('components.elite.icon-card', ['title' => 'Game Analysis', 'description' => 'In-depth post-game feedback.'])
            @include('components.elite.icon-card', ['title' => 'Online Tournaments', 'description' => 'Regular match play and simulations.'])
            @include('components.elite.icon-card', ['title' => 'Performance Evaluation', 'description' => 'Periodic reviews and growth plans.'])
            @include('components.elite.icon-card', ['title' => 'Leadership & Confidence', 'description' => 'Developing leaders through chess.'])
        </div>
    </div>
</section>

<!-- Programme Fee -->
<section id="elite-fees" class="py-16 bg-gray-50 dark:bg-slate-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-white border border-gray-200 p-8 shadow-xl">
            <div class="flex items-center justify-between gap-6">
                <div>
                    <h3 class="text-2xl font-extrabold text-[#0B1F4D]">Programme Fee</h3>
                    <p class="mt-2 text-gray-700">Monthly tuition — flexible enrolment, premium support, and tournament-focused coaching.</p>
                </div>
                <div class="text-right">
                    <div class="inline-flex items-center gap-3">
                        <span class="rounded-full px-3 py-1 text-xs font-semibold bg-amber-100 text-amber-700">Best Value</span>
                    </div>
                    <div class="mt-4 text-4xl font-bold text-[#0B1F4D]">₦25,000</div>
                    <div class="mt-1 text-sm text-gray-600">Monthly tuition</div>
                </div>
            </div>

            <div class="mt-6 grid md:grid-cols-2 gap-4">
                <ul class="list-disc list-inside text-gray-700">
                    <li>Live coaching sessions</li>
                    <li>Individualised opening prep</li>
                    <li>Game analysis & assignments</li>
                    <li>Access to online practice pools</li>
                </ul>
                <div class="text-sm text-gray-600">Tournament registration fees may be separate and communicated before each event.</div>
            </div>

            <div class="mt-6 text-right">
                <a href="https://forms.gle/5zxFtYQiDmVoT7LE7" target="_blank" rel="noopener" class="inline-flex items-center rounded-full px-6 py-3 bg-[#0B1F4D] text-white font-semibold">Register Now</a>
            </div>
        </div>
    </div>
</section>

<!-- Parent Commitment -->
<section id="elite-commit" class="py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-display text-[#0B1F4D] text-center">Parent Commitment</h2>
        <p class="mt-3 text-center text-gray-700">A supportive home environment amplifies progress. These are simple commitments that make a big difference.</p>

        <div class="mt-8 grid md:grid-cols-4 gap-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center shadow-sm">
                <h4 class="font-semibold">Attendance</h4>
                <p class="text-sm text-gray-600 mt-2">Ensure timely participation in sessions.</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center shadow-sm">
                <h4 class="font-semibold">Practice</h4>
                <p class="text-sm text-gray-600 mt-2">Encourage daily tactics and assignments.</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center shadow-sm">
                <h4 class="font-semibold">Tournament Participation</h4>
                <p class="text-sm text-gray-600 mt-2">Support entries and travel where needed.</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center shadow-sm">
                <h4 class="font-semibold">Positive Support</h4>
                <p class="text-sm text-gray-600 mt-2">Celebrate effort and progress, not just wins.</p>
            </div>
        </div>
    </div>
</section>

<!-- Registration CTA -->
<section id="elite-cta" class="py-16 bg-gradient-to-br from-[#0B1F4D] to-[#063321] text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-display">Ready to Raise a Future Chess Champion?</h2>
        <p class="mt-4 text-gray-200">Join Genchess Elite for mentor-led coaching and competition readiness. Spaces are limited to ensure quality instruction.</p>
        <div class="mt-6">
                <a href="https://forms.gle/5zxFtYQiDmVoT7LE7" target="_blank" rel="noopener" class="inline-flex items-center rounded-full px-8 py-4 bg-amber-400 text-[#0B1F4D] font-bold">Register Now</a>
        </div>
    </div>
</section>

<!-- FAQ & Accordion -->
<section id="elite-faq" class="py-16 bg-gray-50 dark:bg-slate-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-display text-[#0B1F4D] text-center">Frequently Asked Questions</h2>
        <div class="mt-6 space-y-3">
            <div class="border rounded-lg bg-white p-4">
                <button class="w-full text-left flex items-center justify-between faq-toggle"><span><strong>Who can enrol?</strong><div class="text-sm text-gray-600">Any child who shows interest in chess and a willingness to learn.</div></span><span class="ml-4">+</span></button>
                <div class="mt-3 faq-content hidden text-gray-700">Students aged 6 and up, beginners with interest, and experienced players seeking competitive development are welcome.</div>
            </div>
            <div class="border rounded-lg bg-white p-4">
                <button class="w-full text-left flex items-center justify-between faq-toggle"><span><strong>Is it compulsory?</strong><div class="text-sm text-gray-600">No — participation is voluntary.</div></span><span class="ml-4">+</span></button>
                <div class="mt-3 faq-content hidden text-gray-700">This programme is optional and designed for those pursuing competitive progress.</div>
            </div>
            <div class="border rounded-lg bg-white p-4">
                <button class="w-full text-left flex items-center justify-between faq-toggle"><span><strong>What devices are needed?</strong><div class="text-sm text-gray-600">A phone, tablet or laptop with a stable internet connection.</div></span><span class="ml-4">+</span></button>
                <div class="mt-3 faq-content hidden text-gray-700">Most classes work well on tablets and laptops; ensure good audio and a quiet space for focus.</div>
            </div>
            <div class="border rounded-lg bg-white p-4">
                <button class="w-full text-left flex items-center justify-between faq-toggle"><span><strong>How are classes conducted?</strong><div class="text-sm text-gray-600">Live online sessions via secure platforms with interactive boards.</div></span><span class="ml-4">+</span></button>
                <div class="mt-3 faq-content hidden text-gray-700">We use interactive teaching, screen-sharing and live game analysis to maximise learning.</div>
            </div>
        </div>
    </div>
</section>

@include('components.elite.footer')

<!-- Back to top & scripts -->
<button id="back-to-top" class="fixed bottom-6 right-6 bg-[#0B1F4D] text-white p-3 rounded-full shadow-lg hidden" aria-label="Back to top">↑</button>

@endsection

@push('scripts')
<script>
// Smooth scroll for internal links
document.querySelectorAll('a[href^="#"]').forEach(a=>{
    a.addEventListener('click', function(e){
        const target = document.querySelector(this.getAttribute('href'));
        if(target){ e.preventDefault(); target.scrollIntoView({behavior:'smooth', block:'start'}); }
    });
});

// Back to top
const btt = document.getElementById('back-to-top');
window.addEventListener('scroll', ()=>{ if(window.scrollY>400) btt.classList.remove('hidden'); else btt.classList.add('hidden'); });
btt.addEventListener('click', ()=>window.scrollTo({top:0,behavior:'smooth'}));

// Simple FAQ accordion
document.querySelectorAll('.faq-toggle').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        const content = btn.parentElement.querySelector('.faq-content');
        const open = !content.classList.contains('hidden');
        document.querySelectorAll('.faq-content').forEach(c=>c.classList.add('hidden'));
        document.querySelectorAll('.faq-toggle span:last-child').forEach(s=>s.textContent='+');
        if(open===false){ content.classList.remove('hidden'); btn.querySelector('span:last-child').textContent='-'; }
    });
});

// Simple scroll reveal
const srItems = document.querySelectorAll('section, .icon-card');
const sr = new IntersectionObserver(entries =>{
    entries.forEach(e=>{ if(e.isIntersecting) e.target.classList.add('gc-fade-up'); });
},{threshold:0.12});
srItems.forEach(i=>sr.observe(i));
</script>
@endpush

@extends('layouts.public')

@section('title', 'Genchess.ng | Raise Focused, Confident Children Through Chess')
@section('description', 'Genchess Educational Services Ltd helps children in schools, homes, and communities build focus, confidence, and strong thinking skills through storytelling-based chess education in Nigeria.')
@section('keywords', 'Genchess Nigeria, chess for kids Nigeria, chess in schools, chess home lessons, chess communities')
@section('image', asset('images/hero/genchess-hero.jpg'))

@section('content')
@php
    $whatsAppLink = 'https://wa.me/2348078462223?text=' . urlencode('Hello Genchess, I want to book a free trial for my child.');
    $trialLink = route('chess.communities.homes') . '#booking-form';
    $schoolLink = route('register.school');
    $communityLink = route('chess.communities.homes');
@endphp

<style>
    .gc-home-bg {
        background:
            radial-gradient(1100px 520px at 10% -10%, rgba(228, 178, 72, 0.28) 0%, transparent 62%),
            radial-gradient(800px 400px at 100% 10%, rgba(26, 95, 74, 0.22) 0%, transparent 65%),
            linear-gradient(180deg, #fbf7ee 0%, #fff 62%);
    }

    .gc-fade-up {
        animation: gcFadeUp 0.7s ease-out both;
    }

    .gc-fade-up-delay {
        animation: gcFadeUp 0.95s ease-out both;
    }

    .gc-whatsapp-float {
        position: fixed;
        right: 1rem;
        bottom: 1rem;
        z-index: 60;
    }

    @keyframes gcFadeUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (min-width: 768px) {
        .gc-whatsapp-float {
            right: 1.5rem;
            bottom: 1.5rem;
        }
    }
</style>

<section class="gc-home-bg relative overflow-hidden py-14 md:py-20 dark:bg-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 grid lg:grid-cols-2 gap-10 items-center">
        <div class="gc-fade-up">
            <p class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-900 text-xs font-semibold tracking-wide uppercase">
                unlocking the genius within every child through chess
            </p>
            <h1 class="mt-5 text-4xl md:text-5xl leading-tight font-display text-slate-900 dark:text-slate-100">
                Raise a child who can think clearly, solve problems, and lead with confidence.
            </h1>
            <p class="mt-5 text-base md:text-lg text-slate-700 dark:text-slate-300 max-w-xl">
                At Genchess Educational Services Ltd, children do not just learn chess rules. They learn how to stay calm, think ahead, and make wise decisions in school and in life.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row gap-3">
                <a href="{{ $trialLink }}" class="inline-flex items-center justify-center rounded-xl px-6 py-3 font-semibold text-white bg-[#8b5e34] hover:bg-[#72482a] transition">
                    Book a Free Trial
                </a>
                <a href="{{ $whatsAppLink }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-xl px-6 py-3 font-semibold text-white border border-purple-300/40 bg-purple-900 hover:bg-purple-950 transition">
                    Chat on WhatsApp
                </a>
            </div>
            <p class="mt-4 text-sm text-slate-600 dark:text-slate-300">Trusted by parents, schools, and communities across Nigeria.</p>
        </div>

        <div class="gc-fade-up-delay relative">
            <div class="absolute -inset-3 rounded-[2rem] bg-gradient-to-br from-amber-200/50 via-transparent to-emerald-200/45 blur-md"></div>
            <img
                src="{{ $homepageHeroImage }}"
                alt="Happy children learning chess with a Genchess instructor"
                class="relative w-full rounded-[1.75rem] border border-amber-200/60 shadow-xl object-cover"
            >
        </div>
    </div>
</section>

<section class="py-14 md:py-20 bg-purple-50 dark:bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 grid lg:grid-cols-2 gap-8 md:gap-10 items-start">
        <div>
            <h2 class="text-3xl md:text-4xl font-display text-slate-900 dark:text-slate-100">The Chess Empire of Sixty-Four Estates</h2>
            <p class="mt-4 text-slate-700 dark:text-slate-300">
                Every Genchess lesson is a journey into a living story world called <strong>The Chess Empire of Sixty-Four Estates</strong>.
            </p>
            <p class="mt-3 text-slate-700 dark:text-slate-300">
                The King learns responsibility. The Queen models courage. The Rooks protect community. Through stories children understand values, not only moves.
            </p>
            <p class="mt-3 text-slate-700 dark:text-slate-300">
                This makes learning fun for young minds and easier for parents to connect chess to behavior, confidence, and classroom success.
            </p>
            <a href="{{ route('about') }}" class="mt-6 inline-flex items-center justify-center rounded-xl px-5 py-3 font-semibold text-slate-900 dark:text-slate-100 border border-[#d8c5a8] bg-[#fff7e8] dark:bg-slate-800 hover:bg-[#fcefd8] dark:hover:bg-slate-700 transition">
                Read the Story Philosophy
            </a>
        </div>

        <div class="rounded-2xl border border-[#dfd0b8] dark:border-slate-700 bg-white dark:bg-slate-800 p-3 shadow-sm">
            <div class="aspect-video rounded-xl overflow-hidden bg-slate-100">
                <iframe
                    class="h-full w-full"
                    src="https://www.youtube.com/embed/bvf8ZuZzarY"
                    title="The Chess Empire of Sixty-Four Estates"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen
                ></iframe>
            </div>
            <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">
                Suggested video: A short introduction to Genchess storytelling classes and student transformation.
            </p>
        </div>
    </div>
</section>

<section class="py-14 md:py-20 bg-purple-100/40 dark:bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <h2 class="text-3xl md:text-4xl font-display text-slate-900 dark:text-slate-100">Why Parents Choose Genchess</h2>
        <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <article class="rounded-2xl border border-amber-100 dark:border-slate-700 bg-[#fffaf0] dark:bg-slate-800 p-5">
                <p class="font-semibold text-slate-900 dark:text-slate-100">Improves concentration</p>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Children learn to focus deeply, listen carefully, and complete tasks.</p>
            </article>
            <article class="rounded-2xl border border-emerald-100 dark:border-slate-700 bg-[#f2fbf7] dark:bg-slate-800 p-5">
                <p class="font-semibold text-slate-900 dark:text-slate-100">Builds problem-solving skills</p>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Every game teaches your child to think before acting and find smart solutions.</p>
            </article>
            <article class="rounded-2xl border border-amber-100 dark:border-slate-700 bg-[#fffaf0] dark:bg-slate-800 p-5">
                <p class="font-semibold text-slate-900 dark:text-slate-100">Boosts confidence</p>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">With each lesson and match, shy children become bolder and more expressive.</p>
            </article>
            <article class="rounded-2xl border border-emerald-100 dark:border-slate-700 bg-[#f2fbf7] dark:bg-slate-800 p-5">
                <p class="font-semibold text-slate-900 dark:text-slate-100">Enhances academic performance</p>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Students apply chess logic to mathematics, reading, and class decision-making.</p>
            </article>
        </div>
    </div>
</section>

<section class="py-14 md:py-20 bg-purple-50 dark:bg-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <h2 class="text-3xl md:text-4xl font-display text-slate-900 dark:text-slate-100">Programs Designed for Every Child</h2>
        <p class="mt-3 text-slate-700 dark:text-slate-300 max-w-3xl">From school timetables to living rooms and estates, we make quality chess education accessible.</p>

        <div class="mt-8 grid md:grid-cols-3 gap-5">
            <article class="rounded-2xl border border-[#ddc8a7] dark:border-slate-700 bg-white dark:bg-slate-800 p-4">
                <img src="{{ $homepageServiceImages['schools'] }}" alt="Chess in schools program" class="h-44 w-full rounded-xl object-cover border border-[#eee0c7]">
                <h3 class="mt-4 text-xl font-semibold text-slate-900 dark:text-slate-100">Schools Program</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Structured classes for primary and secondary schools, delivered by trained Genchess instructors.</p>
                <a href="{{ $schoolLink }}" class="mt-4 inline-flex rounded-lg px-4 py-2 text-sm font-semibold text-white bg-[#8b5e34] hover:bg-[#72482a] transition">Enroll Your School</a>
            </article>

            <article class="rounded-2xl border border-[#cce3d7] dark:border-slate-700 bg-white dark:bg-slate-800 p-4">
                <img src="{{ $classroomImages['play'] ?? $homepageHeroImage }}" alt="Chess at home with children" class="h-44 w-full rounded-xl object-cover border border-[#dceee6]">
                <h3 class="mt-4 text-xl font-semibold text-slate-900 dark:text-slate-100">Homes Program</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Private or small-group lessons for children at home, online or physical, based on your schedule.</p>
                <a href="{{ $trialLink }}" class="mt-4 inline-flex rounded-lg px-4 py-2 text-sm font-semibold text-[#114232] dark:text-[#0f2e23] bg-[#dff3ea] dark:bg-[#d3ecdf] hover:bg-[#cfeede] transition">Book Home Trial</a>
            </article>

            <article class="rounded-2xl border border-[#ddc8a7] dark:border-slate-700 bg-white dark:bg-slate-800 p-4">
                <img src="{{ $homepageServiceImages['communities'] }}" alt="Chess in communities program" class="h-44 w-full rounded-xl object-cover border border-[#eee0c7]">
                <h3 class="mt-4 text-xl font-semibold text-slate-900 dark:text-slate-100">Communities Program</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Estate, church, and youth-center chess clubs that build unity, character, and healthy competition.</p>
                <a href="{{ $communityLink }}#booking-form" class="mt-4 inline-flex rounded-lg px-4 py-2 text-sm font-semibold text-white bg-[#8b5e34] hover:bg-[#72482a] transition">Start Community Club</a>
            </article>
        </div>
    </div>
</section>

<section class="py-14 md:py-20 bg-white dark:bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid lg:grid-cols-2 gap-8 items-start">
            <div>
                <h2 class="text-3xl md:text-4xl font-display text-slate-900 dark:text-slate-100">Become a Genchess Instructor</h2>
                <p class="mt-3 text-slate-700 dark:text-slate-300">
                    Passionate about teaching children? Join our growing team and help raise Africa's next generation of strategic thinkers.
                </p>
                <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-md">
                    <img src="{{ $homepageInstructorImage }}" alt="Genchess instructor teaching children chess" class="w-full h-64 object-cover">
                </div>
            </div>

            <div class="space-y-4">
                <article class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Apply to Become a Chess Instructor</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                        For experienced candidates. Complete screening and interviews to join our teaching network.
                    </p>
                    <a href="{{ route('instructor.screening.create') }}" class="mt-4 inline-flex rounded-lg px-4 py-2 text-sm font-semibold text-white bg-purple-900 hover:bg-purple-950 transition">
                        Start Instructor Application
                    </a>
                </article>

                <article class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Apply for Instructor Training Program</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                        New to structured chess teaching? Join the Genchess Certified Chess Instructor Program (GCCIP).
                    </p>
                    <a href="{{ route('instructor.training') }}" class="mt-4 inline-flex rounded-lg px-4 py-2 text-sm font-semibold text-white bg-purple-900 hover:bg-purple-950 transition">
                        Apply for Training Program
                    </a>
                </article>
            </div>
        </div>
    </div>
</section>

<section class="py-14 md:py-20 bg-purple-100/30 dark:bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <h2 class="text-3xl md:text-4xl font-display text-slate-900 dark:text-slate-100">Parents See the Difference</h2>
        <div class="mt-8 grid md:grid-cols-3 gap-4">
            <blockquote class="rounded-2xl border border-[#e7dcc8] dark:border-slate-700 bg-[#fffdf8] dark:bg-slate-800 p-5 shadow-md">
                <p class="text-slate-700 dark:text-slate-300">"After 6 weeks, my son became calmer during homework and more patient with his siblings."</p>
                <cite class="mt-3 block text-sm font-semibold text-slate-900 dark:text-slate-100">Parent Testimonial Placeholder</cite>
            </blockquote>
            <blockquote class="rounded-2xl border border-[#e7dcc8] dark:border-slate-700 bg-[#fffdf8] dark:bg-slate-800 p-5 shadow-md">
                <p class="text-slate-700 dark:text-slate-300">"Our pupils now think before answering. We can clearly see better reasoning in class."</p>
                <cite class="mt-3 block text-sm font-semibold text-slate-900 dark:text-slate-100">School Testimonial Placeholder</cite>
            </blockquote>
            <blockquote class="rounded-2xl border border-[#e7dcc8] dark:border-slate-700 bg-[#fffdf8] dark:bg-slate-800 p-5 shadow-md">
                <p class="text-slate-700 dark:text-slate-300">"My daughter used to fear competitions. Now she asks to join every chess challenge."</p>
                <cite class="mt-3 block text-sm font-semibold text-slate-900 dark:text-slate-100">Parent Testimonial Placeholder</cite>
            </blockquote>
        </div>

        <div class="mt-8 rounded-2xl border border-[#cce3d7] dark:border-slate-700 bg-[#f2fbf7] dark:bg-slate-800 p-6 shadow-md">
            <h3 class="text-2xl font-display text-emerald-900 dark:text-emerald-200">Student Success Stories</h3>
            <p class="mt-2 text-emerald-900 dark:text-emerald-200">
                Placeholder for before-and-after stories: confidence growth, improved grades, tournament milestones, and leadership wins.
            </p>
            <a href="{{ route('tournaments') }}" class="mt-4 inline-flex rounded-lg px-4 py-2 text-sm font-semibold text-[#114232] dark:text-[#0f2e23] bg-white dark:bg-[#e8f6ef] border border-[#afd5c3] hover:bg-[#f7fffb] transition">
                View Student Milestones
            </a>
        </div>
    </div>
</section>

<section class="py-14 md:py-20 bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <h2 class="text-3xl md:text-4xl font-display">How It Works</h2>
        <div class="mt-8 grid md:grid-cols-3 gap-4">
            <article class="rounded-2xl border border-white/20 bg-white/10 p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-amber-200">Step 1</p>
                <h3 class="mt-2 text-xl font-semibold">Sign up</h3>
                <p class="mt-2 text-sm text-amber-50/90">Book a free trial for home, community, or school learning.</p>
            </article>
            <article class="rounded-2xl border border-white/20 bg-white/10 p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-amber-200">Step 2</p>
                <h3 class="mt-2 text-xl font-semibold">Training begins</h3>
                <p class="mt-2 text-sm text-amber-50/90">Your child starts guided lessons with fun storytelling and structured chess practice.</p>
            </article>
            <article class="rounded-2xl border border-white/20 bg-white/10 p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-amber-200">Step 3</p>
                <h3 class="mt-2 text-xl font-semibold">Child improves</h3>
                <p class="mt-2 text-sm text-amber-50/90">Watch growth in focus, confidence, discipline, and school performance.</p>
            </article>
        </div>
    </div>
</section>

<section class="py-16 md:py-20 bg-gradient-to-br from-[#063321] via-[#0b442d] to-[#14523a] text-black">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 text-center">
        <h2 class="text-3xl md:text-5xl font-display leading-tight">Give your child a mind that sees possibilities, not problems.</h2>
        <p class="mt-4 text-emerald-50/95 max-w-3xl mx-auto">
            The next generation of African leaders is being shaped one thoughtful move at a time. Start your child's Genchess journey today.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
            <a href="{{ $trialLink }}" class="inline-flex items-center justify-center rounded-xl px-6 py-3 font-semibold text-[#0f5132] dark:text-[#2f1f14] bg-[#f5d18c] dark:bg-[#f2cc82] hover:bg-[#efc26f] transition">
                Book a Free Trial
            </a>
            <a href="{{ $whatsAppLink }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-xl px-6 py-3 font-semibold text-white border border-purple-300/40 bg-purple-900 hover:bg-purple-950 transition">
                Chat on WhatsApp
            </a>
        </div>
    </div>
</section>

<a
    href="{{ $whatsAppLink }}"
    target="_blank"
    rel="noopener"
    class="gc-whatsapp-float inline-flex items-center gap-2 rounded-full px-4 py-3 bg-purple-900 text-white font-semibold border border-purple-300/40 shadow-lg hover:bg-purple-950 transition"
    aria-label="Chat with Genchess on WhatsApp"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M19.05 4.94A9.87 9.87 0 0012.02 2C6.56 2 2.11 6.45 2.1 11.92c0 1.75.46 3.47 1.33 4.99L2 22l5.24-1.37a9.87 9.87 0 004.77 1.22h.01c5.47 0 9.92-4.45 9.93-9.92a9.83 9.83 0 00-2.9-6.99zm-7.03 15.2h-.01a8.16 8.16 0 01-4.16-1.14l-.3-.18-3.11.81.83-3.03-.2-.31a8.16 8.16 0 01-1.26-4.37c0-4.51 3.67-8.19 8.2-8.19a8.14 8.14 0 015.8 2.4 8.14 8.14 0 012.39 5.8c0 4.52-3.68 8.2-8.18 8.21zm4.49-6.12c-.24-.12-1.41-.7-1.63-.78-.22-.08-.38-.12-.55.12-.16.24-.63.78-.77.94-.14.16-.29.18-.53.06-.24-.12-1.03-.38-1.97-1.22-.73-.65-1.22-1.45-1.36-1.69-.14-.24-.02-.37.1-.49.11-.11.24-.29.36-.43.12-.14.16-.24.24-.41.08-.16.04-.31-.02-.43-.06-.12-.55-1.32-.75-1.81-.2-.47-.41-.41-.55-.42l-.47-.01c-.16 0-.43.06-.65.3-.22.24-.85.83-.85 2.02 0 1.19.87 2.34.99 2.5.12.16 1.7 2.6 4.12 3.65.58.25 1.04.4 1.39.51.58.19 1.1.16 1.52.1.46-.07 1.41-.58 1.61-1.14.2-.56.2-1.04.14-1.14-.06-.1-.22-.16-.46-.28z"/>
    </svg>
    WhatsApp
</a>
@endsection

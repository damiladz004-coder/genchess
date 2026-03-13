@extends('layouts.public')

@section('content')
<section class="py-12">
    <div class="max-w-5xl mx-auto px-6 space-y-6">
        <div>
            <h1 class="text-3xl gc-heading">Genchess Instructor Screening Test (Level 1)</h1>
            <p class="text-slate-600 mt-2">For experienced candidates. One trial only. Pass mark: 80%. Time limit: 15 minutes.</p>
            <p class="text-sm text-slate-600 mt-1">Next stages after passing: Stage 2 interview (chess knowledge) and Stage 3 interview (teaching/classroom management).</p>
            <p class="text-sm font-semibold mt-2">Time left: <span id="time-left">{{ gmdate('i:s', $remainingSeconds) }}</span></p>
        </div>

        @if(session('error'))
            <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="screening-form" method="POST" action="{{ route('instructor.screening.store') }}" class="space-y-5">
            @csrf
            <div class="gc-panel p-4 grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Location</label>
                    <input type="text" name="location" value="{{ old('location') }}" placeholder="City / State">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Preferred Interview Mode</label>
                    <select name="interview_mode" required>
                        <option value="zoom" @selected(old('interview_mode') === 'zoom')>Online (Zoom)</option>
                        <option value="physical" @selected(old('interview_mode') === 'physical')>Physical</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Preferred Interview Day</label>
                    <input type="date" name="preferred_interview_date" value="{{ old('preferred_interview_date') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Preferred Interview Time</label>
                    <input type="time" name="preferred_interview_time" value="{{ old('preferred_interview_time') }}">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-600 mb-1">Preferred Time Notes</label>
                    <textarea name="preferred_interview_notes" rows="2" placeholder="Share suitable days, time windows, or timezone details">{{ old('preferred_interview_notes') }}</textarea>
                </div>
            </div>

            @php
                $sections = ['A' => 'Chess Knowledge', 'B' => 'Lesson Planning', 'C' => 'Classroom Management', 'D' => 'Teaching Methods', 'E' => 'Instructional Materials'];
            @endphp

            @php
                $flatQuestions = collect($quiz)->values();
            @endphp
            <div class="gc-panel p-4">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <h2 class="text-xl font-semibold">Screening Questions</h2>
                    <p class="text-sm text-slate-600">Question <span id="current-question-index">1</span> of {{ $flatQuestions->count() }}</p>
                </div>

                <div class="space-y-4">
                    @foreach($flatQuestions as $index => $question)
                        @php
                            $sectionTitle = $sections[$question['section']] ?? 'General';
                        @endphp
                        <div
                            class="question-card {{ $index === 0 ? '' : 'hidden' }}"
                            data-question-card
                            data-index="{{ $index }}"
                        >
                            <p class="text-xs font-semibold text-slate-500 mb-1">{{ $question['section'] }}. {{ $sectionTitle }}</p>
                            <p class="font-medium mb-2">{{ $question['id'] }}. {{ $question['prompt'] }}</p>
                            <div class="space-y-2">
                                @foreach($question['options'] as $label => $text)
                                    <label class="flex items-start gap-2">
                                        <input
                                            type="radio"
                                            name="answers[{{ $question['id'] }}]"
                                            value="{{ $label }}"
                                            data-question-option="{{ $index }}"
                                            @checked(old('answers.' . $question['id']) === $label)
                                            required
                                        >
                                        <span>{{ strtoupper($label) }}. {{ $text }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="gc-btn-primary">Submit Screening Test</button>
        </form>
    </div>
</section>

<script>
    (function () {
        let remaining = {{ (int) $remainingSeconds }};
        const timeEl = document.getElementById('time-left');
        const form = document.getElementById('screening-form');
        const questionCards = Array.from(document.querySelectorAll('[data-question-card]'));
        const currentQuestionEl = document.getElementById('current-question-index');

        function format(seconds) {
            const m = String(Math.floor(seconds / 60)).padStart(2, '0');
            const s = String(seconds % 60).padStart(2, '0');
            return `${m}:${s}`;
        }

        function findFirstUnansweredIndex() {
            for (let i = 0; i < questionCards.length; i += 1) {
                const radios = Array.from(questionCards[i].querySelectorAll('input[type="radio"]'));
                const hasAnswer = radios.some((radio) => radio.checked);
                if (!hasAnswer) {
                    return i;
                }
            }

            return Math.max(questionCards.length - 1, 0);
        }

        function setQuestionState(activeIndex) {
            questionCards.forEach((card, idx) => {
                const radios = Array.from(card.querySelectorAll('input[type="radio"]'));
                const shouldShow = idx <= activeIndex;
                const shouldEnable = idx <= activeIndex;

                card.classList.toggle('hidden', !shouldShow);
                radios.forEach((radio) => {
                    radio.disabled = !shouldEnable;
                });
            });

            if (currentQuestionEl) {
                currentQuestionEl.textContent = String(Math.min(activeIndex + 1, questionCards.length));
            }
        }

        let activeIndex = findFirstUnansweredIndex();
        setQuestionState(activeIndex);

        document.querySelectorAll('[data-question-option]').forEach((radio) => {
            radio.addEventListener('change', function () {
                const idx = Number(this.getAttribute('data-question-option'));
                if (Number.isNaN(idx)) {
                    return;
                }

                if (idx >= activeIndex) {
                    activeIndex = Math.min(idx + 1, questionCards.length - 1);
                }

                setQuestionState(activeIndex);
            });
        });

        const timer = setInterval(() => {
            remaining -= 1;
            timeEl.textContent = format(Math.max(0, remaining));
            if (remaining <= 0) {
                clearInterval(timer);
                alert('Time is up. Your test will be submitted now.');
                form.submit();
            }
        }, 1000);
    })();
</script>
@endsection

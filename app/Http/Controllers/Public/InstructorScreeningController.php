<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\InstructorScreeningInvitation;
use App\Models\InstructorScreening;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class InstructorScreeningController extends Controller
{
    private const PASS_PERCENTAGE = 80;
    private const TIME_LIMIT_SECONDS = 900;

    public function create(Request $request)
    {
        $quiz = $request->session()->get('instructor_screening.quiz');
        $startedAt = $request->session()->get('instructor_screening.started_at');

        if (!$quiz || !$startedAt) {
            $quiz = $this->buildQuiz();
            $startedAt = now()->toIso8601String();
            $request->session()->put('instructor_screening.quiz', $quiz);
            $request->session()->put('instructor_screening.started_at', $startedAt);
        }

        $remainingSeconds = max(0, self::TIME_LIMIT_SECONDS - now()->diffInSeconds($startedAt));

        return view('public.instructor-screening', [
            'quiz' => $quiz,
            'remainingSeconds' => $remainingSeconds,
        ]);
    }

    public function store(Request $request)
    {
        $quiz = $request->session()->get('instructor_screening.quiz');
        $startedAtRaw = $request->session()->get('instructor_screening.started_at');

        if (!$quiz || !$startedAtRaw) {
            return redirect()->route('instructor.screening.create')
                ->with('error', 'Screening session expired. Please start again.');
        }

        $startedAt = Carbon::parse($startedAtRaw);
        $elapsed = now()->diffInSeconds($startedAt);
        if ($elapsed > self::TIME_LIMIT_SECONDS) {
            $request->session()->forget('instructor_screening');
            return redirect()->route('instructor.screening.create')
                ->with('error', 'Time limit exceeded. You can no longer submit this test.');
        }

        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'location' => 'nullable|string|max:255',
            'interview_mode' => 'required|in:zoom,physical',
            'preferred_interview_date' => 'nullable|date|after_or_equal:today',
            'preferred_interview_time' => 'nullable|date_format:H:i',
            'preferred_interview_notes' => 'nullable|string|max:1000',
        ];

        foreach ($quiz as $question) {
            $validationRules['answers.' . $question['id']] = 'required|in:a,b,c';
        }

        $request->validate($validationRules);

        if (InstructorScreening::where('email', $request->email)->exists()) {
            return redirect()->route('instructor.screening.create')
                ->with('error', 'Only one trial is allowed per email.');
        }

        $answers = $request->input('answers', []);
        $score = 0;
        $detailedAnswers = [];

        foreach ($quiz as $question) {
            $qid = (string) $question['id'];
            $selectedLabel = $answers[$qid] ?? null;
            $selectedText = $selectedLabel ? ($question['options'][$selectedLabel] ?? null) : null;
            $correctLabel = $question['correct'];
            $correctText = $question['options'][$correctLabel] ?? null;
            $isCorrect = $selectedLabel === $correctLabel;

            if ($isCorrect) {
                $score++;
            }

            $detailedAnswers[] = [
                'question_id' => $question['id'],
                'section' => $question['section'],
                'prompt' => $question['prompt'],
                'selected_label' => $selectedLabel,
                'selected_text' => $selectedText,
                'correct_label' => $correctLabel,
                'correct_text' => $correctText,
                'is_correct' => $isCorrect,
            ];
        }

        $totalQuestions = count($quiz);
        $percentage = $totalQuestions > 0 ? round(($score / $totalQuestions) * 100, 2) : 0;
        $passed = $percentage >= self::PASS_PERCENTAGE;

        $screening = InstructorScreening::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'location' => $request->location,
            'interview_mode' => $request->interview_mode,
            'preferred_interview_date' => $request->preferred_interview_date,
            'preferred_interview_time' => $request->preferred_interview_time,
            'preferred_interview_notes' => $request->preferred_interview_notes,
            'score' => $score,
            'total_questions' => $totalQuestions,
            'percentage' => $percentage,
            'passed' => $passed,
            'stage_two_status' => 'pending',
            'stage_three_status' => 'pending',
            'final_status' => $passed ? 'pending' : 'recommended_training',
            'training_recommended_at' => $passed ? null : now(),
            'answers_json' => $detailedAnswers,
            'started_at' => $startedAt,
            'submitted_at' => now(),
            'invitation_sent_at' => null,
        ]);

        $mailWarning = null;
        if ($passed) {
            try {
                Mail::to($screening->email)->send(new InstructorScreeningInvitation(
                    $screening->name,
                    $screening->interview_mode
                ));

                $screening->update(['invitation_sent_at' => now()]);
            } catch (\Throwable $e) {
                Log::error('Failed to send instructor screening invitation email.', [
                    'screening_id' => $screening->id,
                    'email' => $screening->email,
                    'error' => $e->getMessage(),
                ]);
                $mailWarning = 'You passed, but we could not send your invitation email right now.';
            }
        }

        $request->session()->forget('instructor_screening');

        $request->session()->put('instructor_screening.result_id', $screening->id);

        $redirect = redirect()->route('instructor.screening.result');
        if ($mailWarning) {
            $redirect->with('warning', $mailWarning);
        }

        return $redirect;
    }

    public function result(Request $request)
    {
        $resultId = $request->session()->get('instructor_screening.result_id');
        if (!$resultId) {
            abort(404);
        }

        $screening = InstructorScreening::with('instructorProfile')->findOrFail($resultId);
        $onboardingUrl = null;

        if ($screening->passed && $screening->final_status === 'approved' && !$screening->instructorProfile) {
            $onboardingUrl = URL::signedRoute('instructor.screening.biodata.create', ['screening' => $screening->id]);
        }

        return view('public.instructor-screening-result', compact('screening', 'onboardingUrl'));
    }

    private function buildQuiz(): array
    {
        return collect($this->questionBank())
            ->map(function ($question) {
                $options = collect($question['options'])->shuffle()->values();
                $labels = ['a', 'b', 'c'];
                $mapped = [];
                $correctLabel = null;

                foreach ($labels as $idx => $label) {
                    $option = $options[$idx];
                    $mapped[$label] = $option;
                    if ($option === $question['answer']) {
                        $correctLabel = $label;
                    }
                }

                return [
                    'id' => $question['id'],
                    'section' => $question['section'],
                    'prompt' => $question['prompt'],
                    'options' => $mapped,
                    'correct' => $correctLabel,
                ];
            })
            ->values()
            ->all();
    }

    private function questionBank(): array
    {
        return [
            ['id' => 1, 'section' => 'A', 'prompt' => 'How do you properly describe the chessboard?', 'options' => ['Rows, columns, diagonals', 'Files, ranks, diagonals', 'Horizontal, vertical, diagonal'], 'answer' => 'Files, ranks, diagonals'],
            ['id' => 2, 'section' => 'A', 'prompt' => 'What is the name of the piece that moves in an "L" shape?', 'options' => ['Knight', 'Queen', 'King'], 'answer' => 'Knight'],
            ['id' => 3, 'section' => 'A', 'prompt' => 'Two rooks + one queen + one knight + one bishop equals:', 'options' => ['22', '25', '23'], 'answer' => '25'],
            ['id' => 4, 'section' => 'A', 'prompt' => 'What is the symbol for capture in chess notation?', 'options' => ['x', '+', '#'], 'answer' => 'x'],
            ['id' => 5, 'section' => 'A', 'prompt' => 'An attack on the king with no escape route is called:', 'options' => ['Check', 'Checkmate', 'Stalemate'], 'answer' => 'Checkmate'],
            ['id' => 6, 'section' => 'A', 'prompt' => 'How many ways can a king be defended from check?', 'options' => ['2', '3', '5'], 'answer' => '3'],
            ['id' => 7, 'section' => 'A', 'prompt' => 'Main idea when two rooks checkmate a lone king:', 'options' => ['Control of diagonal', 'Control of file', 'Control of center'], 'answer' => 'Control of file'],
            ['id' => 8, 'section' => 'A', 'prompt' => 'What are the central squares?', 'options' => ['e4, e5, d4, d5', 'c4, d4, c5, d5', 'f4, f5, e4, e5'], 'answer' => 'e4, e5, d4, d5'],
            ['id' => 9, 'section' => 'A', 'prompt' => 'What are the minor pieces?', 'options' => ['Pawn and Knight', 'Pawn and Bishop', 'Knight and Bishop'], 'answer' => 'Knight and Bishop'],
            ['id' => 10, 'section' => 'A', 'prompt' => 'A chess game has how many phases?', 'options' => ['2 phases', '4 phases', '3 phases'], 'answer' => '3 phases'],
            ['id' => 11, 'section' => 'A', 'prompt' => 'Attacking two or more pieces at the same time is called:', 'options' => ['Fork', 'Pin', 'Skewer'], 'answer' => 'Fork'],
            ['id' => 12, 'section' => 'A', 'prompt' => 'Which is a known checkmate pattern?', 'options' => ['Anastasia\'s Mate', 'Broad Mate', 'Najdorf Mate'], 'answer' => 'Anastasia\'s Mate'],

            ['id' => 13, 'section' => 'B', 'prompt' => 'A proper chess lesson plan should include:', 'options' => ['Only tactics', 'Objective, materials, activities, and assessment', 'Only opening theory'], 'answer' => 'Objective, materials, activities, and assessment'],
            ['id' => 14, 'section' => 'B', 'prompt' => 'First step in writing a lesson plan:', 'options' => ['Choosing homework', 'Defining the lesson objective', 'Setting exam questions'], 'answer' => 'Defining the lesson objective'],
            ['id' => 15, 'section' => 'B', 'prompt' => 'A good lesson objective should be:', 'options' => ['Long and complicated', 'Clear and measurable', 'Focused on winning games'], 'answer' => 'Clear and measurable'],
            ['id' => 16, 'section' => 'B', 'prompt' => 'If objective is "understand forks", you must include:', 'options' => ['Only explanation', 'Practice exercises', 'Story about a grandmaster'], 'answer' => 'Practice exercises'],
            ['id' => 17, 'section' => 'B', 'prompt' => 'Best 30-minute beginner class structure:', 'options' => ['25 min talking, 5 min practice', '10 min explanation, 15 min practice, 5 min review', 'Entire time for playing'], 'answer' => '10 min explanation, 15 min practice, 5 min review'],
            ['id' => 18, 'section' => 'B', 'prompt' => 'Why is lesson reflection important?', 'options' => ['To impress parents', 'To improve future lessons', 'To reduce teaching time'], 'answer' => 'To improve future lessons'],

            ['id' => 19, 'section' => 'C', 'prompt' => 'If students are making noise during class, you should:', 'options' => ['Shout at them', 'Stop teaching and restore order calmly', 'Ignore them'], 'answer' => 'Stop teaching and restore order calmly'],
            ['id' => 20, 'section' => 'C', 'prompt' => 'A child keeps losing and becomes frustrated. You should:', 'options' => ['Tell them to try harder', 'Encourage and guide them', 'Remove them from class'], 'answer' => 'Encourage and guide them'],
            ['id' => 21, 'section' => 'C', 'prompt' => 'Best seating arrangement for chess teaching:', 'options' => ['Random seating', 'All facing the demo board', 'Sitting on the floor anywhere'], 'answer' => 'All facing the demo board'],
            ['id' => 22, 'section' => 'C', 'prompt' => 'If a student cheats during a game, you should:', 'options' => ['Publicly embarrass them', 'Quietly correct and teach fair play', 'Ignore it'], 'answer' => 'Quietly correct and teach fair play'],
            ['id' => 23, 'section' => 'C', 'prompt' => 'A good chess instructor should:', 'options' => ['Show favoritism', 'Treat all students fairly', 'Focus only on strong players'], 'answer' => 'Treat all students fairly'],
            ['id' => 24, 'section' => 'C', 'prompt' => 'One key classroom rule in chess club should be:', 'options' => ['Silence always', 'Respect and sportsmanship', 'Only winners talk'], 'answer' => 'Respect and sportsmanship'],

            ['id' => 25, 'section' => 'D', 'prompt' => 'Best way to teach beginners piece movement:', 'options' => ['Let them figure it out alone', 'Demonstration + guided practice', 'Give them a book only'], 'answer' => 'Demonstration + guided practice'],
            ['id' => 26, 'section' => 'D', 'prompt' => 'Most effective style for ages 5-7:', 'options' => ['Lecture method', 'Storytelling and games', 'Deep theory explanation'], 'answer' => 'Storytelling and games'],
            ['id' => 27, 'section' => 'D', 'prompt' => 'When teaching tactics, students learn best through:', 'options' => ['Memorizing definitions', 'Solving puzzles', 'Watching tournaments'], 'answer' => 'Solving puzzles'],
            ['id' => 28, 'section' => 'D', 'prompt' => 'Peer learning in chess means:', 'options' => ['Students teaching and playing each other', 'Teacher playing all students', 'Students watching YouTube'], 'answer' => 'Students teaching and playing each other'],
            ['id' => 29, 'section' => 'D', 'prompt' => 'Asking "Why did your opponent move there?" develops:', 'options' => ['Speed', 'Critical thinking', 'Memorization'], 'answer' => 'Critical thinking'],
            ['id' => 30, 'section' => 'D', 'prompt' => 'Continuous assessment in chess teaching means:', 'options' => ['One exam per term', 'Regular evaluation through games and puzzles', 'No testing'], 'answer' => 'Regular evaluation through games and puzzles'],

            ['id' => 31, 'section' => 'E', 'prompt' => 'A demo board is used to:', 'options' => ['Decorate the classroom', 'Demonstrate moves to the whole class', 'Store pieces'], 'answer' => 'Demonstrate moves to the whole class'],
            ['id' => 32, 'section' => 'E', 'prompt' => 'Chess clocks help students learn:', 'options' => ['Time management', 'Faster talking', 'Memorization'], 'answer' => 'Time management'],
            ['id' => 33, 'section' => 'E', 'prompt' => 'Online platforms like Lichess/Chess.com can be used for:', 'options' => ['Playing only', 'Homework and puzzle practice', 'Avoiding class'], 'answer' => 'Homework and puzzle practice'],
            ['id' => 34, 'section' => 'E', 'prompt' => 'Worksheets in chess class are useful for:', 'options' => ['Discipline punishment', 'Reinforcing lesson concepts', 'Decoration'], 'answer' => 'Reinforcing lesson concepts'],
        ];
    }
}

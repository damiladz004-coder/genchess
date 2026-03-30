<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\MediaPath;
use App\Models\TrainingCourse;

class PageController extends Controller
{
    public function home()
    {
        return view('public.home', [
            'homepageHeroImage' => $this->settingValue('homepage_hero_image', asset('images/hero/genchess-hero.jpg')),
            'homepageServiceImages' => [
                'schools' => $this->settingValue('homepage_schools_image', asset('images/chess-in-schools.jpg')),
                'communities' => $this->settingValue('homepage_communities_image', asset('images/placeholders/chess-in-communities.jpg')),
                'store' => $this->settingValue('homepage_store_image', asset('images/chess%20products/chessproducts.jpg')),
            ],
            'homepageInstructorImage' => $this->settingValue('homepage_instructor_image', asset('images/instructors/certified-coach.jpg')),
            'classroomImages' => $this->classroomImages(),
        ]);
    }

    public function about()
    {
        return view('about', [
            'aboutImages' => [
                'hero' => $this->settingValue('about_hero_image', asset('images/hero/genchess-hero.jpg')),
                'who_we_are' => $this->settingValue('about_who_we_are_image', asset('images/programs/primary-chess.jpg')),
                'philosophy' => $this->settingValue('about_philosophy_image', asset('images/programs/secondary-chess.jpg')),
                'instructors' => $this->settingValue('about_instructors_image', asset('images/instructors/certified-coach.jpg')),
            ],
        ]);
    }

    public function contact()
    {
        $settings = Setting::whereIn('key', ['organization_name', 'support_email', 'support_phone'])
            ->get()
            ->keyBy('key');

        return view('contact', [
            'organizationName' => $settings['organization_name']->value ?? 'Genchess Educational Services',
            'supportEmail' => $settings['support_email']->value ?? 'info@genchess.ng',
            'supportPhone' => $settings['support_phone']->value ?? '+234 XXX XXX XXXX',
            'contactHeroImage' => $this->settingValue('contact_hero_image', asset('images/hero/genchess-hero.jpg')),
        ]);
    }

    public function chessInSchools()
    {
        return view('chess-in-schools', [
            'classroomImages' => $this->classroomImages(),
            'programImages' => $this->programImages(),
        ]);
    }

    public function chessInSchoolsPrimary()
    {
        return view('chess-in-schools-primary');
    }

    public function chessInSchoolsJss()
    {
        return view('chess-in-schools-jss');
    }

    public function chessCommunitiesHomes()
    {
        return view('chess-communities-homes', [
            'communitiesHeroImage' => $this->settingValue('communities_hero_image', asset('images/hero/genchess-hero.jpg')),
        ]);
    }

    public function instructorTraining()
    {
        $course = TrainingCourse::where('active', true)->orderBy('id')->first();

        return view('instructor-training', [
            'curriculum' => config('training_curriculum'),
            'course' => $course,
        ]);
    }

    public function careers()
    {
        return view('careers');
    }

    public function products()
    {
        return redirect()->route('store.index');
    }

    public function productsBoards()
    {
        return redirect()->route('store.category', ['category' => 'chessboards']);
    }

    public function productsClocks()
    {
        return redirect()->route('store.category', ['category' => 'chess-clocks']);
    }

    public function productsBooks()
    {
        return redirect()->route('store.category', ['category' => 'chess-books']);
    }

    public function tournaments()
    {
        return view('tournaments');
    }

    public function careersInstructors()
    {
        return view('careers-instructors');
    }

    public function careersCoordinators()
    {
        return view('careers-coordinators');
    }

    public function careersMarketers()
    {
        return view('careers-marketers');
    }

    public function registerSchool()
    {
        return view('schools.register');
    }

    private function classroomImages(): array
    {
        $keys = [
            'chess_school_hero_image',
            'chess_school_lesson_image',
            'chess_school_play_image',
            'chess_school_puzzle_image',
            'chess_school_competition_image',
        ];

        $settings = Setting::whereIn('key', $keys)->get()->keyBy('key');

        return [
            'hero' => $settings['chess_school_hero_image']->value ?? asset('images/herochessclassroom.jpg'),
            'lesson' => $settings['chess_school_lesson_image']->value ?? asset('images/chess-in-classroom.jpg'),
            'play' => $settings['chess_school_play_image']->value ?? asset('images/placeholders/student-playing-chess.jpg'),
            'puzzle' => $settings['chess_school_puzzle_image']->value ?? asset('images/placeholders/students-puzzles.jpg'),
            'competition' => $settings['chess_school_competition_image']->value ?? asset('images/tournaments/chess-competition.jpg'),
        ];
    }

    private function programImages(): array
    {
        return [
            'hero' => asset('images/instructors/classroom-instructor.jpg'),
            'lesson' => asset('images/placeholders/instructor-classroom.jpg'),
            'play' => asset('images/placeholders/student-plays.jpg'),
            'puzzle' => asset('images/puzzles.jpg'),
            'competition' => asset('images/tournaments/chess-competition.jpg'),
        ];
    }

    private function settingValue(string $key, string $fallback): string
    {
        $value = Setting::where('key', $key)->value('value');

        return MediaPath::toUrl($value) ?: $fallback;
    }
}

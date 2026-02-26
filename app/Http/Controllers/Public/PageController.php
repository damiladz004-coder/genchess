<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\TrainingCourse;

class PageController extends Controller
{
    public function home()
    {
        return view('public.home');
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        $settings = Setting::whereIn('key', ['organization_name', 'support_email', 'support_phone'])
            ->get()
            ->keyBy('key');

        return view('contact', [
            'organizationName' => $settings['organization_name']->value ?? 'Genchess Academy',
            'supportEmail' => $settings['support_email']->value ?? 'info@genchessacademy.com',
            'supportPhone' => $settings['support_phone']->value ?? '+234 XXX XXX XXXX',
        ]);
    }

    public function chessInSchools()
    {
        return view('chess-in-schools');
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
        return view('chess-communities-homes');
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
}

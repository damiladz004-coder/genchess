<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

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
        return view('contact');
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
        return view('instructor-training');
    }

    public function careers()
    {
        return view('careers');
    }

    public function products()
    {
        return view('products');
    }

    public function productsBoards()
    {
        return view('products-boards');
    }

    public function productsClocks()
    {
        return view('products-clocks');
    }

    public function productsBooks()
    {
        return view('products-books');
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

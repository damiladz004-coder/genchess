<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class TrainingDashboardController extends Controller
{
    public function index(): View
    {
        return view('training.dashboard');
    }
}

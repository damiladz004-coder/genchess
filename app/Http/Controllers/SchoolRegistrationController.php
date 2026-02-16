<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolRegistrationController extends Controller
{
    // Show registration form
    public function create()
    {
        return view('schools.register');
    }

    // Handle form submission
    public function store(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'state' => 'required|string',
            'city' => 'required|string',
            'class_system' => 'required|string',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|unique:schools,email',
            'phone' => 'required|string|max:20',
        ]);

        School::create([
            'school_name' => $request->school_name,
            'state' => $request->state,
            'city' => $request->city,
            'class_system' => $request->class_system,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 'pending',
        ]);

        return redirect()->back()->with(
            'success',
            'School registration submitted successfully. We will contact you shortly.'
        );
    }
}

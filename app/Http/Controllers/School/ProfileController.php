<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $school = auth()->user()->school;
        $states = config('nigeria.states', []);

        return view('school.profile.edit', compact('school', 'states'));
    }

    public function update(Request $request)
    {
        $school = auth()->user()->school;

        $rules = [
            'school_name' => 'required|string|max:255',
            'address_line' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => ['required', 'string', 'max:100', Rule::in(config('nigeria.states', []))],
        ];

        if ($school->status !== 'active') {
            $rules['class_system'] = 'required|in:primary_jss_ss,grade_1_12,year_1_12';
        }

        $data = $request->validate($rules);

        $school->update($data);

        return redirect()->back()->with('success', 'School profile updated.');
    }
}

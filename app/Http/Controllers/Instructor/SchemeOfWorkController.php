<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\SchemeOfWorkItem;

class SchemeOfWorkController extends Controller
{
    public function index()
    {
        $classes = auth()->user()->teachingClasses()->pluck('classes.id');

        $query = SchemeOfWorkItem::whereIn('class_id', $classes)
            ->with('classroom')
            ->orderBy('term')
            ->orderBy('week_number');

        if (request('class_id')) {
            $query->where('class_id', request('class_id'));
        }

        if (request('term')) {
            $query->where('term', request('term'));
        }

        $items = $query->get();
        $classModels = auth()->user()->teachingClasses()->orderBy('name')->get();
        $terms = ['Term 1', 'Term 2', 'Term 3'];

        return view('instructor.scheme.index', [
            'items' => $items,
            'classes' => $classModels,
            'terms' => $terms,
        ]);
    }
}

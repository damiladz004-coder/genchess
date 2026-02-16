<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchemeOfWorkItem;
use App\Models\Classroom;
use Illuminate\Http\Request;

class SchemeOfWorkController extends Controller
{
    public function index()
    {
        $items = SchemeOfWorkItem::with('classroom')
            ->orderBy('term')
            ->orderBy('week_number')
            ->get();

        return view('admin.scheme.index', compact('items'));
    }

    public function create()
    {
        $classes = Classroom::orderBy('name')->get();
        $terms = $this->terms();

        return view('admin.scheme.create', compact('classes', 'terms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'term' => 'required|in:' . implode(',', $this->terms()),
            'week_number' => 'required|integer|min:1|max:20',
            'topic' => 'required|string|max:255',
            'objectives' => 'nullable|string',
        ]);

        SchemeOfWorkItem::create($request->only([
            'class_id',
            'term',
            'week_number',
            'topic',
            'objectives',
        ]));

        return redirect()->route('admin.scheme.index')
            ->with('success', 'Scheme item created.');
    }

    public function edit(SchemeOfWorkItem $item)
    {
        $classes = Classroom::orderBy('name')->get();
        $terms = $this->terms();

        return view('admin.scheme.edit', compact('item', 'classes', 'terms'));
    }

    public function update(Request $request, SchemeOfWorkItem $item)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'term' => 'required|in:' . implode(',', $this->terms()),
            'week_number' => 'required|integer|min:1|max:20',
            'topic' => 'required|string|max:255',
            'objectives' => 'nullable|string',
        ]);

        $item->update($request->only([
            'class_id',
            'term',
            'week_number',
            'topic',
            'objectives',
        ]));

        return redirect()->route('admin.scheme.index')
            ->with('success', 'Scheme item updated.');
    }

    public function destroy(SchemeOfWorkItem $item)
    {
        $item->delete();

        return redirect()->route('admin.scheme.index')
            ->with('success', 'Scheme item removed.');
    }

    private function terms(): array
    {
        return ['Term 1', 'Term 2', 'Term 3'];
    }
}

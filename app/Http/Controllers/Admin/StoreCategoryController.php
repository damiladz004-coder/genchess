<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest('id')->paginate(20);

        return view('admin.store.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'status' => 'required|in:active,inactive',
        ]);

        $slug = Str::slug($data['title']);
        $base = $slug;
        $n = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $n++;
        }

        Category::create([
            'title' => $data['title'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'image' => $request->hasFile('image')
                ? '/storage/' . ltrim($request->file('image')->store('store/category-images', 'public'), '/')
                : null,
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Category created.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'status' => 'required|in:active,inactive',
        ]);

        $imagePath = $category->image;
        if ($request->hasFile('image')) {
            if ($imagePath && str_starts_with($imagePath, '/storage/store/category-images/')) {
                $oldPath = ltrim(str_replace('/storage/', '', $imagePath), '/');
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $storedPath = $request->file('image')->store('store/category-images', 'public');
            $imagePath = '/storage/' . ltrim($storedPath, '/');
        }

        $category->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? $category->description,
            'image' => $imagePath,
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Category updated.');
    }
}

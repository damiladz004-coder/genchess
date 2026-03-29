<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\PublicImage;
use Illuminate\Http\Request;
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
                ? PublicImage::store($request->file('image'), PublicImage::PRODUCTS_DIRECTORY.'/categories')
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $imagePath = $category->getRawOriginal('image');
        if ($request->hasFile('image')) {
            PublicImage::delete($imagePath);
            $imagePath = PublicImage::store($request->file('image'), PublicImage::PRODUCTS_DIRECTORY.'/categories');
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    private array $keys = [
        'organization_name',
        'support_email',
        'support_phone',
        'default_currency',
        'chess_school_hero_image',
        'chess_school_lesson_image',
        'chess_school_play_image',
        'chess_school_puzzle_image',
        'chess_school_competition_image',
    ];

    public function index()
    {
        $settings = Setting::whereIn('key', $this->keys)->get()->keyBy('key');

        return view('admin.settings.index', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'organization_name' => 'nullable|string|max:255',
            'support_email' => 'nullable|email|max:255',
            'support_phone' => 'nullable|string|max:30',
            'default_currency' => 'nullable|string|max:10',
            'chess_school_hero_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:6144',
            'chess_school_lesson_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:6144',
            'chess_school_play_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:6144',
            'chess_school_puzzle_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:6144',
            'chess_school_competition_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:6144',
        ]);

        foreach (['organization_name', 'support_email', 'support_phone', 'default_currency'] as $key) {
            $value = $data[$key] ?? null;
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        foreach ([
            'chess_school_hero_image',
            'chess_school_lesson_image',
            'chess_school_play_image',
            'chess_school_puzzle_image',
            'chess_school_competition_image',
        ] as $imageKey) {
            if (!$request->hasFile($imageKey)) {
                continue;
            }

            $existing = Setting::where('key', $imageKey)->value('value');
            if ($existing && str_starts_with($existing, '/storage/')) {
                $oldPath = ltrim(str_replace('/storage/', '', $existing), '/');
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $storedPath = $request->file($imageKey)->store('chess-classrooms', 'public');
            $publicPath = '/storage/' . ltrim($storedPath, '/');

            Setting::updateOrCreate(
                ['key' => $imageKey],
                ['value' => $publicPath]
            );
        }

        return redirect()->back()->with('success', 'Settings updated.');
    }
}

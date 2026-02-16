<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private array $keys = [
        'organization_name',
        'support_email',
        'support_phone',
        'default_currency',
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
        ]);

        foreach ($this->keys as $key) {
            $value = $data[$key] ?? null;
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Settings updated.');
    }
}

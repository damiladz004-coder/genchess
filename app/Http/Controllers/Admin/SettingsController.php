<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\PublicImage;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private const IMAGE_RULE = 'nullable|image|mimes:jpg,jpeg,png|max:6144';

    private array $pageImageSections = [
        'Homepage' => [
            ['key' => 'homepage_hero_image', 'label' => 'Homepage Hero Image', 'alt' => 'Homepage hero image preview', 'directory' => 'site/homepage'],
            ['key' => 'homepage_schools_image', 'label' => 'Homepage Schools Card Image', 'alt' => 'Homepage schools card image preview', 'directory' => 'site/homepage'],
            ['key' => 'homepage_communities_image', 'label' => 'Homepage Communities Card Image', 'alt' => 'Homepage communities card image preview', 'directory' => 'site/homepage'],
            ['key' => 'homepage_store_image', 'label' => 'Homepage Store Card Image', 'alt' => 'Homepage store card image preview', 'directory' => 'site/homepage'],
            ['key' => 'homepage_instructor_image', 'label' => 'Homepage Instructor Section Image', 'alt' => 'Homepage instructor image preview', 'directory' => 'site/homepage'],
        ],
        'Store' => [
            ['key' => 'store_hero_image', 'label' => 'Store Hero Image', 'alt' => 'Store hero image preview', 'directory' => 'site/store'],
            ['key' => 'store_bulk_order_image', 'label' => 'Store Bulk Order Section Image', 'alt' => 'Store bulk order image preview', 'directory' => 'site/store'],
        ],
        'Communities & Homes' => [
            ['key' => 'communities_hero_image', 'label' => 'Communities & Homes Hero Image', 'alt' => 'Communities and homes hero image preview', 'directory' => 'site/communities'],
        ],
        'About' => [
            ['key' => 'about_hero_image', 'label' => 'About Hero Image', 'alt' => 'About hero image preview', 'directory' => 'site/about'],
            ['key' => 'about_who_we_are_image', 'label' => 'About Who We Are Image', 'alt' => 'About who we are image preview', 'directory' => 'site/about'],
            ['key' => 'about_philosophy_image', 'label' => 'About Philosophy Image', 'alt' => 'About philosophy image preview', 'directory' => 'site/about'],
            ['key' => 'about_instructors_image', 'label' => 'About Instructors Image', 'alt' => 'About instructors image preview', 'directory' => 'site/about'],
        ],
        'Contact' => [
            ['key' => 'contact_hero_image', 'label' => 'Contact Hero Image', 'alt' => 'Contact hero image preview', 'directory' => 'site/contact'],
        ],
    ];

    private array $schoolImageFields = [
        ['key' => 'chess_school_hero_image', 'label' => 'Hero Classroom Image', 'alt' => 'Hero classroom image preview', 'directory' => 'chess-classrooms'],
        ['key' => 'chess_school_lesson_image', 'label' => 'Lesson Image', 'alt' => 'Lesson image preview', 'directory' => 'chess-classrooms'],
        ['key' => 'chess_school_play_image', 'label' => 'Students Playing Image', 'alt' => 'Students playing image preview', 'directory' => 'chess-classrooms'],
        ['key' => 'chess_school_puzzle_image', 'label' => 'Puzzle Session Image', 'alt' => 'Puzzle image preview', 'directory' => 'chess-classrooms'],
        ['key' => 'chess_school_competition_image', 'label' => 'Competition Image', 'alt' => 'Competition image preview', 'directory' => 'chess-classrooms'],
    ];

    private array $textKeys = [
        'organization_name',
        'support_email',
        'support_phone',
        'default_currency',
    ];

    public function index()
    {
        $settings = Setting::whereIn('key', $this->settingKeys())->get()->keyBy('key');

        return view('admin.settings.index', [
            'settings' => $settings,
            'pageImageSections' => $this->pageImageSections,
            'schoolImageFields' => $this->schoolImageFields,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate(array_merge([
            'organization_name' => 'nullable|string|max:255',
            'support_email' => 'nullable|email|max:255',
            'support_phone' => 'nullable|string|max:30',
            'default_currency' => 'nullable|string|max:10',
        ], $this->imageValidationRules()));

        foreach ($this->textKeys as $key) {
            $value = $data[$key] ?? null;
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        foreach ($this->imageFieldsByKey() as $imageKey => $field) {
            if (!$request->hasFile($imageKey)) {
                continue;
            }

            $existing = Setting::where('key', $imageKey)->value('value');
            PublicImage::delete($existing);

            $imagePath = PublicImage::store($request->file($imageKey), $field['directory']);

            Setting::updateOrCreate(
                ['key' => $imageKey],
                ['value' => $imagePath]
            );
        }

        return redirect()->back()->with('success', 'Settings updated.');
    }

    private function settingKeys(): array
    {
        return array_merge($this->textKeys, array_keys($this->imageFieldsByKey()));
    }

    private function imageValidationRules(): array
    {
        return collect($this->imageFieldsByKey())
            ->mapWithKeys(fn (array $field, string $key) => [$key => self::IMAGE_RULE])
            ->all();
    }

    private function imageFieldsByKey(): array
    {
        $pageFields = array_merge(...array_values($this->pageImageSections));
        $allFields = array_merge($pageFields, $this->schoolImageFields);

        return collect($allFields)->keyBy('key')->all();
    }
}

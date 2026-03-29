<?php

namespace Tests\Unit;

use App\Support\PublicImage;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PublicImageTest extends TestCase
{
    private string $testDirectory = 'unit-tests';

    protected function tearDown(): void
    {
        File::deleteDirectory(public_path('images/'.$this->testDirectory));

        parent::tearDown();
    }

    public function test_it_builds_an_asset_url_for_existing_public_images(): void
    {
        $directory = public_path('images/'.$this->testDirectory);
        File::ensureDirectoryExists($directory);
        File::put($directory.'/sample.jpg', 'fake-image-bytes');

        $url = PublicImage::url($this->testDirectory.'/sample.jpg', 'images/products/placeholder-board.jpg');

        $this->assertSame(asset('images/'.$this->testDirectory.'/sample.jpg'), $url);
        $this->assertTrue(PublicImage::exists($this->testDirectory.'/sample.jpg'));
    }

    public function test_it_returns_the_placeholder_when_the_image_is_missing(): void
    {
        $url = PublicImage::url('products/missing-image.jpg', 'images/products/placeholder-board.jpg');

        $this->assertSame(asset('images/products/placeholder-board.jpg'), $url);
        $this->assertFalse(PublicImage::exists('products/missing-image.jpg'));
    }
}

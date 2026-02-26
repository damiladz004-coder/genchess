<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['title' => 'Chessboards', 'slug' => 'chessboards', 'image' => '/images/products/placeholder-board.jpg'],
            ['title' => 'Chess Clocks', 'slug' => 'chess-clocks', 'image' => '/images/products/placeholder-clock.jpg'],
            ['title' => 'Chess Books', 'slug' => 'chess-books', 'image' => '/images/products/placeholder-book.jpg'],
            ['title' => 'Chess Apparel', 'slug' => 'chess-apparel', 'image' => '/images/products/placeholder-shirt.jpg'],
            ['title' => 'School Packages', 'slug' => 'school-packages', 'image' => '/images/products/placeholder-bag.jpg'],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                [
                    'title' => $categoryData['title'],
                    'description' => $categoryData['title'] . ' for Genchess schools and communities.',
                    'image' => $categoryData['image'],
                    'status' => 'active',
                ]
            );

            if (!Product::where('category_id', $category->id)->exists()) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $category->title . ' Sample Product',
                    'slug' => $category->slug . '-sample-product',
                    'description' => 'Placeholder sample product for ' . $category->title . '.',
                    'price_kobo' => 1500000,
                    'bulk_price_kobo' => 1200000,
                    'sku' => strtoupper(substr($category->slug, 0, 3)) . '-' . rand(1000, 9999),
                    'stock_quantity' => 100,
                    'image_placeholder' => $categoryData['image'],
                    'product_type' => $category->slug === 'school-packages' ? 'school_package' : 'standard',
                    'featured' => true,
                    'allow_quote' => $category->slug === 'school-packages',
                    'has_size_options' => $category->slug === 'chess-apparel',
                    'has_color_options' => $category->slug === 'chess-apparel',
                    'status' => 'active',
                ]);
            }
        }
    }
}


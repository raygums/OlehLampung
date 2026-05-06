<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kopi',
                'slug' => 'kopi',
                'description' => 'Kopi robusta dan arabika khas Lampung, dari petani lokal pilihan.',
                'image' => '/images/categories/kopi.jpg',
                'sort_order' => 1,
            ],
            [
                'name' => 'Makanan',
                'slug' => 'makanan',
                'description' => 'Aneka keripik, lempok durian, dan makanan ringan khas Lampung.',
                'image' => '/images/categories/makanan.jpg',
                'sort_order' => 2,
            ],

        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

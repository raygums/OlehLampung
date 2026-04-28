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
            [
                'name' => 'Kerajinan',
                'slug' => 'kerajinan',
                'description' => 'Kain tapis, batik, dan kerajinan tangan khas suku Lampung.',
                'image' => '/images/categories/kerajinan.jpg',
                'sort_order' => 3,
            ],
            [
                'name' => 'Minuman',
                'slug' => 'minuman',
                'description' => 'Teh herbal, sirup, dan minuman tradisional Lampung.',
                'image' => '/images/categories/minuman.jpg',
                'sort_order' => 4,
            ],
            [
                'name' => 'Souvenir',
                'slug' => 'souvenir',
                'description' => 'Pernak-pernik dan cinderamata khas Lampung untuk kenang-kenangan.',
                'image' => '/images/categories/souvenir.jpg',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

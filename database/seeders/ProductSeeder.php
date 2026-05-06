<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Kopi (category_id: 1)
            [
                'category_id' => 1,
                'name' => 'Kopi Robusta Lampung Sinar Baru 250g',
                'slug' => 'kopi-robusta-lampung-sinar-baru-250g',
                'short_description' => 'Kopi robusta pilihan dari perkebunan Lampung Barat.',
                'description' => 'Kopi Robusta Lampung Sinar Baru adalah kopi pilihan yang berasal dari perkebunan kopi terbaik di Lampung Barat. Diproses secara natural untuk menghasilkan cita rasa yang kuat, bold, dan memiliki body yang tebal. Cocok untuk pecinta kopi yang menyukai rasa autentik kopi Lampung. Berat bersih 250 gram.',
                'price' => 45000,
                'original_price' => null,
                'stock' => 150,
                'weight' => 270,
                'rating' => 4.8,
                'review_count' => 234,
                'images' => ['/images/products/kopi-robusta-1.jpg', '/images/products/kopi-robusta-2.jpg'],
                'is_featured' => true,
                'is_sale' => false,
            ],
            [
                'category_id' => 1,
                'name' => 'Kopi Arabika Way Tenong Specialty',
                'slug' => 'kopi-arabika-way-tenong-specialty',
                'short_description' => 'Kopi arabika specialty grade dari Way Tenong, Lampung Barat.',
                'description' => 'Kopi Arabika Way Tenong Specialty adalah kopi single origin berkualitas tinggi dari dataran tinggi Way Tenong. Dengan proses full washed, kopi ini menghasilkan rasa fruity, floral, dan acidity yang cerah. Grade specialty dengan skor cupping di atas 80. Berat bersih 200 gram.',
                'price' => 85000,
                'original_price' => 95000,
                'stock' => 80,
                'weight' => 220,
                'rating' => 4.9,
                'review_count' => 89,
                'images' => ['/images/products/kopi-arabika-1.jpg'],
                'is_featured' => true,
                'is_sale' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Kopi Lampung Bubuk Premium 500g',
                'slug' => 'kopi-lampung-bubuk-premium-500g',
                'short_description' => 'Kopi bubuk siap seduh, roasting medium-dark.',
                'description' => 'Kopi Lampung Bubuk Premium sudah dihaluskan dan siap diseduh. Proses roasting medium-dark menghasilkan rasa yang seimbang antara pahit dan manis. Cocok untuk tubruk, V60, maupun french press. Berat bersih 500 gram.',
                'price' => 65000,
                'original_price' => null,
                'stock' => 200,
                'weight' => 520,
                'rating' => 4.6,
                'review_count' => 156,
                'images' => ['/images/products/kopi-bubuk-1.jpg'],
                'is_featured' => false,
                'is_sale' => false,
            ],

            // Makanan (category_id: 2)
            [
                'category_id' => 2,
                'name' => 'Keripik Pisang Coklat Premium',
                'slug' => 'keripik-pisang-coklat-premium',
                'short_description' => 'Keripik pisang renyah dengan lapisan coklat premium.',
                'description' => 'Keripik Pisang Coklat Premium dibuat dari pisang kepok pilihan yang diiris tipis dan digoreng renyah, kemudian dilapisi coklat premium Belgium. Tekstur renyah di luar dan lumer di mulut. Tersedia dalam kemasan 250 gram, cocok untuk oleh-oleh keluarga.',
                'price' => 25000,
                'original_price' => 35000,
                'stock' => 300,
                'weight' => 270,
                'rating' => 4.7,
                'review_count' => 512,
                'images' => ['/images/products/keripik-pisang-1.jpg', '/images/products/keripik-pisang-2.jpg'],
                'is_featured' => true,
                'is_sale' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Lempok Durian Asli',
                'slug' => 'lempok-durian-asli',
                'short_description' => 'Dodol durian tradisional khas Lampung.',
                'description' => 'Lempok Durian Asli dibuat dari buah durian lokal Lampung yang diolah secara tradisional tanpa pengawet. Tekstur lembut dan legit dengan aroma durian yang kuat. Setiap bungkus berisi 300 gram lempok yang dikemas rapi.',
                'price' => 50000,
                'original_price' => null,
                'stock' => 100,
                'weight' => 320,
                'rating' => 4.5,
                'review_count' => 178,
                'images' => ['/images/products/lempok-durian-1.jpg'],
                'is_featured' => true,
                'is_sale' => false,
            ],
            [
                'category_id' => 2,
                'name' => 'Sambal Lampung Bajak Kenacan Kaca',
                'slug' => 'sambal-lampung-bajak-kenacan',
                'short_description' => 'Sambal bajak pedas manis khas Lampung dalam jar kaca.',
                'description' => 'Sambal Lampung Bajak Kenacan dibuat dari cabai rawit dan cabai merah pilihan, diulek kasar dengan bumbu rempah tradisional Lampung. Disajikan dalam jar kaca premium 200 gram. Tahan hingga 3 bulan tanpa pengawet buatan.',
                'price' => 38000,
                'original_price' => 45000,
                'stock' => 250,
                'weight' => 350,
                'rating' => 4.6,
                'review_count' => 203,
                'images' => ['/images/products/sambal-lampung-1.jpg'],
                'is_featured' => false,
                'is_sale' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Keripik Pisang Asin Original Lampung',
                'slug' => 'keripik-pisang-asin-original',
                'short_description' => 'Keripik pisang asin gurih renyah khas Lampung.',
                'description' => 'Keripik pisang asin original dibuat dari pisang kepok segar yang diiris tipis dan digoreng hingga renyah sempurna. Rasa gurih dan asin pas, tanpa MSG. Kemasan 200 gram.',
                'price' => 20000,
                'original_price' => null,
                'stock' => 400,
                'weight' => 220,
                'rating' => 4.4,
                'review_count' => 321,
                'images' => ['/images/products/keripik-asin-1.jpg'],
                'is_featured' => false,
                'is_sale' => false,
            ],
            [
                'category_id' => 2,
                'name' => 'Kemplang Panggang Ikan Tenggiri',
                'slug' => 'kemplang-panggang-ikan-tenggiri',
                'short_description' => 'Kemplang panggang gurih dari ikan tenggiri segar.',
                'description' => 'Kemplang Panggang Ikan Tenggiri khas Lampung, dibuat dari ikan tenggiri segar pilihan. Dipanggang dengan arang kelapa untuk aroma smoky yang khas. Renyah dan gurih, cocok sebagai camilan atau lauk. Kemasan 150 gram.',
                'price' => 30000,
                'original_price' => null,
                'stock' => 180,
                'weight' => 170,
                'rating' => 4.3,
                'review_count' => 145,
                'images' => ['/images/products/kemplang-1.jpg'],
                'is_featured' => false,
                'is_sale' => false,
            ],


        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

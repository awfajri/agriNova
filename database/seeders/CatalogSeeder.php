<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Buah & Sayur', 'slug' => 'buah-sayur', 'description' => 'Produk segar harian dari kebun Agrinova.'],
            ['name' => 'Perlengkapan Kebun', 'slug' => 'perlengkapan-kebun', 'description' => 'Alat dan perlengkapan pendukung budidaya.'],
            ['name' => 'Pupuk', 'slug' => 'pupuk', 'description' => 'Pupuk dan nutrisi tanaman untuk kebutuhan budidaya.'],
        ];

        foreach ($categories as $categoryData) {
            Category::query()->firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        $buahSayur = Category::where('slug', 'buah-sayur')->first();
        $perlengkapan = Category::where('slug', 'perlengkapan-kebun')->first();
        $pupuk = Category::where('slug', 'pupuk')->first();

        $products = [
            [
                'category_id' => $buahSayur?->id,
                'name' => 'Melon Premium',
                'slug' => 'melon-premium',
                'sku' => 'AGR-MEL-001',
                'short_description' => 'Melon segar dengan rasa manis dan tekstur lembut.',
                'description' => 'Melon premium hasil panen kebun Agrinova. Cocok untuk kebutuhan konsumsi rumah tangga dan kebutuhan usaha.',
                'price' => 22000,
                'stock' => 35,
                'min_order_quantity' => 1,
                'is_preorder' => true,
                'is_featured' => true,
                'is_active' => true,
                'image_url' => '/images/mln.webp',
            ],
            [
                'category_id' => $buahSayur?->id,
                'name' => 'Sayur Campur Segar',
                'slug' => 'sayur-campur-segar',
                'sku' => 'AGR-SAY-001',
                'short_description' => 'Paket sayur segar pilihan untuk kebutuhan keluarga.',
                'description' => 'Paket sayur segar yang dipanen setiap hari dan dikirim langsung ke pelanggan.',
                'price' => 18000,
                'stock' => 24,
                'min_order_quantity' => 1,
                'is_preorder' => false,
                'is_featured' => true,
                'is_active' => true,
                'image_url' => '/images/mix.webp',
            ],
            [
                'category_id' => $perlengkapan?->id,
                'name' => 'Set Alat Kebun Dasar',
                'slug' => 'set-alat-kebun-dasar',
                'sku' => 'AGR-TOOL-001',
                'short_description' => 'Paket alat pertanian dasar untuk pemula dan petani rumahan.',
                'description' => 'Set alat kebun dasar yang membantu proses budidaya lebih cepat dan nyaman.',
                'price' => 125000,
                'stock' => 12,
                'min_order_quantity' => 1,
                'is_preorder' => false,
                'is_featured' => false,
                'is_active' => true,
                'image_url' => null,
            ],
            [
                'category_id' => $pupuk?->id,
                'name' => 'Pupuk Organik Cair',
                'slug' => 'pupuk-organik-cair',
                'sku' => 'AGR-FERT-001',
                'short_description' => 'Nutrisi tanaman berbasis organik untuk pertumbuhan optimal.',
                'description' => 'Pupuk organik cair yang membantu tanaman tumbuh lebih sehat dan kuat.',
                'price' => 50000,
                'stock' => 18,
                'min_order_quantity' => 1,
                'is_preorder' => false,
                'is_featured' => false,
                'is_active' => true,
                'image_url' => null,
            ],
        ];

        foreach ($products as $product) {
            Product::query()->updateOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }
    }
}

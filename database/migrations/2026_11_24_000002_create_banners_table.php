<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('eyebrow')->nullable();
            $table->string('image_path');
            $table->string('cta_text')->nullable();
            $table->string('cta_url')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_countdown')->default(false);
            $table->timestamps();
        });

        // Seed initial banners agar slider langsung memiliki data multi-banner siap pakai
        DB::table('banners')->insert([
            [
                'title' => 'Booking sekarang, panen segar melon hidroponik.',
                'eyebrow' => 'Pre-Order Panen Batch Terkini',
                'image_path' => 'images/banner-1.jpg',
                'cta_text' => 'Booking Melon Sekarang',
                'cta_url' => '/catalog?availability=preorder#hasil-katalog',
                'order' => 1,
                'is_active' => true,
                'is_countdown' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Sayuran Segar & Hidroponik Bebas Pestisida.',
                'eyebrow' => 'Kualitas Premium',
                'image_path' => 'images/banner-2.jpg',
                'cta_text' => 'Belanja Sayur Segar',
                'cta_url' => '/catalog#hasil-katalog',
                'order' => 2,
                'is_active' => true,
                'is_countdown' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Eduwisata & Pelatihan Kebun Modern Agrinova.',
                'eyebrow' => 'Kunjungan & Edukasi',
                'image_path' => 'images/banner-3.jpg',
                'cta_text' => 'Pelajari Paket Eduwisata',
                'cta_url' => '/eduwisata',
                'order' => 3,
                'is_active' => true,
                'is_countdown' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};

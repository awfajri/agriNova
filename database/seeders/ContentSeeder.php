<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use App\Models\SiteSetting;
use App\Models\TrainingClass;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            [
                'slug' => 'budidaya-melon-hidroponik',
                'category' => 'Budidaya Tanaman',
                'title' => 'Budidaya Melon Green House',
                'price' => 450000,
                'duration' => '2 Hari Praktik',
                'schedule' => '14–15 Nov 2026',
                'capacity' => 8,
                'status' => 'Tersedia',
                'image' => 'images/pelatihan.webp',
                'highlights' => ['Modul & Sertifikat', 'Bibit & AB Mix', 'Praktik Kebun'],
                'summary' => 'Praktik semai, racik nutrisi AB Mix, hingga panen melon premium.',
                'description' => 'Kuasa standar budidaya melon eksklusif di dalam Green House Agrinova. Peserta belajar langsung manajemen iklim mikro, polinasi buatan, hingga seleksi buah kualitas supermarket.',
                'materials' => [
                    'Pengenalan varietas melon & instalasi Green House',
                    'Manajemen nutrisi fertigasi (AB Mix) & kontrol EC/pH',
                    'Teknik polinasi, seleksi buah, dan pemangkasan cabang',
                    'Analisis usaha tani & kemitraan pasar hasil panen',
                ],
                'is_available' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'pembekalan-pensiun-agribisnis',
                'category' => 'Pembekalan Pensiun',
                'title' => 'Wirausaha Tani Masa Pensiun',
                'price' => 1250000,
                'duration' => '3 Hari Intensif',
                'schedule' => '20–22 Nov 2026',
                'capacity' => 5,
                'status' => 'Dibuka',
                'image' => 'images/edukasi2.webp',
                'highlights' => ['Perencanaan Bisnis', 'Tur Kebun Mitra', 'Konsultasi Privat'],
                'summary' => 'Rancang bisnis kebun produktif & terukur jelang masa purnabakti.',
                'description' => 'Program khusus bagi karyawan menjelang masa purnabakti yang ingin membangun agribisnis mandiri. Mencakup hitung RAB Green House, pemilihan komoditas, hingga manajemen risiko.',
                'materials' => [
                    'Mindset wirausaha & manajemen risiko di masa pensiun',
                    'Perancangan RAB Green House skala rumahan & komersial',
                    'Praktik budidaya melon, anggur, dan sayur hidroponik',
                    'Simulasi finansial & studi kasus kebun mitra Agrinova',
                ],
                'is_available' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'budidaya-anggur-dan-sayur',
                'category' => 'Budidaya Tanaman',
                'title' => 'Anggur & Sayur Hidroponik',
                'price' => 350000,
                'duration' => '1 Hari (6 Jam)',
                'schedule' => '28 Nov 2026',
                'capacity' => 0,
                'status' => 'Penuh',
                'image' => 'images/sayursegar.webp',
                'highlights' => ['Teknik Pruning', 'Sistem NFT/DFT', 'Starter Kit'],
                'summary' => 'Teknik pembuahan anggur meja & instalasi sayur di lahan sempit.',
                'description' => 'Pelajari cara membuahkan anggur secara terjadwal dan memproduksi sayuran daun bebas pestisida menggunakan sistem hidroponik modern.',
                'materials' => [
                    'Pemilihan bibit anggur impor & media tanam poros',
                    'Teknik pruning (pemangkasan) pembuahan bertingkat',
                    'Instalasi hidroponik sayur bebas pestisida',
                    'Penanganan pascapanen dan pengemasan ritel',
                ],
                'is_available' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($classes as $class) {
            TrainingClass::query()->updateOrCreate(['slug' => $class['slug']], $class);
        }

        $settings = [
            'site_name' => 'Agrinova Farm',
            'address' => '',
            'whatsapp' => '',
            'maps_url' => '',
            'seo_title' => 'Agrinova Farm | Kebun Melon dan Edukasi Agribisnis',
            'seo_description' => 'Agrinova Farm menyediakan melon, sayur segar, pelatihan budidaya, dan eduwisata agribisnis.',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        if (! GalleryItem::query()->exists()) {
            GalleryItem::query()->create([
                'category' => 'Fasilitas',
                'title' => 'Agrinova Farm',
                'description' => 'Tambahkan foto kegiatan dan fasilitas Agrinova melalui panel admin.',
                'image_url' => '/images/hero-bg.jpg',
                'is_active' => true,
                'sort_order' => 1,
            ]);
        }
    }
}

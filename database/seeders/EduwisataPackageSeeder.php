<?php

namespace Database\Seeders;

use App\Models\EduwisataPackage;
use Illuminate\Database\Seeder;

class EduwisataPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'slug' => 'paket-a-basic',
                'title' => 'Paket A (Basic)',
                'short_title' => 'Paket A',
                'tag' => 'Sekolah & Komunitas',
                'price' => 35000,
                'quota' => '100–120 peserta/sesi',
                'subtitle' => 'Tur kebun & panen langsung',
                'summary' => 'Tur kebun seru mengenal tanaman sayur, buah, dan hidroponik lengkap dengan pengalaman panen langsung.',
                'image' => 'images/edukasi2.webp',
                'sort_order' => 1,
                'highlights' => [
                    'Keliling kebun edukasi tanaman sayur, buah, & hidroponik',
                    'Pengalaman memanen langsung sayur mayur atau jagung',
                    'Didampingi fasilitator kebun & sound system',
                    'Akses fasilitas umum (mushola, kamar mandi, titik kumpul, parkir)',
                    'Gratis sertifikat kunjungan untuk sekolah',
                ],
            ],
            [
                'slug' => 'paket-b-berkebun',
                'title' => 'Paket B',
                'short_title' => 'Paket B',
                'tag' => 'Sekolah & Komunitas',
                'price' => 65000,
                'quota' => '100–120 peserta/sesi',
                'subtitle' => 'Semua fasilitas Paket A + praktik menanam bibit',
                'summary' => 'Paket lengkap eksplorasi kebun ditambah praktik membuat media tanam dan menanam bibit sendiri.',
                'image' => 'images/eduwisata.webp',
                'sort_order' => 2,
                'highlights' => [
                    'Semua fasilitas dalam Paket A (Basic)',
                    'Praktik membuat media tanam dan menanam bibit',
                    'Souvenir hasil menanam bibit dibawa pulang',
                    'Air mineral untuk setiap peserta',
                    'Sertifikat tiap anak dan sekolah',
                ],
            ],
            [
                'slug' => 'paket-c-panen-melon',
                'title' => 'Paket C',
                'short_title' => 'Paket C',
                'tag' => 'Sekolah & Keluarga',
                'price' => 80000,
                'quota' => '100–120 peserta/sesi',
                'subtitle' => 'Semua fasilitas Paket A + panen buah melon',
                'summary' => 'Wisata edukasi kebun dengan pengalaman eksklusif memanen dan membawa pulang hasil panen buah melon.',
                'image' => 'images/panenmelon.webp',
                'sort_order' => 3,
                'highlights' => [
                    'Semua fasilitas dalam Paket A (Basic)',
                    'Edukasi & proses memanen langsung buah melon di Green House',
                    'Hasil panen buah melon dibawa pulang',
                    'Gratis sertifikat untuk sekolah',
                ],
            ],
            [
                'slug' => 'paket-d-kreasi',
                'title' => 'Paket D',
                'short_title' => 'Paket D',
                'tag' => 'PAUD & TK / SD',
                'price' => 60000,
                'quota' => '100–120 peserta/sesi',
                'subtitle' => 'Semua fasilitas Paket A + mewarnai & keterampilan',
                'summary' => 'Kombinasi tur edukasi kebun dengan aktivitas mewarnai, kreasi keterampilan, dan spanduk penyambutan.',
                'image' => 'images/edukasi2.webp',
                'sort_order' => 4,
                'highlights' => [
                    'Semua fasilitas dalam Paket A (Basic)',
                    'Sesi mewarnai / kreasi keterampilan anak',
                    'Gratis spanduk welcome rombongan',
                    'Sertifikat per anak dan sekolah',
                ],
            ],
            [
                'slug' => 'paket-e-lengkap',
                'title' => 'Paket E',
                'short_title' => 'Paket E',
                'tag' => 'Full Day Rombongan',
                'price' => 170000,
                'quota' => '100–120 peserta/sesi',
                'subtitle' => 'Paket lengkap + makan siang & dokumentasi',
                'summary' => 'Paket all-in terkomplit: berkebun, mewarnai, snack, makan siang, serta dokumentasi kegiatan tiap anak.',
                'image' => 'images/greenhouse2.webp',
                'sort_order' => 5,
                'highlights' => [
                    'Semua fasilitas dalam Paket B (berkebun & bibit)',
                    'Aktivitas mewarnai & spanduk welcome rombongan',
                    'Sudah termasuk snack dan makan siang peserta',
                    'Dokumentasi kegiatan per anak',
                    'Sertifikat per anak dan sekolah',
                ],
            ],
            [
                'slug' => 'paket-f-satwa',
                'title' => 'Paket F',
                'short_title' => 'Paket F',
                'tag' => 'Edukasi Anak',
                'price' => 55000,
                'quota' => '100–120 peserta/sesi',
                'subtitle' => 'Semua fasilitas Paket A + edukasi hewan melata',
                'summary' => 'Tur kebun sayur dan hidroponik yang dipadukan dengan sesi pengenalan dan edukasi hewan melata.',
                'image' => 'images/eduwisata.webp',
                'sort_order' => 6,
                'highlights' => [
                    'Semua fasilitas dalam Paket A (Basic)',
                    'Sesi interaksi & edukasi hewan melata bersama pemandu',
                    'Gratis sertifikat untuk sekolah',
                ],
            ],
        ];

        foreach ($packages as $pkg) {
            EduwisataPackage::query()->updateOrCreate(
                ['slug' => $pkg['slug']],
                array_merge($pkg, ['is_active' => true])
            );
        }
    }
}
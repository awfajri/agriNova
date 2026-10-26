<?php

namespace App\Http\Controllers;

use App\Models\PreOrder;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('name')
            ->limit(4)
            ->get();

        $preorder = PreOrder::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('booking_close_at')
                    ->orWhere('booking_close_at', '>=', now());
            })
            ->orderBy('harvest_date')
            ->first();

        $hotspots = [
            ['title' => 'Green House',          'x' => 27, 'y' => 44, 'desc' => 'Bangunan green house tempat tanaman dibudidayakan dalam lingkungan yang lebih terkontrol.', 'img' => 'images/greenhouse2.webp'],
            ['title' => 'Area Budidaya Melon',  'x' => 61, 'y' => 56, 'desc' => 'Area tanam melon. Dari sini panen untuk penjualan dan pre-order berasal.', 'img' => 'images/panenmelon.webp'],
            ['title' => 'Area Kegiatan Edukasi','x' => 44, 'y' => 74, 'desc' => 'Area untuk kegiatan edukasi dan pelatihan bagi pengunjung eduwisata.', 'img' => 'images/eduwisata.webp'],
            ['title' => 'Parkir Bus',           'x' => 80, 'y' => 30, 'desc' => 'Area parkir untuk rombongan sekolah dan komunitas yang datang dengan bus.', 'img' => 'images/arean bus.webp'],
        ];

        $gallery = [
            ['cat' => 'Panen',     'title' => 'Panen Melon',        'desc' => 'Suasana panen melon di kebun Agrinova.',         'img' => 'images/panenmelon.webp'],
            ['cat' => 'Eduwisata', 'title' => 'Kunjungan Sekolah',  'desc' => 'Rombongan sekolah belajar langsung di kebun.',   'img' => 'images/eduwisata.webp'],
            ['cat' => 'Fasilitas', 'title' => 'Green House',        'desc' => 'Green house tempat tanaman dibudidayakan.',      'img' => 'images/greenhouse2.webp'],
            ['cat' => 'Pelatihan', 'title' => 'Kelas Budidaya',     'desc' => 'Peserta pelatihan praktik budidaya tanaman.',     'img' => 'images/pelatihan.webp'],
            ['cat' => 'Panen',     'title' => 'Sayur Segar',        'desc' => 'Sayur segar yang dipetik langsung dari kebun.',   'img' => 'images/sayursegar.webp'],
            ['cat' => 'Eduwisata', 'title' => 'Kegiatan Edukasi',   'desc' => 'Kegiatan edukasi bagi komunitas dan keluarga.',   'img' => 'images/edukasi2.webp'],
            ['cat' => 'Fasilitas', 'title' => 'Area Kebun',         'desc' => 'Hamparan area kebun Agrinova.',                  'img' => 'images/arean bus.webp'],
        ];

        return view('home', compact('products', 'preorder', 'hotspots', 'gallery'));
    }
}
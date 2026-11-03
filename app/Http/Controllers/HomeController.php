<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
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

        $gallery = GalleryItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get()
            ->map(fn (GalleryItem $item): array => [
                'cat' => $item->category,
                'title' => $item->title,
                'desc' => $item->description,
                'img' => $item->image_url,
            ])
            ->all();

        return view('home', compact('products', 'preorder', 'hotspots', 'gallery'));
    }
}
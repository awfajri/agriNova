<?php

namespace App\Http\Controllers;

use App\Models\PreOrder;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
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

        return view('home', compact('products', 'preorder'));
    }
}
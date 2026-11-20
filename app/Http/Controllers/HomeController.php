<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PreOrder;
use App\Models\Product;
use App\Models\TrainingClass;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->withCount(['products' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->limit(8)
            ->get();

        $trainings = TrainingClass::query()
            ->where('status', '!=', 'Nonaktif')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->limit(3)
            ->get()
            ->map(fn (TrainingClass $class): array => $class->toPublicArray());

        $preorder = PreOrder::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('booking_close_at')
                    ->orWhere('booking_close_at', '>=', now());
            })
            ->orderBy('harvest_date')
            ->first();

        return view('home', compact('categories', 'products', 'trainings', 'preorder'));
    }
}
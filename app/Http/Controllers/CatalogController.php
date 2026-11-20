<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PreOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $isFiltering = $request->filled('category')
            || $request->filled('q')
            || $request->filled('availability')
            || $request->filled('sort');

        $query = Product::query()->with('category')->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', function ($categoryQuery) use ($request) {
                $categoryQuery->where('slug', $request->category);
            });
        }

        if ($request->filled('q')) {
            $search = trim((string) $request->string('q'));

            $query->where(function ($productQuery) use ($search) {
                $productQuery
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->input('availability') === 'in_stock') {
            $query->where('stock', '>', 0);
        }

        if ($request->input('availability') === 'preorder') {
            $query->where('is_preorder', true);
        }

        match ($request->input('sort')) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name' => $query->orderBy('name'),
            default => $query->orderByDesc('is_featured')->orderBy('name'),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::query()->withCount('products')->orderBy('name')->get();

        // Koleksi terkurasi untuk tampilan default katalog (tanpa filter)
        $readyProducts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('is_preorder', false)
            ->where('stock', '>', 0)
            ->orderByDesc('is_featured')
            ->limit(4)
            ->get();

        $preorderProducts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('is_preorder', true)
            ->orderByDesc('is_featured')
            ->limit(3)
            ->get();

        $hydroponicProducts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('name', 'like', '%hidroponik%')
                    ->orWhere('short_description', 'like', '%hidroponik%')
                    ->orWhere('description', 'like', '%hidroponik%');
            })
            ->limit(3)
            ->get();

        if ($hydroponicProducts->isEmpty()) {
            $hydroponicProducts = $readyProducts->take(2);
        }

        $activePreOrder = class_exists(PreOrder::class)
            ? PreOrder::query()->where('is_active', true)->latest()->first()
            : null;

        return view('catalog', [
            'isFiltering' => $isFiltering,
            'products' => $products,
            'readyProducts' => $readyProducts,
            'preorderProducts' => $preorderProducts,
            'hydroponicProducts' => $hydroponicProducts,
            'activePreOrder' => $activePreOrder,
            'categories' => $categories,
            'selectedCategory' => $request->input('category'),
            'search' => $request->input('q', ''),
            'availability' => $request->input('availability', ''),
            'sort' => $request->input('sort', ''),
        ]);
    }
}
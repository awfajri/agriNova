<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
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

        return view('catalog', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $request->input('category'),
            'search' => $request->input('q', ''),
            'availability' => $request->input('availability', ''),
            'sort' => $request->input('sort', ''),
        ]);
    }
}

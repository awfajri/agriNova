<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        $product->load('category');

        $relatedProducts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '<>', $product->id)
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}

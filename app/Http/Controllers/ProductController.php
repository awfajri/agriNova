<?php

namespace App\Http\Controllers;

use App\Models\Order;
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

        $reviews = $product->reviews()
            ->approved()
            ->with('user:id,name,avatar_url')
            ->latest()
            ->limit(8)
            ->get();
        $reviewCount = $product->reviews()->approved()->count();
        $averageRating = (float) ($product->reviews()->approved()->avg('rating') ?? 0);
        $reviewableOrder = null;
        $existingReview = null;

        if (auth()->check()) {
            $reviewableOrder = Order::query()
                ->where('user_id', auth()->id())
                ->where('payment_status', 'paid')
                ->whereHas('items', fn ($query) => $query->where('product_id', $product->id))
                ->latest('id')
                ->first();

            if ($reviewableOrder) {
                $existingReview = $product->reviews()
                    ->where('user_id', auth()->id())
                    ->where('order_id', $reviewableOrder->id)
                    ->first();
            }
        }

        return view('products.show', compact(
            'product',
            'relatedProducts',
            'reviews',
            'reviewCount',
            'averageRating',
            'reviewableOrder',
            'existingReview',
        ));
    }
}

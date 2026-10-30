<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $order = Order::query()
            ->where('user_id', $request->user()->id)
            ->where('payment_status', 'paid')
            ->whereHas('items', fn ($query) => $query->where('product_id', $product->id))
            ->latest('id')
            ->first();

        if (! $order) {
            throw ValidationException::withMessages([
                'review' => 'Ulasan hanya bisa diberikan setelah kamu menyelesaikan pembelian produk ini.',
            ]);
        }

        $product->reviews()->updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'order_id' => $order->id,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'is_approved' => true,
            ],
        );

        return back()->with('success', 'Rating dan komentar kamu sudah tersimpan.');
    }
}

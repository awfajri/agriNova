<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;

class OrderStockService
{
    public function releaseReservedStock(Order $order): void
    {
        if ($order->stock_released_at !== null) {
            return;
        }

        $order->loadMissing('items');
        $productIds = $order->items
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($order->items as $item) {
            $product = $products->get($item->product_id);
            if ($product) {
                $product->increment('stock', $item->quantity);
            }
        }

        $order->forceFill(['stock_released_at' => now()])->save();
    }
}

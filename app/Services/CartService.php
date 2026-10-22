<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class CartService
{
    private const SESSION_KEY = 'cart';

    /**
     * @return array<string, int>
     */
    public function raw(): array
    {
        $cart = session()->get(self::SESSION_KEY, []);

        if (! is_array($cart)) {
            return [];
        }

        return collect($cart)
            ->mapWithKeys(fn ($quantity, $productId) => [(string) $productId => (int) $quantity])
            ->filter(fn (int $quantity) => $quantity > 0)
            ->all();
    }

    public function add(Product $product, int $quantity): void
    {
        $this->ensurePurchasable($product);

        $cart = $this->raw();
        $productId = (string) $product->id;
        $newQuantity = ($cart[$productId] ?? 0) + $quantity;

        $this->ensureQuantityAvailable($product, $newQuantity);

        $cart[$productId] = $newQuantity;
        $this->put($cart);
    }

    /**
     * @param array<string|int, int|string> $quantities
     */
    public function update(array $quantities): void
    {
        $cart = $this->raw();
        $productIds = array_keys($cart);
        $products = Product::query()
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy(fn (Product $product) => (string) $product->id);

        foreach ($quantities as $productId => $quantity) {
            $productId = (string) $productId;
            $quantity = (int) $quantity;

            if (! isset($cart[$productId]) || ! isset($products[$productId])) {
                continue;
            }

            $product = $products[$productId];
            $this->ensurePurchasable($product);
            $this->ensureQuantityAvailable($product, $quantity);
            $cart[$productId] = $quantity;
        }

        $this->put($cart);
    }

    public function remove(Product $product): void
    {
        $cart = $this->raw();
        unset($cart[(string) $product->id]);
        $this->put($cart);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * @return Collection<int, array{product: Product, quantity: int, line_total: float, available: bool}>
     */
    public function items(): Collection
    {
        $cart = $this->raw();

        if ($cart === []) {
            return collect();
        }

        $products = Product::query()
            ->with('category')
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy(fn (Product $product) => (string) $product->id);

        return collect($cart)
            ->map(function (int $quantity, string $productId) use ($products) {
                $product = $products->get($productId);

                if (! $product) {
                    return null;
                }

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'line_total' => (float) $product->price * $quantity,
                    'available' => $product->is_active && $product->stock >= $quantity,
                ];
            })
            ->filter()
            ->values();
    }

    public function count(): int
    {
        return array_sum($this->raw());
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum('line_total');
    }

    private function put(array $cart): void
    {
        session()->put(self::SESSION_KEY, $cart);
    }

    private function ensurePurchasable(Product $product): void
    {
        if (! $product->is_active) {
            throw ValidationException::withMessages([
                'cart' => "Produk {$product->name} sedang tidak tersedia.",
            ]);
        }

        if ($product->stock < $product->min_order_quantity) {
            throw ValidationException::withMessages([
                'cart' => "Stok {$product->name} belum mencukupi minimum pemesanan.",
            ]);
        }
    }

    private function ensureQuantityAvailable(Product $product, int $quantity): void
    {
        if ($quantity < $product->min_order_quantity) {
            throw ValidationException::withMessages([
                'cart' => "Minimum pemesanan {$product->name} adalah {$product->min_order_quantity}.",
            ]);
        }

        if ($quantity > $product->stock) {
            throw ValidationException::withMessages([
                'cart' => "Jumlah {$product->name} melebihi stok yang tersedia ({$product->stock}).",
            ]);
        }
    }
}

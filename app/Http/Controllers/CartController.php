<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function index(): View
    {
        return view('cart.index', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
            'buy_now' => ['nullable', 'boolean'],
        ]);

        $product = Product::query()->findOrFail($validated['product_id']);
        $this->cart->add($product, $validated['quantity']);

        if ($request->boolean('buy_now')) {
            if (! auth()->check()) {
                return redirect()
                    ->route('catalog')
                    ->with('auth_required', true)
                    ->with('auth_intended', route('checkout.create'))
                    ->with('success', "{$product->name} siap dibeli. Login dengan Google untuk melanjutkan.");
            }

            return redirect()->route('checkout.create');
        }

        return redirect()->back()->with('success', "{$product->name} ditambahkan ke keranjang.");
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'quantities' => ['required', 'array'],
            'quantities.*' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $this->cart->update($validated['quantities']);

        return redirect()
            ->route('cart')
            ->with('success', 'Keranjang diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->cart->remove($product);

        return redirect()
            ->route('cart')
            ->with('success', "{$product->name} dihapus dari keranjang.");
    }
}

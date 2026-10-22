<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function create(): View|RedirectResponse
    {
        if ($this->cart->count() === 0) {
            return redirect()
                ->route('cart')
                ->with('error', 'Keranjang masih kosong.');
        }

        return view('checkout.create', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_whatsapp' => ['required', 'string', 'max:24', 'regex:/^[0-9+()\\s-]{8,24}$/'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:manual_transfer'],
        ]);

        $order = DB::transaction(function () use ($validated): Order {
            $cart = $this->cart->raw();
            $products = Product::query()
                ->whereIn('id', array_keys($cart))
                ->lockForUpdate()
                ->get()
                ->keyBy(fn (Product $product) => (string) $product->id);

            if ($cart === [] || $products->count() !== count($cart)) {
                throw ValidationException::withMessages([
                    'cart' => 'Ada produk di keranjang yang sudah tidak tersedia. Silakan periksa kembali.',
                ]);
            }

            $subtotal = 0;
            $orderItems = [];

            foreach ($cart as $productId => $quantity) {
                $product = $products->get((string) $productId);

                if (! $product->is_active || $quantity < $product->min_order_quantity || $quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'cart' => "Stok {$product->name} berubah. Silakan perbarui keranjang.",
                    ]);
                }

                $lineTotal = (float) $product->price * $quantity;
                $subtotal += $lineTotal;
                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'total_price' => $lineTotal,
                ];
            }

            $order = Order::query()->create([
                'order_number' => $this->makeOrderNumber(),
                'customer_name' => $validated['customer_name'],
                'customer_whatsapp' => $validated['customer_whatsapp'],
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'subtotal' => $subtotal,
                'shipping_cost' => 0,
                'total' => $subtotal,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
                $products->get((string) $item['product_id'])->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        $this->cart->clear();
        session()->put('last_order_number', $order->order_number);

        return redirect()->route('order.success', $order->order_number);
    }

    public function success(string $orderNumber): View
    {
        abort_unless(session('last_order_number') === $orderNumber, 404);

        $order = Order::query()
            ->with('items')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }

    private function makeOrderNumber(): string
    {
        do {
            $number = 'AGR-' . now()->format('ymd') . '-' . Str::upper(Str::random(6));
        } while (Order::query()->where('order_number', $number)->exists());

        return $number;
    }
}

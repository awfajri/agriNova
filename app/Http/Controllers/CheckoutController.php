<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
    ) {
    }

    /**
     * Step 1: Form Detail Pemesanan
     */
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
            'user' => Auth::user(),
        ]);
    }

    /**
     * Simpan pesanan & reservasi stok selama 15 menit
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_whatsapp' => ['required', 'string', 'max:24', 'regex:/^[0-9+()\s-]{8,24}$/'],
            'pickup_date' => [
                'required',
                'date',
                'after_or_equal:' . Order::pickupNow()->toDateString(),
                'before_or_equal:' . Order::pickupNow()->addDays(Order::pickupDateMaxDays())->toDateString(),
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (Order::availablePickupTimes((string) $value) === []) {
                        $fail('Hari ini sudah tidak ada jam ambil yang tersedia. Silakan pilih tanggal lain.');
                    }
                },
            ],
            'pickup_time' => [
                'required',
                'string',
                Rule::in(array_keys(Order::pickupTimeOptions())),
                function (string $attribute, mixed $value, \Closure $fail) use ($request): void {
                    if (! Order::isPickupSlotAvailable($request->input('pickup_date'), (string) $value)) {
                        $fail('Jam ambil yang kamu pilih sudah lewat. Silakan pilih jam lain.');
                    }
                },
            ],
            'notes' => ['nullable', 'string', 'max:1000'],
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
                'user_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'customer_whatsapp' => $validated['customer_whatsapp'],
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'manual_transfer',
                'payment_provider' => 'qris_static',
                'payment_expires_at' => now()->addMinutes(15),
                'subtotal' => $subtotal,
                'shipping_cost' => 0,
                'total' => $subtotal,
                'notes' => $validated['notes'] ?? null,
                'pickup_time' => $validated['pickup_time'],
                'pickup_date' => $validated['pickup_date'],
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
                $products->get((string) $item['product_id'])->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        $this->cart->clear();

        return redirect()->route('checkout.payment', $order->order_number);
    }

    /**
     * Step 2: Halaman Pembayaran QRIS (Countdown 15 Menit + Unduh QRIS)
     */
    public function payment(string $orderNumber): View|RedirectResponse
    {
        $order = Order::query()
            ->with('items')
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status === 'canceled' || $order->payment_status === 'paid') {
            return redirect()->route('order.success', $order->order_number);
        }

        return view('checkout.payment', compact('order'));
    }

    /**
     * Step 3: Halaman Detail Konfirmasi & Redirect WhatsApp
     */
    public function success(string $orderNumber): View
    {
        $order = Order::query()
            ->with('items')
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $whatsappUrl = $this->buildWhatsAppConfirmationUrl($order);

        return view('checkout.success', compact('order', 'whatsappUrl'));
    }

    private function buildWhatsAppConfirmationUrl(Order $order): string
    {
        $adminPhone = config('agrinova.whatsapp_number', '6285156287360');
        $itemsText = $order->items
            ->map(fn ($item) => "- {$item->product_name} ({$item->quantity}x)")
            ->implode("\n");

        $totalFormatted = 'Rp' . number_format((float) $order->total, 0, ',', '.');

        $pickupLabel = $order->pickupScheduleLabel();

        $message = "Halo Admin Agrinova, saya ingin konfirmasi pembayaran pesanan:\n\n"
            . "ID Pesanan: *#{$order->order_number}*\n"
            . "Nama: {$order->customer_name}\n"
            . "Rincian:\n{$itemsText}\n"
            . "Total Bayar: *{$totalFormatted}*\n"
            . "Metode: Ambil di Kebun\n"
            . ($pickupLabel ? "Rencana Ambil: {$pickupLabel}\n" : '')
            . "\nBerikut saya lampirkan bukti transfer QRIS-nya:";

        return 'https://wa.me/' . preg_replace('/[^0-9]/', '', $adminPhone) . '?text=' . urlencode($message);
    }

    private function makeOrderNumber(): string
    {
        $attempts = 0;
        do {
            if (++$attempts > 10) {
                throw new \RuntimeException('Gagal membuat nomor order unik.');
            }
            $number = 'AGR-' . now()->format('ymd') . '-' . Str::upper(Str::random(6));
        } while (Order::query()->where('order_number', $number)->exists());

        return $number;
    }
}
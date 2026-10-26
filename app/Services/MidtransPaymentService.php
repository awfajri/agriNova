<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;
use RuntimeException;

class MidtransPaymentService
{
    public function __construct()
    {
        Config::$serverKey = (string) config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createSnapToken(Order $order): string
    {
        $this->ensureConfigured();
        $order->loadMissing('items');

        $itemDetails = $order->items->map(function ($item): array {
            return [
                'id' => (string) ($item->product_id ?? $item->id),
                'price' => (int) round((float) $item->unit_price),
                'quantity' => $item->quantity,
                'name' => $item->product_name,
            ];
        })->values()->all();

        return Snap::getSnapToken([
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) round((float) $order->total),
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $order->customer_name,
                'phone' => $order->customer_whatsapp,
            ],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'hour',
                'duration' => 24,
            ],
        ]);
    }

    private function ensureConfigured(): void
    {
        if (! filled(config('services.midtrans.server_key')) || ! filled(config('services.midtrans.client_key'))) {
            throw new RuntimeException('Kredensial Midtrans belum dikonfigurasi lengkap.');
        }
    }
}

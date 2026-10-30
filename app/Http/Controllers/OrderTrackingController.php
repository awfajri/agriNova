<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function show(Request $request, string $orderNumber): View
    {
        $order = Order::query()
            ->with('items')
            ->where('order_number', $orderNumber)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return view('orders.tracking', [
            'order' => $order,
            'statuses' => $this->statuses($order),
        ]);
    }

    private function statuses(Order $order): array
    {
        $currentStatus = match ($order->status) {
            'pending' => 'pending',
            'processing' => 'processing',
            'shipped' => 'shipped',
            'completed' => 'completed',
            'canceled' => 'canceled',
            default => 'pending',
        };

        $steps = [
            'pending' => ['label' => 'Pesanan diterima', 'description' => 'Pesanan sudah tercatat di sistem Agrinova.'],
            'processing' => ['label' => 'Sedang diproses', 'description' => 'Pesanan sedang disiapkan oleh tim Agrinova.'],
            'shipped' => ['label' => 'Dalam pengiriman', 'description' => 'Pesanan sudah diserahkan untuk dikirim.'],
            'completed' => ['label' => 'Selesai', 'description' => 'Pesanan telah selesai.'],
        ];

        if ($currentStatus === 'canceled') {
            return [
                ['key' => 'canceled', 'label' => 'Pesanan dibatalkan', 'description' => 'Pesanan ini tidak lagi diproses.', 'active' => true],
            ];
        }

        $keys = array_keys($steps);
        $currentIndex = array_search($currentStatus, $keys, true);

        return array_map(
            fn (string $key, int $index): array => [
                'key' => $key,
                'label' => $steps[$key]['label'],
                'description' => $steps[$key]['description'],
                'active' => $index <= $currentIndex,
                'current' => $key === $currentStatus,
            ],
            $keys,
            array_keys($keys),
        );
    }
}

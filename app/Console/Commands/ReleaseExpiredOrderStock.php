<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\OrderStockService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReleaseExpiredOrderStock extends Command
{
    protected $signature = 'orders:release-expired-stock';

    protected $description = 'Release stock reserved by expired Midtrans orders';

    public function handle(OrderStockService $stock): int
    {
        $orderIds = Order::query()
            ->where('payment_method', 'midtrans_snap')
            ->where('payment_status', 'pending')
            ->whereNotNull('payment_expires_at')
            ->where('payment_expires_at', '<=', now())
            ->pluck('id');

        foreach ($orderIds as $orderId) {
            DB::transaction(function () use ($orderId, $stock): void {
                $order = Order::query()
                    ->with('items')
                    ->whereKey($orderId)
                    ->lockForUpdate()
                    ->first();

                if (! $order || $order->payment_status !== 'pending' || $order->stock_released_at !== null) {
                    return;
                }

                $order->forceFill([
                    'status' => 'canceled',
                    'payment_status' => 'expire',
                ])->save();
                $stock->releaseReservedStock($order);
            });
        }

        $this->info(sprintf('Released stock for %d expired order(s).', $orderIds->count()));

        return self::SUCCESS;
    }
}

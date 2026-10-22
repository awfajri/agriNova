<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderStockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MidtransNotificationController extends Controller
{
    public function __construct(private readonly OrderStockService $stock) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => ['required', 'string', 'max:100'],
            'status_code' => ['required', 'string', 'max:10'],
            'gross_amount' => ['required', 'string', 'max:30'],
            'signature_key' => ['required', 'string', 'size:128'],
            'transaction_status' => ['required', 'string', 'max:30'],
            'transaction_id' => ['nullable', 'string', 'max:100'],
            'fraud_status' => ['nullable', 'string', 'max:30'],
        ]);

        $serverKey = (string) config('services.midtrans.server_key');
        if ($serverKey === '') {
            return response()->json(['message' => 'Midtrans belum dikonfigurasi.'], 500);
        }

        $signature = hash(
            'sha512',
            $validated['order_id'].$validated['status_code'].$validated['gross_amount'].$serverKey
        );

        if (! hash_equals($signature, $validated['signature_key'])) {
            return response()->json(['message' => 'Signature notification tidak valid.'], 403);
        }

        $order = Order::query()->where('order_number', $validated['order_id'])->first();
        if (! $order) {
            return response()->json(['message' => 'Order tidak ditemukan.'], 404);
        }

        if (number_format((float) $order->total, 2, '.', '') !== $validated['gross_amount']) {
            return response()->json(['message' => 'Nominal notification tidak sesuai order.'], 422);
        }

        DB::transaction(function () use ($validated): void {
            $order = Order::query()
                ->with('items')
                ->where('order_number', $validated['order_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $transactionStatus = strtolower($validated['transaction_status']);
            $paymentReference = $validated['transaction_id'] ?? $validated['order_id'];

            $captureIsAccepted = $transactionStatus === 'capture'
                && in_array(strtolower((string) ($validated['fraud_status'] ?? 'accept')), ['accept', ''], true);

            if ($transactionStatus === 'settlement' || $captureIsAccepted) {
                $order->forceFill([
                    'payment_status' => 'paid',
                    'payment_reference' => $paymentReference,
                    'paid_at' => $order->paid_at ?? now(),
                    'status' => $order->status === 'pending' ? 'processing' : $order->status,
                ])->save();

                return;
            }

            if (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'], true)) {
                $order->forceFill([
                    'payment_status' => $transactionStatus,
                    'payment_reference' => $paymentReference,
                    'status' => 'canceled',
                ])->save();

                $this->stock->releaseReservedStock($order);

                return;
            }

            $order->forceFill([
                'payment_status' => $transactionStatus,
                'payment_reference' => $paymentReference,
            ])->save();
        });

        return response()->json(['ok' => true]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderStockService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminOrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->when($request->filled('q'), function ($query) use ($request): void {
                $search = '%'.trim((string) $request->input('q')).'%';
                $query->where(function ($orderQuery) use ($search): void {
                    $orderQuery->where('order_number', 'like', $search)
                        ->orWhere('customer_name', 'like', $search);
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load('items', 'user');

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order, OrderStockService $stock): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'processing', 'shipped', 'completed', 'canceled'])],
            'payment_status' => ['required', Rule::in(['pending', 'paid', 'failure', 'expire', 'cancel', 'deny'])],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated, $order, $stock): void {
            $lockedOrder = Order::query()
                ->with('items')
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            $paymentStatus = $validated['payment_status'];
            $lockedOrder->forceFill([
                'status' => $validated['status'],
                'payment_status' => $paymentStatus,
                'paid_at' => $paymentStatus === 'paid' ? ($lockedOrder->paid_at ?? now()) : $lockedOrder->paid_at,
                'notes' => $validated['notes'] ?? null,
            ])->save();

            if (
                $validated['status'] === 'canceled'
                && in_array($paymentStatus, ['pending', 'failure', 'expire', 'cancel', 'deny'], true)
            ) {
                $stock->releaseReservedStock($lockedOrder);
            }
        });

        return back()->with('success', "Order {$order->order_number} berhasil diperbarui.");
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Contracts\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'metrics' => [
                'orders' => Order::query()->count(),
                'pendingOrders' => Order::query()->where('status', 'pending')->count(),
                'revenue' => Order::query()->where('payment_status', 'paid')->sum('total'),
                'lowStock' => Product::query()->where('is_active', true)->whereColumn('stock', '<=', 'min_order_quantity')->count(),
                'reviews' => ProductReview::query()->where('is_approved', false)->count(),
                'customers' => User::query()->where('is_admin', false)->count(),
            ],
            'recentOrders' => Order::query()->latest()->limit(8)->get(),
            'lowStockProducts' => Product::query()
                ->where('is_active', true)
                ->whereColumn('stock', '<=', 'min_order_quantity')
                ->orderBy('stock')
                ->limit(6)
                ->get(),
        ]);
    }
}

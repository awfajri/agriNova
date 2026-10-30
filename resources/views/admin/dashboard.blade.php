@extends('layouts.admin')

@section('title', 'Dashboard Admin | Agrinova')
@section('heading', 'Dashboard')

@section('content')
<div class="admin-metric-grid">
    <div class="admin-metric"><span>Total order</span><strong>{{ $metrics['orders'] }}</strong><small>{{ $metrics['pendingOrders'] }} menunggu diproses</small></div>
    <div class="admin-metric"><span>Pendapatan terbayar</span><strong>Rp {{ number_format((float) $metrics['revenue'], 0, ',', '.') }}</strong><small>Dari order berstatus paid</small></div>
    <div class="admin-metric"><span>Stok perlu dicek</span><strong>{{ $metrics['lowStock'] }}</strong><small>Di bawah batas minimum</small></div>
    <div class="admin-metric"><span>Review menunggu</span><strong>{{ $metrics['reviews'] }}</strong><small>{{ $metrics['customers'] }} akun pelanggan</small></div>
</div>

<div class="admin-grid-two">
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div><span class="eyebrow">Aktivitas terbaru</span><h2>Order terbaru</h2></div>
            <a href="{{ route('admin.orders.index') }}" class="admin-text-link">Lihat semua</a>
        </div>
        @forelse ($recentOrders as $order)
            <a href="{{ route('admin.orders.show', $order) }}" class="admin-list-row">
                <span><strong>{{ $order->order_number }}</strong><small>{{ $order->customer_name }}</small></span>
                <span><strong>Rp {{ number_format((float) $order->total, 0, ',', '.') }}</strong><small>{{ ucfirst($order->status) }}</small></span>
            </a>
        @empty
            <p class="admin-empty">Belum ada order.</p>
        @endforelse
    </section>

    <section class="admin-panel">
        <div class="admin-panel-head">
            <div><span class="eyebrow">Persediaan</span><h2>Stok rendah</h2></div>
            <a href="{{ route('admin.products.index') }}" class="admin-text-link">Kelola produk</a>
        </div>
        @forelse ($lowStockProducts as $product)
            <a href="{{ route('admin.products.edit', $product) }}" class="admin-list-row">
                <span><strong>{{ $product->name }}</strong><small>{{ $product->category->name ?? 'Tanpa kategori' }}</small></span>
                <span class="admin-stock-warning">{{ $product->stock }} unit</span>
            </a>
        @empty
            <p class="admin-empty">Semua stok masih aman.</p>
        @endforelse
    </section>
</div>
@endsection

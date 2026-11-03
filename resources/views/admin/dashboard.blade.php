@extends('layouts.admin')

@section('title', 'Dashboard Admin | Agrinova')
@section('heading', 'Dashboard')

@section('content')
<div class="admin-metric-grid">
    <div class="admin-metric"><span>Total order</span><strong>{{ $metrics['orders'] }}</strong><small>{{ $metrics['pendingOrders'] }} menunggu diproses</small></div>
    <div class="admin-metric"><span>Pendapatan terbayar</span><strong>Rp {{ number_format((float) $metrics['revenue'], 0, ',', '.') }}</strong><small>Dari order berstatus paid</small></div>
    <div class="admin-metric"><span>Stok perlu dicek</span><strong>{{ $metrics['lowStock'] }}</strong><small>Di bawah batas minimum</small></div>
    <div class="admin-metric"><span>Review menunggu</span><strong>{{ $metrics['reviews'] }}</strong><small>{{ $metrics['customers'] }} akun pelanggan</small></div>
    <div class="admin-metric"><span>Pendaftar pelatihan</span><strong>{{ $metrics['trainingRegistrations'] }}</strong><small>Perlu ditinjau</small></div>
    <div class="admin-metric"><span>Reservasi eduwisata</span><strong>{{ $metrics['eduwisataReservations'] }}</strong><small>Perlu dikonfirmasi</small></div>
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
                <span><strong>Rp {{ number_format((float) $order->total, 0, ',', '.') }}</strong><small>{{ $order->statusLabel() }}</small></span>
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

    <section class="admin-panel">
        <div class="admin-panel-head">
            <div><span class="eyebrow">Edukasi</span><h2>Permintaan terbaru</h2></div>
            <a href="{{ route('admin.training-registrations.index') }}" class="admin-text-link">Lihat pendaftar</a>
        </div>
        <a href="{{ route('admin.eduwisata.index') }}" class="admin-list-row">
            <span><strong>Reservasi eduwisata</strong><small>Kelola jadwal kunjungan dan kapasitas kebun</small></span>
            <span class="admin-stock-warning">{{ $metrics['eduwisataReservations'] }} menunggu</span>
        </a>
        <a href="{{ route('admin.trainings.index') }}" class="admin-list-row">
            <span><strong>Kelas pelatihan aktif</strong><small>Atur jadwal, harga, dan kuota</small></span>
            <span>{{ $metrics['trainingClasses'] }} kelas</span>
        </a>
    </section>
</div>
@endsection

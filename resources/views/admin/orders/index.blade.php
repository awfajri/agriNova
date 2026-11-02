@extends('layouts.admin')

@section('title', 'Kelola Order | Agrinova')
@section('heading', 'Kelola order')

@section('content')
<div class="admin-toolbar">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="admin-filter-form">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nomor order atau nama...">
        <select name="status" aria-label="Filter status order">
            <option value="">Semua status</option>
            @foreach (['pending', 'processing', 'shipped', 'completed', 'canceled'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <button type="submit" class="admin-button admin-button--secondary">Terapkan</button>
    </form>
</div>

<section class="admin-panel admin-table-panel">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Order</th><th>Pelanggan</th><th>Total</th><th>Pembayaran</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td><strong>{{ $order->order_number }}</strong><small>{{ $order->created_at->format('d M Y, H:i') }}</small></td>
                    <td><strong>{{ $order->customer_name }}</strong><small>{{ $order->customer_whatsapp }}</small></td>
                    <td>Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td>
                    <td><span class="admin-status admin-status--{{ $order->payment_status === 'paid' ? 'success' : 'muted' }}">{{ ucfirst($order->payment_status) }}</span></td>
                    <td><span class="admin-status admin-status--{{ $order->status === 'canceled' ? 'danger' : 'info' }}">{{ ucfirst($order->status) }}</span></td>
                    <td><a href="{{ route('admin.orders.show', $order) }}" class="admin-text-link">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="admin-empty">Belum ada order.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
</section>
@endsection

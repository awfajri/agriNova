@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number . ' | Agrinova')
@section('heading', 'Detail order')

@section('content')
<div class="admin-detail-head">
    <div>
        <span class="eyebrow">{{ $order->order_number }}</span>
        <h2>{{ $order->customer_name }}</h2>
        <p>{{ $order->customer_whatsapp }} · dibuat {{ $order->created_at->format('d M Y, H:i') }}</p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="admin-button admin-button--secondary">Kembali</a>
</div>

<div class="admin-grid-two">
    <section class="admin-panel">
        <div class="admin-panel-head"><div><span class="eyebrow">Isi order</span><h2>Produk</h2></div></div>
        @foreach ($order->items as $item)
            <div class="admin-list-row">
                <span><strong>{{ $item->product_name }} × {{ $item->quantity }}</strong><small>Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }} per unit</small></span>
                <strong>Rp {{ number_format((float) $item->total_price, 0, ',', '.') }}</strong>
            </div>
        @endforeach
        <div class="admin-total-row"><span>Total</span><strong>Rp {{ number_format((float) $order->total, 0, ',', '.') }}</strong></div>
    </section>

    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="admin-form-panel">
        @csrf @method('PUT')
        <div class="admin-panel-head"><div><span class="eyebrow">Operasional</span><h2>Perbarui status</h2></div></div>
        <div class="admin-form-field">
            <label for="order-status">Status order</label>
            <select id="order-status" name="status" required>
                @foreach (['pending', 'processing', 'shipped', 'completed', 'canceled'] as $status)
                    <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="admin-form-field">
            <label for="payment-status">Status pembayaran</label>
            <select id="payment-status" name="payment_status" required>
                @foreach (['pending', 'paid', 'failure', 'expire', 'cancel', 'deny'] as $status)
                    <option value="{{ $status }}" @selected($order->payment_status === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="admin-form-field">
            <label for="order-notes">Catatan</label>
            <textarea id="order-notes" name="notes" rows="5" maxlength="1000">{{ old('notes', $order->notes) }}</textarea>
        </div>
        <button type="submit" class="admin-button">Simpan perubahan</button>
    </form>
</div>
@endsection

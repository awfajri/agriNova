@extends('layouts.app')

@section('title', 'Lacak Pesanan ' . $order->order_number . ' | Agrinova')
@section('meta_description', 'Lacak status pesanan Agrinova berdasarkan nomor order.')
@section('body_class', 'has-order-tracking')

@section('content')
<section class="commerce-page">
    <div class="container commerce-shell">
        <div class="commerce-heading">
            <span class="eyebrow">Status pesanan</span>
            <h1>Lacak pesanan</h1>
            <p>Perubahan status pesanan akan tampil di halaman ini setelah diperbarui oleh tim Agrinova.</p>
        </div>

        <div class="tracking-card">
            <div class="tracking-card-head">
                <div>
                    <span>Nomor pesanan</span>
                    <strong>{{ $order->order_number }}</strong>
                </div>
                <div class="tracking-card-meta">
                    <span class="tracking-payment-status">{{ $order->statusLabel() }}</span>
                    <small>Diperbarui {{ $order->updated_at->format('d M Y, H:i') }}</small>
                </div>
            </div>

            <div class="tracking-timeline">
                @foreach ($statuses as $status)
                    <div class="tracking-step {{ $status['active'] ? 'is-active' : '' }} {{ ($status['current'] ?? false) ? 'is-current' : '' }}">
                        <span class="tracking-step-marker" aria-hidden="true"></span>
                        <div>
                            <strong>{{ $status['label'] }}</strong>
                            <p>{{ $status['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="tracking-items">
                <span class="eyebrow">Isi pesanan</span>
                @foreach ($order->items as $item)
                    <div class="tracking-item">
                        <span>{{ $item->product_name }} <strong>&times; {{ $item->quantity }}</strong></span>
                        <strong>Rp {{ number_format((float) $item->total_price, 0, ',', '.') }}</strong>
                    </div>
                @endforeach
            </div>

            <div class="tracking-pickup-note">
                <strong>Pengambilan di lokasi Agrinova</strong>
                <p>Pesanan tidak dikirim melalui kurir. Tunggu status berubah menjadi “Siap diambil”, lalu bawa nomor pesanan saat datang ke lokasi Agrinova.</p>
            </div>
        </div>
    </div>
</section>
@endsection

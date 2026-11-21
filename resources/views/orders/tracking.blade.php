@extends('layouts.app')

@section('title', 'Lacak Pesanan ' . $order->order_number . ' | Agrinova')
@section('meta_description', 'Lacak status pesanan Agrinova berdasarkan nomor order.')
@section('body_class', 'has-order-tracking')

@section('content')
@php
    $isUnpaid = $order->payment_status === 'pending' && $order->status !== 'canceled';
    $isExpired = $order->payment_expires_at && $order->payment_expires_at->isPast();
@endphp

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

            @if ($isUnpaid)
                <div class="tracking-payment-action" style="margin: 1rem 0; padding: 1rem; border-radius: 12px; background: #f4f8f5; border: 1px solid #d5e5da; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.75rem;">
                    <div>
                        <strong style="display: block; color: #163a28;">
                            {{ $isExpired ? 'Batas waktu pembayaran telah habis' : 'Menunggu pembayaran QRIS' }}
                        </strong>
                        <small style="color: #4a6356;">
                            @if ($isExpired)
                                Silakan lanjut ke halaman konfirmasi jika sudah melakukan transfer, atau buat pesanan baru.
                            @elseif ($order->payment_expires_at)
                                Selesaikan pembayaran sebelum {{ $order->payment_expires_at->format('H:i') }} WIB (Total: Rp{{ number_format((float) $order->total, 0, ',', '.') }}).
                            @else
                                Lanjutkan pembayaran QRIS sebesar Rp{{ number_format((float) $order->total, 0, ',', '.') }} untuk memproses pesananmu.
                            @endif
                        </small>
                    </div>
                    <a href="{{ route('checkout.payment', $order->order_number) }}" class="agr-flow-primary-btn" style="width: auto; padding: 0.65rem 1.25rem; text-decoration: none;">
                        Lanjut Pembayaran
                    </a>
                </div>
            @endif

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
@extends('layouts.app')

@section('title', 'Pesanan Berhasil | Agrinova')
@section('meta_description', 'Pesanan Agrinova berhasil dicatat. Simpan nomor pesanan untuk konfirmasi berikutnya.')
@section('body_class', 'has-checkout')

@section('content')
<section class="commerce-page">
    <div class="container commerce-shell">
        <div class="order-success-card">
            <div class="order-success-icon" aria-hidden="true">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 4 4L19 6"/></svg>
            </div>
            <span class="eyebrow">Pesanan tercatat</span>
            <h1>Terima kasih, {{ $order->customer_name }}.</h1>
            <p>Pesananmu sudah masuk ke Agrinova. Simpan nomor ini dan tunggu konfirmasi melalui WhatsApp.</p>
            <div class="order-number">
                <span>Nomor pesanan</span>
                <strong>{{ $order->order_number }}</strong>
            </div>
            <div class="order-success-total">
                <span>Total sementara</span>
                <strong>Rp {{ number_format((float) $order->total, 0, ',', '.') }}</strong>
            </div>
            <div class="order-next-step">
                <strong>Langkah berikutnya</strong>
                <p>Admin akan mengecek stok dan mengirimkan detail pembayaran manual ke nomor WhatsApp yang kamu berikan.</p>
            </div>
            <div class="order-success-actions">
                <a href="{{ route('catalog') }}" class="catalog-action">Kembali belanja</a>
                <a href="{{ route('home') }}" class="catalog-action catalog-action--secondary">Ke beranda</a>
            </div>
        </div>
    </div>
</section>
@endsection

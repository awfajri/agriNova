@extends('layouts.app')

@section('title', 'Detail Pemesanan - Agrinova')
@section('meta_description', 'Lengkapi data pemesan untuk menyelesaikan pesanan produk Agrinova.')
@section('body_class', 'page-checkout-flow')

@section('content')
@php
    $formattedSubtotal = 'Rp' . number_format((float) $subtotal, 0, ',', '.');
@endphp

<div class="agr-flow-page">
    <div class="agr-flow-container">
        {{-- Mobile Top Bar (Hanya tampil di < 768px) --}}
        <header class="agr-flow-mobile-topbar">
            <a href="{{ route('cart') }}" class="agr-flow-back" aria-label="Kembali ke keranjang">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </a>
            <h1 class="agr-flow-title">Detail pemesanan</h1>
        </header>

        {{-- Stepper (1: Active, 2: Pending, 3: Pending) --}}
        <nav class="agr-flow-stepper" aria-label="Tahapan pemesanan">
            <div class="agr-flow-step">
                <div class="agr-flow-step-circle is-active">1</div>
                <span class="agr-flow-step-label is-active">Detail</span>
            </div>

            <div class="agr-flow-step-line"></div>

            <div class="agr-flow-step">
                <div class="agr-flow-step-circle is-pending">2</div>
                <span class="agr-flow-step-label is-pending">Pembayaran</span>
            </div>

            <div class="agr-flow-step-line"></div>

            <div class="agr-flow-step">
                <div class="agr-flow-step-circle is-pending">3</div>
                <span class="agr-flow-step-label is-pending">Konfirmasi</span>
            </div>
        </nav>

        @if ($errors->any())
            <div class="agr-flow-alert" role="alert">
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Ringkasan Produk di Atas --}}
        <section class="agr-flow-items-card">
            @foreach ($items as $item)
                @php
                    $product = $item['product'];
                    $imageUrl = $product->image_path ? asset('storage/' . $product->image_path) : null;
                @endphp
                <div class="agr-flow-product-row">
                    @if ($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="agr-flow-product-thumb">
                    @else
                        <div class="agr-flow-product-thumb is-placeholder">AGR</div>
                    @endif
                    <div class="agr-flow-product-info">
                        <h2>{{ $product->name }}</h2>
                        <p>{{ $product->is_preorder ? 'Pre-order' : 'Siap panen' }} &middot; {{ $item['quantity'] }} {{ $product->unit ?? 'kg' }}</p>
                        @if ($product->is_preorder && $product->harvest_estimate)
                            <p class="agr-flow-harvest-date">Panen {{ \Carbon\Carbon::parse($product->harvest_estimate)->translatedFormat('d M Y') }}</p>
                        @endif
                        <strong>Rp{{ number_format((float) $item['line_total'], 0, ',', '.') }}</strong>
                    </div>
                </div>
            @endforeach
        </section>

        {{-- Form Data Pemesan --}}
        <form id="checkout-create-form" method="POST" action="{{ route('checkout.store') }}" class="agr-flow-form">
            @csrf
            <h2 class="agr-flow-section-heading">Data pemesan</h2>

            <div class="agr-flow-field">
                <label for="customer-name">Nama pemesan <span class="is-required">*</span></label>
                <input id="customer-name"
                       name="customer_name"
                       type="text"
                       value="{{ old('customer_name', $user->name ?? '') }}"
                       autocomplete="name"
                       required
                       maxlength="120">
                <small>Otomatis dari akun Google-mu.</small>
            </div>

            <div class="agr-flow-field">
                <label for="customer-whatsapp">Nomor WhatsApp <span class="is-required">*</span></label>
                <input id="customer-whatsapp"
                       name="customer_whatsapp"
                       type="tel"
                       value="{{ old('customer_whatsapp', $user->phone ?? '') }}"
                       placeholder="Contoh: 081234567890"
                       autocomplete="tel"
                       required
                       maxlength="24">
                <small>Wajib aktif WhatsApp untuk pengiriman jadwal panen &amp; bukti bayar.</small>
            </div>

            <div class="agr-flow-field">
                <label for="order-notes">Rencana waktu ambil / catatan <span>(opsional)</span></label>
                <textarea id="order-notes"
                          name="notes"
                          rows="3"
                          maxlength="1000"
                          placeholder="Misal: Rencana ambil pagi sekitar jam 09.00 WIB">{{ old('notes') }}</textarea>
            </div>

            {{-- Ringkasan Pembayaran --}}
            <div class="agr-flow-summary-box">
                <h3 class="agr-flow-section-heading">Ringkasan pesanan</h3>
                <div class="agr-flow-summary-row">
                    <span>Subtotal ({{ count($items) }} produk)</span>
                    <strong>{{ $formattedSubtotal }}</strong>
                </div>
                <div class="agr-flow-summary-row">
                    <span>Metode pengambilan</span>
                    <strong class="is-free">Ambil di kebun (Gratis)</strong>
                </div>
                <p class="agr-flow-summary-note">
                    Jam operasional kebun: 08.00 – 16.00 WIB (jadwal pasti dikonfirmasi admin via WhatsApp).
                </p>
            </div>

            {{-- Bottom Sticky Bar --}}
            <div class="agr-flow-bottom-bar">
                <div class="agr-flow-bottom-inner">
                    <div class="agr-flow-bottom-total">
                        <span>Total pembayaran</span>
                        <strong>{{ $formattedSubtotal }}</strong>
                    </div>
                    <button type="submit" class="agr-flow-primary-btn">Lanjut ke pembayaran</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Detail Konfirmasi - Agrinova')
@section('meta_description', 'Pesanan Agrinova tercatat. Kirim bukti transfer QRIS via WhatsApp untuk validasi pesanan.')
@section('body_class', 'page-checkout-flow')

@section('content')
@php
    $formattedTotal = 'Rp' . number_format((float) $order->total, 0, ',', '.');
    $waLink = $whatsappUrl ?? null;
@endphp

<div class="agr-flow-page">
    <div class="agr-flow-container">
        {{-- Mobile Top Bar (Hanya tampil di < 768px) --}}
        <header class="agr-flow-mobile-topbar">
            <a href="{{ route('catalog') }}" class="agr-flow-back" aria-label="Kembali ke katalog">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </a>
            <h1 class="agr-flow-title">Detail Konfirmasi</h1>
        </header>

        {{-- Stepper (1: Done, 2: Done, 3: Active) --}}
        <nav class="agr-flow-stepper" aria-label="Tahapan pemesanan">
            <div class="agr-flow-step">
                <div class="agr-flow-step-circle is-done">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span class="agr-flow-step-label">Detail</span>
            </div>

            <div class="agr-flow-step-line is-done"></div>

            <div class="agr-flow-step">
                <div class="agr-flow-step-circle is-done">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span class="agr-flow-step-label">Pembayaran</span>
            </div>

            <div class="agr-flow-step-line is-done"></div>

            <div class="agr-flow-step">
                <div class="agr-flow-step-circle is-active">3</div>
                <span class="agr-flow-step-label is-active">Konfirmasi</span>
            </div>
        </nav>

        {{-- Status Hero --}}
        <section class="agr-confirm-hero">
            <div class="agr-confirm-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                </svg>
            </div>

            <h2 class="agr-confirm-heading">Pesanan Tercatat &mdash; Kirim Bukti Bayar</h2>

            <span class="agr-confirm-badge">Menunggu Bukti Transfer</span>

            <p class="agr-confirm-subtitle">
                Lampirkan tangkapan layar bukti transfer via WhatsApp agar admin segera memvalidasi pesananmu.
            </p>
        </section>

        {{-- Kartu Rincian Pesanan --}}
        <section class="agr-confirm-receipt">
            <div class="agr-confirm-receipt-head">
                <span id="order-number-text">#{{ $order->order_number }}</span>
                <button type="button"
                        class="agr-copy-btn"
                        data-copy-target="#{{ $order->order_number }}"
                        aria-label="Salin kode pesanan">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <rect x="9" y="9" width="11" height="11" rx="2"/>
                        <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                    </svg>
                    <span>Salin</span>
                </button>
            </div>

            <div class="agr-confirm-receipt-rows">
                @foreach ($order->items as $item)
                    <div class="agr-confirm-row">
                        <span>{{ $item->product_name }}</span>
                        <strong>{{ $item->quantity }}x</strong>
                    </div>
                @endforeach

                <div class="agr-confirm-row">
                    <span>Metode pengambilan</span>
                    <strong>Ambil di kebun</strong>
                </div>

                <div class="agr-confirm-row is-total">
                    <span>Total pembayaran</span>
                    <strong>{{ $formattedTotal }}</strong>
                </div>
            </div>
        </section>
    </div>

    {{-- Bottom Action Area --}}
    <div class="agr-flow-bottom-bar is-confirm">
        <div class="agr-flow-bottom-inner">
            <h3 class="agr-confirm-action-title">Konfirmasi pembayaran</h3>
            <p class="agr-confirm-action-desc">
                Rincian pesanan otomatis terisi di WhatsApp. Kamu cukup melampirkan bukti transfer.
            </p>

            @if ($waLink)
                <a href="{{ $waLink }}" target="_blank" rel="noopener" class="agr-flow-primary-btn">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18" aria-hidden="true">
                        <path d="M12.04 2a9.96 9.96 0 0 0-8.58 15.08L2 22l5.08-1.42A9.96 9.96 0 1 0 12.04 2Zm0 18.18a8.2 8.2 0 0 1-4.18-1.14l-.3-.18-3.02.84.85-2.94-.2-.3a8.2 8.2 0 1 1 6.85 3.72Zm4.5-6.15c-.25-.13-1.48-.73-1.71-.82-.23-.08-.4-.13-.57.13-.17.25-.65.82-.8.99-.15.17-.3.19-.55.06-.25-.13-1.04-.38-1.98-1.21-.73-.65-1.22-1.45-1.36-1.7-.14-.25-.01-.39.11-.52.11-.11.25-.3.38-.44.13-.15.17-.25.25-.42.08-.17.04-.32-.02-.44-.06-.13-.57-1.36-.78-1.86-.21-.49-.42-.42-.57-.43h-.49c-.17 0-.44.06-.67.32-.23.25-.88.86-.88 2.09s.9 2.42 1.03 2.59c.13.17 1.77 2.71 4.29 3.8.6.26 1.07.41 1.43.53.6.19 1.14.16 1.57.1.48-.07 1.48-.61 1.69-1.2.21-.59.21-1.1.15-1.2-.06-.1-.23-.16-.48-.29Z"/>
                    </svg>
                    <span>Kirim Bukti via WhatsApp</span>
                </a>
            @else
                <p class="agr-flow-warning">Kontak WhatsApp Admin belum dikonfigurasi di pengaturan sistem.</p>
            @endif

            <a href="{{ route('catalog') }}" class="agr-flow-secondary-btn">Kembali ke Katalog</a>
        </div>
    </div>
</div>
@endsection
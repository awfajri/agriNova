@extends('layouts.app')

@section('title', 'Pembayaran QRIS - Agrinova')
@section('body_class', 'page-checkout-payment')

@section('content')
@php
    $qrisImageUrl = asset('images/qris-agrinova.jpeg');
    $expiresAtTimestamp = $order->payment_expires_at
        ? $order->payment_expires_at->timestamp * 1000
        : now()->addMinutes(15)->timestamp * 1000;
    $formattedTotal = 'Rp' . number_format((float) $order->total, 0, ',', '.');
@endphp

<div class="agr-pay-page">
    <div class="agr-pay-container">
        {{-- Mobile Top Bar (Hanya tampil di layar < 768px) --}}
        <header class="agr-pay-mobile-topbar">
            <a href="{{ route('profile.show') }}" class="agr-pay-back" aria-label="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </a>
            <h1 class="agr-pay-title">Pembayaran QRIS</h1>
        </header>

        {{-- Stepper (1: Done, 2: Active, 3: Pending) --}}
        <nav class="agr-pay-stepper" aria-label="Tahapan pembayaran">
            <div class="agr-pay-step">
                <div class="agr-pay-step-circle is-done">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span class="agr-pay-step-label">Detail</span>
            </div>

            <div class="agr-pay-step-line is-done"></div>

            <div class="agr-pay-step">
                <div class="agr-pay-step-circle is-active">2</div>
                <span class="agr-pay-step-label is-active">Pembayaran</span>
            </div>

            <div class="agr-pay-step-line"></div>

            <div class="agr-pay-step">
                <div class="agr-pay-step-circle is-pending">3</div>
                <span class="agr-pay-step-label is-pending">Konfirmasi</span>
            </div>
        </nav>

        {{-- Ringkasan Total --}}
        <section class="agr-pay-total-box">
            <p class="agr-pay-total-caption">Total pembayaran</p>
            <p class="agr-pay-total-amount">{{ $formattedTotal }}</p>
            <p class="agr-pay-order-id">Pesanan <strong>#{{ $order->order_number }}</strong></p>
        </section>

        {{-- Countdown Timer --}}
        <section class="agr-pay-timer-card" aria-live="polite">
            <div class="agr-pay-timer-row">
                <span class="agr-pay-timer-label">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                    </svg>
                    Sisa waktu pembayaran
                </span>
                <span id="payment-countdown"
                      data-expires-at="{{ $expiresAtTimestamp }}"
                      class="agr-pay-timer-value">
                    15:00
                </span>
            </div>
            <p class="agr-pay-timer-desc">
                Selesaikan pembayaran sebelum waktu habis agar slot pesanan tidak hangus.
            </p>
        </section>

        {{-- Kartu QRIS --}}
        <section class="agr-pay-qris-card">
            <h2 class="agr-pay-qris-title">QRIS Agrinova</h2>
            <p class="agr-pay-qris-subtitle">Bayar dari e-wallet atau mobile banking</p>

            <div class="agr-pay-qris-frame">
                <img src="{{ $qrisImageUrl }}"
                     alt="Kode QRIS Resmi Agrinova"
                     class="agr-pay-qris-img">
            </div>

            <div>
                <a href="{{ $qrisImageUrl }}"
                   download="QRIS-Agrinova-{{ $order->order_number }}.png"
                   class="agr-pay-download-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                    </svg>
                    <span>Unduh Kode QRIS</span>
                </a>
            </div>

            <p class="agr-pay-qris-note">Gunakan QRIS resmi yang diberikan Agrinova.</p>
        </section>

        {{-- Panduan Pembayaran --}}
        <section>
            <h3 class="agr-pay-guide-title">Cara membayar</h3>
            <ol class="agr-pay-guide-list">
                <li>Tekan <strong>Unduh Kode QRIS</strong> untuk menyimpan gambar ke galeri HP.</li>
                <li>Buka aplikasi m-banking atau e-wallet, pilih <strong>Scan QR &rarr; Pilih dari Galeri</strong>.</li>
                <li>Pastikan nominal <strong>{{ $formattedTotal }}</strong> sesuai, selesaikan pembayaran, lalu simpan tangkapan layar buktinya.</li>
            </ol>
        </section>
    </div>

    {{-- Bottom Sticky CTA --}}
    <div class="agr-pay-bottom-bar">
        <div class="agr-pay-bottom-inner">
            <p class="agr-pay-bottom-hint">Sudah transfer? Lanjut kirim bukti bayar ke WhatsApp admin.</p>
            <a href="{{ route('order.success', $order->order_number) }}" class="agr-pay-submit-btn">
                Saya sudah bayar
            </a>
        </div>
    </div>
</div>
@endsection
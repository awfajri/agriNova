@extends('layouts.app')

@section('title', 'Detail Reservasi - Agrinova')
@section('body_class', 'page-mobile-subpage')

@section('content')
@php
    $defaultParticipants = (int) old('jumlah_peserta', 20);
    $initialTotal = $package['price'] * $defaultParticipants;
@endphp

<div class="agr-edu-page has-sticky-bottom">
    <div class="agr-edu-container">
        <header class="agr-edu-topbar">
            <a href="{{ route('eduwisata.show', $package['slug']) }}" class="agr-edu-back" aria-label="Kembali ke detail paket">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </a>
            <h1 class="agr-edu-topbar-title">Detail reservasi</h1>
        </header>

        {{-- Stepper (1: Active, 2: Pending, 3: Pending) --}}
        <nav class="agr-flow-stepper" aria-label="Tahapan reservasi">
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

        {{-- Kartu Ringkasan Paket Terpilih --}}
        <div class="agr-flow-items-card">
            <div class="agr-flow-product-row">
                <img src="{{ asset($package['image']) }}" alt="{{ $package['title'] }}" class="agr-flow-product-thumb">
                <div class="agr-flow-product-info">
                    <h2>{{ $package['title'] }}</h2>
                    <p>{{ $package['tag'] }}</p>
                    <strong>Rp{{ number_format($package['price'], 0, ',', '.') }} <small style="font-weight:500;">/ Peserta</small></strong>
                </div>
            </div>
        </div>

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

        <form method="POST" action="{{ route('eduwisata.reserve') }}" class="agr-flow-form">
            @csrf
            <input type="hidden" name="package_slug" value="{{ $package['slug'] }}">

            <h2 class="agr-flow-section-heading">Data pemesan</h2>

            <div class="agr-flow-field">
                <label for="nama_pic">Nama pemesan <span class="is-required">*</span></label>
                <input id="nama_pic" name="nama_pic" type="text" value="{{ old('nama_pic', auth()->user()->name ?? '') }}" placeholder="Contoh: Nadia Putri" required>
            </div>

            <div class="agr-flow-field">
                <label for="asal_rombongan">Asal sekolah / komunitas / instansi</label>
                <input id="asal_rombongan" name="asal_rombongan" type="text" value="{{ old('asal_rombongan') }}" placeholder="Contoh: SDN 01 Cikarang">
            </div>

            <div class="agr-flow-field">
                <label for="whatsapp">Nomor WhatsApp <span class="is-required">*</span></label>
                <input id="whatsapp" name="whatsapp" type="tel" value="{{ old('whatsapp', auth()->user()->phone ?? '') }}" placeholder="Contoh: 081234567890" required>
                <small>Gunakan nomor aktif agar admin bisa menghubungimu.</small>
            </div>

            <div class="agr-flow-field">
                <label for="tanggal_kunjungan">Rencana Kunjungan <span class="is-required">*</span></label>
                <input id="tanggal_kunjungan" name="tanggal_kunjungan" type="date" min="{{ now()->addDays(3)->format('Y-m-d') }}" value="{{ old('tanggal_kunjungan', now()->addDays(3)->format('Y-m-d')) }}" required>
            </div>

            <div class="agr-flow-field">
                <label>Sesi Waktu Kunjungan <span class="is-required">*</span></label>
                <div class="agr-session-grid">
                    <label class="agr-session-card">
                        <input type="radio" name="sesi_waktu" value="Sesi Pagi (08.30 - 11.30 WIB)" @checked(old('sesi_waktu', 'Sesi Pagi (08.30 - 11.30 WIB)') === 'Sesi Pagi (08.30 - 11.30 WIB)') required>
                        <div class="agr-session-top">
                            <strong>Sesi Pagi</strong>
                            <span class="agr-session-check">&#10003;</span>
                        </div>
                        <span>08.30 - 11.30 WIB</span>
                    </label>

                    <label class="agr-session-card">
                        <input type="radio" name="sesi_waktu" value="Sesi Siang (13.00 - 15.30 WIB)" @checked(old('sesi_waktu') === 'Sesi Siang (13.00 - 15.30 WIB)')>
                        <div class="agr-session-top">
                            <strong>Sesi Siang</strong>
                            <span class="agr-session-check">&#10003;</span>
                        </div>
                        <span>13.00 - 15.30 WIB</span>
                    </label>
                </div>
            </div>

            <div class="agr-flow-field">
                <label for="jumlah_peserta">Estimasi Jumlah Peserta <span class="is-required">*</span></label>
                <input id="jumlah_peserta"
                       name="jumlah_peserta"
                       type="number"
                       min="5"
                       max="200"
                       value="{{ $defaultParticipants }}"
                       data-unit-price="{{ $package['price'] }}"
                       required>
            </div>

            <div class="agr-flow-field">
                <label for="catatan">Catatan <span>(Opsional)</span></label>
                <textarea id="catatan" name="catatan" rows="3" placeholder="Contoh: Rombongan menggunakan 1 bus besar..">{{ old('catatan') }}</textarea>
            </div>

            {{-- Sticky Bottom Bar --}}
            <div class="agr-flow-bottom-bar">
                <div class="agr-flow-bottom-inner">
                    <div class="agr-flow-bottom-total">
                        <span>Total pembayaran</span>
                        <strong id="edu-total-display">Rp{{ number_format($initialTotal, 0, ',', '.') }}</strong>
                    </div>
                    <button type="submit" class="agr-flow-primary-btn">Lanjut ke Pembayaran</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
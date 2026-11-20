@extends('layouts.app')

@section('title', 'Pendaftaran Pelatihan - Agrinova')
@section('body_class', 'page-mobile-subpage')

@section('content')
@php
    $defaultParticipants = (int) old('jumlah_peserta', 1);
    $initialTotal = $kelas['harga'] * $defaultParticipants;
@endphp

<div class="agr-edu-page has-sticky-bottom">
    <div class="agr-edu-container">
        <header class="agr-edu-topbar">
            <a href="{{ route('pelatihan.show', $kelas['slug']) }}" class="agr-edu-back" aria-label="Kembali ke detail pelatihan">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </a>
            <h1 class="agr-edu-topbar-title">Detail pendaftaran</h1>
        </header>

        {{-- Stepper (1: Active, 2: Pending, 3: Pending) --}}
        <nav class="agr-flow-stepper" aria-label="Tahapan pendaftaran">
            <div class="agr-flow-step">
                <div class="agr-flow-step-circle is-active">1</div>
                <span class="agr-flow-step-label is-active">Detail</span>
            </div>
            <div class="agr-flow-step-line"></div>
            <div class="agr-flow-step">
                <div class="agr-flow-step-circle is-pending">2</div>
                <span class="agr-flow-step-label is-pending">Konfirmasi</span>
            </div>
            <div class="agr-flow-step-line"></div>
            <div class="agr-flow-step">
                <div class="agr-flow-step-circle is-pending">3</div>
                <span class="agr-flow-step-label is-pending">Selesai</span>
            </div>
        </nav>

        {{-- Kartu Ringkasan Pelatihan Terpilih --}}
        <div class="agr-flow-items-card">
            <div class="agr-flow-product-row">
                <img src="{{ asset($kelas['image']) }}" alt="{{ $kelas['judul'] }}" class="agr-flow-product-thumb">
                <div class="agr-flow-product-info">
                    <h2>{{ $kelas['judul'] }}</h2>
                    <p>{{ $kelas['jadwal'] }} &middot; {{ $kelas['durasi'] }}</p>
                    <strong>Rp{{ number_format($kelas['harga'], 0, ',', '.') }} <small style="font-weight:500;">/ Peserta</small></strong>
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

        <form method="POST" action="{{ route('pelatihan.register', $kelas['slug']) }}" class="agr-flow-form">
            @csrf
            <h2 class="agr-flow-section-heading">Data peserta</h2>

            <div class="agr-flow-field">
                <label for="nama">Nama lengkap <span class="is-required">*</span></label>
                <input id="nama" name="nama" type="text" value="{{ old('nama', auth()->user()->name ?? '') }}" placeholder="Nama lengkap peserta" required>
            </div>

            <div class="agr-flow-field">
                <label for="whatsapp">Nomor WhatsApp <span class="is-required">*</span></label>
                <input id="whatsapp" name="whatsapp" type="tel" value="{{ old('whatsapp', auth()->user()->phone ?? '') }}" placeholder="Contoh: 081234567890" required>
                <small>Gunakan nomor aktif agar admin bisa mengirim jadwal &amp; konfirmasi.</small>
            </div>

            <div class="agr-flow-field">
                <label for="email">Alamat Email <span>(Opsional)</span></label>
                <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="nama@email.com">
            </div>

            <div class="agr-flow-field">
                <label for="instansi">Instansi / Komunitas <span>(Opsional)</span></label>
                <input id="instansi" name="instansi" type="text" value="{{ old('instansi') }}" placeholder="Kosongkan jika mendaftar pribadi">
            </div>

            <div class="agr-flow-field">
                <label for="jumlah_peserta">Jumlah Peserta <span class="is-required">*</span></label>
                <input id="jumlah_peserta"
                       name="jumlah_peserta"
                       type="number"
                       min="1"
                       max="50"
                       value="{{ $defaultParticipants }}"
                       data-unit-price="{{ $kelas['harga'] }}"
                       required>
            </div>

            <div class="agr-flow-field">
                <label for="catatan">Catatan <span>(Opsional)</span></label>
                <textarea id="catatan" name="catatan" rows="3" placeholder="Pertanyaan atau kebutuhan khusus...">{{ old('catatan') }}</textarea>
            </div>

            {{-- Sticky Bottom Bar --}}
            <div class="agr-flow-bottom-bar">
                <div class="agr-flow-bottom-inner">
                    <div class="agr-flow-bottom-total">
                        <span>Estimasi biaya</span>
                        <strong id="edu-total-display">Rp{{ number_format($initialTotal, 0, ',', '.') }}</strong>
                    </div>
                    <button type="submit" class="agr-flow-primary-btn">Lanjut ke Konfirmasi</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
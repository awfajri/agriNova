@extends('layouts.app')

@section('title', $kelas['judul'] . ' - Pelatihan Agrinova')

@section('content')
<section class="product-detail-page">
    <div class="container">

        <nav class="breadcrumb-nav" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span>/</span>
            <a href="{{ route('pelatihan.index') }}">Pelatihan</a>
            <span>/</span>
            <span>{{ $kelas['judul'] }}</span>
        </nav>

        @if (session('success_pelatihan'))
            <div class="catalog-alert catalog-alert--success mb-4" role="alert">
                <strong>Pendaftaran Terkirim!</strong> {{ session('success_pelatihan') }}
            </div>
        @endif

        <div class="product-detail-grid">
            <div class="product-detail-media product-detail-media--placeholder">
                <span class="product-badge {{ $kelas['is_available'] ? 'product-badge--preorder' : '' }}">
                    {{ $kelas['status'] }}
                </span>
                <span>Foto Kegiatan Pelatihan Menyusul</span>
            </div>

            <div class="product-detail-copy">
                <span class="eyebrow">{{ $kelas['kategori'] }}</span>
                <h1>{{ $kelas['judul'] }}</h1>
                <div class="product-detail-price">
                    Rp {{ number_format($kelas['harga'], 0, ',', '.') }}
                    <small class="fs-6 fw-normal text-muted">/ peserta</small>
                </div>

                <p class="product-detail-description">{{ $kelas['deskripsi'] }}</p>

                <div class="product-detail-status">
                    <span class="d-inline-flex align-items-center gap-2">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                        {{ $kelas['jadwal'] }}
                    </span>
                    <span class="d-inline-flex align-items-center gap-2">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        {{ $kelas['durasi'] }}
                    </span>
                    <span class="{{ !$kelas['is_available'] ? 'is-unavailable' : '' }}">
                        {{ $kelas['kuota'] }}
                    </span>
                </div>

                <div class="p-3 rounded-4 mb-4" style="background: var(--c-white); border: 1px solid rgba(18, 84, 79, 0.12);">
                    <strong class="d-block mb-2 small text-uppercase" style="color: var(--c-green); letter-spacing: 0.06em;">Fokus Materi:</strong>
                    <div class="d-grid gap-2" style="font-size: 0.92rem; color: rgba(9, 35, 40, 0.82);">
                        @foreach ($kelas['materi'] as $materi)
                            <div class="d-flex align-items-start gap-2">
                                <svg class="mt-1 shrink-0" style="color: var(--c-green);" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>{{ $materi }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <a href="#form-pendaftaran" class="btn-pill btn-pill--solid">
                    Daftar Kelas Ini
                    <span class="btn-pill-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                    </span>
                </a>
            </div>
        </div>

        {{-- Formulir Pendaftaran Kelas --}}
        <div id="form-pendaftaran" class="mt-5 pt-4">
            <div class="checkout-layout">
                <form action="{{ route('pelatihan.register', $kelas['slug']) }}" method="POST" class="checkout-form">
                    @csrf
                    <div class="checkout-step">
                        <span class="checkout-step-number">1</span>
                        <div>
                            <h2>Formulir Pendaftaran Peserta</h2>
                            <p>Isi data singkat berikut. Konfirmasi kuota akan dikirim via WhatsApp.</p>
                        </div>
                    </div>

                    <div class="checkout-fields">
                        <div class="checkout-field">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" placeholder="Nama lengkap peserta" required value="{{ old('nama') }}">
                        </div>

                        <div class="checkout-field">
                            <label for="whatsapp">Nomor WhatsApp</label>
                            <input type="tel" id="whatsapp" name="whatsapp" placeholder="0812xxxxxxxx" required value="{{ old('whatsapp') }}">
                        </div>

                        <div class="checkout-field">
                            <label for="email">Alamat Email</label>
                            <input type="email" id="email" name="email" placeholder="nama@email.com" required value="{{ old('email') }}">
                        </div>

                        <div class="checkout-field">
                            <label for="instansi">Instansi / Komunitas <span>(Opsional)</span></label>
                            <input type="text" id="instansi" name="instansi" placeholder="Kosongkan jika pribadi" value="{{ old('instansi') }}">
                        </div>

                        <div class="checkout-field">
                            <label for="jumlah_peserta">Jumlah Peserta</label>
                            <input type="number" id="jumlah_peserta" name="jumlah_peserta" min="1" max="50" value="{{ old('jumlah_peserta', 1) }}" required>
                        </div>

                        <div class="checkout-field checkout-field--full">
                            <label for="catatan">Catatan Tambahan <span>(Opsional)</span></label>
                            <textarea id="catatan" name="catatan" rows="2" placeholder="Pertanyaan atau kebutuhan khusus...">{{ old('catatan') }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="commerce-checkout-button checkout-submit" @disabled(!$kelas['is_available'])>
                        {{ $kelas['is_available'] ? 'Kirim Pendaftaran' : 'Kuota Kelas Sudah Penuh' }}
                    </button>
                    <a href="{{ route('pelatihan.index') }}" class="checkout-back">← Kembali ke Daftar Kelas</a>
                </form>

                <aside class="commerce-summary">
                    <h2>Ringkasan Kelas</h2>
                    <div class="commerce-summary-row">
                        <span>Program</span>
                        <strong class="text-end">{{ $kelas['judul'] }}</strong>
                    </div>
                    <div class="commerce-summary-row">
                        <span>Jadwal</span>
                        <span class="text-end">{{ $kelas['jadwal'] }}</span>
                    </div>
                    <div class="commerce-summary-row">
                        <span>Durasi</span>
                        <span>{{ $kelas['durasi'] }}</span>
                    </div>
                    <div class="commerce-summary-total">
                        <span>Biaya</span>
                        <span>Rp {{ number_format($kelas['harga'], 0, ',', '.') }} / org</span>
                    </div>
                    <p class="commerce-summary-note">
                        Sudah termasuk modul materi, konsumsi, sertifikat, dan sesi praktik di kebun Agrinova.
                    </p>
                </aside>
            </div>
        </div>

    </div>
</section>
@endsection
@extends('layouts.app')

@section('title', $kelas['judul'] . ' - Pelatihan Agrinova')
@section('meta_description', 'Daftar kelas ' . $kelas['judul'] . ' di Agrinova Farm. ' . Str::limit($kelas['deskripsi'], 120))

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

        <div class="product-detail-grid pelatihan-detail-grid">
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

                <div class="product-detail-status pelatihan-detail-status">
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

                <div class="pelatihan-materi-box">
                    <strong class="pelatihan-materi-label">Fokus Materi:</strong>
                    <div class="pelatihan-materi-list">
                        @foreach ($kelas['materi'] as $materi)
                            <div class="pelatihan-materi-item">
                                <svg class="pelatihan-materi-check" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>{{ $materi }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <a href="#form-pendaftaran" class="btn-pill btn-pill--solid pelatihan-cta-btn">
                    Daftar Kelas Ini
                    <span class="btn-pill-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                    </span>
                </a>
            </div>
        </div>

        {{-- Formulir Pendaftaran Kelas --}}
        <div id="form-pendaftaran" class="pelatihan-form-section">
            <div class="checkout-layout pelatihan-form-layout">
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

                    <button type="submit" class="commerce-checkout-button checkout-submit pelatihan-submit-btn" @disabled(!$kelas['is_available'])>
                        {{ $kelas['is_available'] ? 'Kirim Pendaftaran' : 'Kuota Kelas Sudah Penuh' }}
                    </button>
                    <a href="{{ route('pelatihan.index') }}" class="checkout-back">← Kembali ke Daftar Kelas</a>
                </form>

                <aside class="commerce-summary pelatihan-summary">
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

<style>
    /* ===== Pelatihan Show: mobile-first scoped styles ===== */

    /* -- Detail grid alignment -- */
    .pelatihan-detail-grid {
        align-items: start;
    }

    /* -- Status pills: bigger touch targets -- */
    .pelatihan-detail-status span {
        min-height: 2.25rem;
        padding: 0.45rem 0.7rem;
    }

    /* -- Materi box -- */
    .pelatihan-materi-box {
        padding: 1rem 1.15rem;
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        background: var(--c-white);
        border: 1px solid rgba(18, 84, 79, 0.12);
    }
    .pelatihan-materi-label {
        display: block;
        margin-bottom: 0.65rem;
        font-size: 0.78rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--c-green);
    }
    .pelatihan-materi-list {
        display: grid;
        gap: 0.55rem;
        font-size: 0.92rem;
        color: rgba(9, 35, 40, 0.82);
    }
    .pelatihan-materi-item {
        display: flex;
        align-items: flex-start;
        gap: 0.55rem;
    }
    .pelatihan-materi-check {
        flex-shrink: 0;
        margin-top: 0.15rem;
        color: var(--c-green);
    }

    /* -- CTA button -- */
    .pelatihan-cta-btn {
        width: 100%;
        justify-content: center;
    }

    /* -- Form section -- */
    .pelatihan-form-section {
        margin-top: 3.5rem;
        padding-top: 2.5rem;
    }

    /* -- Form inputs: touch-friendly -- */
    .checkout-field input,
    .checkout-field textarea {
        font-size: 16px; /* Prevents iOS zoom */
    }

    /* -- Submit button touch target -- */
    .pelatihan-submit-btn {
        min-height: 3rem;
        font-size: 1rem;
    }

    /* ===== Tablet ≤ 991px: stack form/summary ===== */
    @media (max-width: 991.98px) {
        .pelatihan-form-layout {
            grid-template-columns: 1fr;
        }
        .pelatihan-summary {
            position: static;
            order: -1;
        }
    }

    /* ===== Phone ≤ 767px: stack detail grid ===== */
    @media (max-width: 767.98px) {
        .pelatihan-detail-grid {
            grid-template-columns: 1fr;
            gap: 1.75rem;
        }
        .pelatihan-detail-grid .product-detail-media,
        .pelatihan-detail-grid .product-detail-media img {
            min-height: 16rem;
            border-radius: 1.25rem;
        }
        .product-detail-copy h1 {
            font-size: clamp(1.65rem, 5vw, 2.5rem);
            margin-top: 0.3rem;
        }
        .product-detail-price {
            font-size: 1.25rem;
        }
        .pelatihan-form-section {
            margin-top: 2.5rem;
            padding-top: 1.75rem;
        }
    }

    /* ===== Small phone ≤ 576px ===== */
    @media (max-width: 575.98px) {
        .breadcrumb-nav {
            font-size: 0.82rem;
            gap: 0.35rem;
        }

        .product-detail-copy h1 {
            font-size: 1.5rem;
            line-height: 1.12;
        }
        .product-detail-price {
            font-size: 1.15rem;
            margin-bottom: 0.85rem;
        }
        .product-detail-description {
            font-size: 0.92rem;
            line-height: 1.6;
        }

        .pelatihan-detail-status {
            gap: 0.4rem;
        }
        .pelatihan-detail-status span {
            font-size: 0.75rem;
            padding: 0.4rem 0.6rem;
        }

        .pelatihan-materi-box {
            padding: 0.85rem;
            border-radius: 0.85rem;
        }
        .pelatihan-materi-list {
            font-size: 0.86rem;
            gap: 0.45rem;
        }
        .pelatihan-materi-check {
            width: 14px;
            height: 14px;
        }

        .pelatihan-cta-btn {
            font-size: 0.95rem;
            padding: 0.45rem 0.45rem 0.45rem 1.25rem;
        }
        .pelatihan-cta-btn .btn-pill-icon {
            width: 2.4rem;
            height: 2.4rem;
        }

        .checkout-form {
            padding: 1rem;
            border-radius: 1rem;
        }
        .checkout-step h2 {
            font-size: 1.05rem;
        }
        .checkout-step p {
            font-size: 0.8rem;
        }

        .pelatihan-submit-btn {
            min-height: 2.85rem;
            font-size: 0.92rem;
        }

        .checkout-back {
            font-size: 0.85rem;
            min-height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pelatihan-summary {
            padding: 1rem;
            border-radius: 1rem;
        }
        .pelatihan-summary h2 {
            font-size: 1.08rem;
            margin-bottom: 0.85rem;
        }
        .commerce-summary-row,
        .commerce-summary-total {
            font-size: 0.88rem;
        }
        .commerce-summary-note {
            font-size: 0.78rem;
        }

        .catalog-alert {
            font-size: 0.9rem;
            padding: 0.75rem 0.9rem;
        }
    }

    /* ===== Tiny phone ≤ 375px ===== */
    @media (max-width: 375px) {
        .product-detail-copy h1 {
            font-size: 1.3rem;
        }
        .product-detail-price {
            font-size: 1.05rem;
        }
        .pelatihan-materi-list {
            font-size: 0.82rem;
        }
        .pelatihan-detail-status span {
            font-size: 0.7rem;
            padding: 0.35rem 0.5rem;
        }
    }
</style>
@endsection
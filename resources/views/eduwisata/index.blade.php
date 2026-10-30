@extends('layouts.app')

@section('title', 'Eduwisata & Reservasi Kunjungan - Agrinova Farm')
@section('meta_description', 'Kunjungan edukatif ke kebun melon dan green house Agrinova untuk sekolah, kampus, komunitas, dan keluarga.')

@section('content')
@php
    $paketEduwisata = [
        [
            'id' => 'Paket Edukasi Pelajar',
            'tag' => 'Sekolah & Kampus',
            'judul' => 'Edukasi Pelajar',
            'harga' => 'Rp 35.000',
            'satuan' => '/ siswa (Min. 20 org)',
            'deskripsi' => 'Tur terpandu mengenal siklus hidroponik melon dan anatomi tanaman bersama agronomi.',
            'chips' => ['Pemandu Kebun', 'Lembar Materi', 'Welcome Drink'],
        ],
        [
            'id' => 'Paket Wisata Petik Melon',
            'tag' => 'Keluarga & Umum',
            'judul' => 'Wisata Petik Melon',
            'harga' => 'Rp 50.000',
            'satuan' => '/ orang (Min. 5 org)',
            'deskripsi' => 'Sensasi memilih dan memetik melon premium langsung dari pohon di dalam Green House.',
            'chips' => ['Tiket Green House', 'Voucher Buah', 'Alat Petik'],
        ],
        [
            'id' => 'Paket Studi Banding Agribisnis',
            'tag' => 'Komunitas & Instansi',
            'judul' => 'Studi Banding Tani',
            'harga' => 'Rp 85.000',
            'satuan' => '/ peserta (Min. 15 org)',
            'deskripsi' => 'Bedah operasional kebun, sistem fertigasi otomatis, dan diskusi bisnis agrowisata.',
            'chips' => ['Diskusi Manajemen', 'Tur Teknis Irigasi', 'Sajian Melon'],
        ],
    ];

    $fasilitasList = [
        ['no' => '01', 'nama' => 'Green House Modern', 'ket' => 'Area budidaya melon bersih, teduh, dan bebas lumpur.'],
        ['no' => '02', 'nama' => 'Aula Edukasi Terbuka', 'ket' => 'Ruang kumpul nyaman untuk materi dan istirahat rombongan.'],
        ['no' => '03', 'nama' => 'Instalasi Hidroponik', 'ket' => 'Area praktik menanam dan memanen sayur bebas pestisida.'],
        ['no' => '04', 'nama' => 'Parkir Bus & Musala', 'ket' => 'Lahan parkir bus pariwisata luas, toilet bersih, dan musala.'],
    ];
@endphp

<style>
    .edu-bento-right {
        grid-column: span 7 !important;
        grid-row: span 2 !important;
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        grid-template-rows: repeat(2, 1fr) !important;
        gap: 1rem !important;
    }

    .edu-bento-right .bento-card {
        grid-column: auto !important;
        grid-row: auto !important;
        min-height: 10rem !important;
        height: 100% !important;
        margin: 0 !important;
    }

    @media (max-width: 991.98px) {
        .edu-bento-right {
            grid-column: span 12 !important;
            grid-row: auto !important;
            grid-template-rows: auto !important;
        }
    }
    @media (max-width: 575.98px) {
        .edu-bento-right {
            grid-template-columns: 1fr !important;
        }
    }

    /* Interactive Option Cards - Kontras Tinggi */
    .agr-choice-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(165px, 1fr));
        gap: 0.75rem;
    }
    .agr-choice-card {
        position: relative;
        display: flex !important;
        flex-direction: column;
        justify-content: center;
        gap: 0.3rem;
        padding: 0.9rem 1.05rem;
        border: 1.5px solid rgba(18, 84, 79, 0.22);
        border-radius: 0.9rem;
        background: var(--c-white);
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 0 !important;
    }
    .agr-choice-card:hover {
        border-color: var(--c-deep);
        background: var(--c-paper);
        transform: translateY(-1px);
    }
    .agr-choice-card input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    /* Tampilan tidak dipilih: teks gelap tajam */
    .agr-choice-title {
        font-weight: 700 !important;
        font-size: 0.95rem;
        color: var(--c-ink) !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }
    .agr-choice-sub {
        font-size: 0.82rem;
        font-weight: 500 !important;
        color: rgba(9, 35, 40, 0.78) !important;
    }
    .agr-choice-dot {
        width: 1.15rem;
        height: 1.15rem;
        flex-shrink: 0;
        border-radius: 50%;
        border: 2px solid rgba(18, 84, 79, 0.4);
        background: var(--c-white);
        display: inline-grid;
        place-items: center;
        transition: all 0.2s ease;
    }
    /* Tampilan KETIKA DIPILIH: Background Hijau Tua Solid & Teks Putih Kontras */
    .agr-choice-card:has(input:checked) {
        background: var(--c-deep) !important;
        border-color: var(--c-deep) !important;
        box-shadow: 0 8px 20px rgba(18, 84, 79, 0.22);
    }
    .agr-choice-card:has(input:checked) .agr-choice-title {
        color: var(--c-white) !important;
    }
    .agr-choice-card:has(input:checked) .agr-choice-sub {
        color: rgba(255, 255, 255, 0.88) !important;
    }
    .agr-choice-card:has(input:checked) .agr-choice-dot {
        border-color: var(--c-white);
        background: var(--c-sage);
    }
    .agr-choice-card:has(input:checked) .agr-choice-dot::after {
        content: "";
        width: 0.42rem;
        height: 0.42rem;
        border-radius: 50%;
        background: var(--c-ink);
    }

    /* Custom Visual Datepicker Agrinova */
    .agr-date-wrap {
        position: relative;
    }
    .agr-date-trigger {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0.95rem;
        border: 1px solid rgba(18, 84, 79, 0.18);
        border-radius: 0.65rem;
        background: var(--c-paper);
        color: var(--c-ink);
        font-weight: 500;
        cursor: pointer;
        text-align: left;
        transition: border-color 0.2s ease, background 0.2s ease;
    }
    .agr-date-trigger:hover,
    .agr-date-trigger.is-open {
        border-color: var(--c-deep);
        background: var(--c-white);
    }
    .agr-calendar-pop {
        position: absolute;
        top: calc(100% + 0.5rem);
        left: 0;
        z-index: 60;
        width: 19.5rem;
        padding: 1rem;
        border-radius: 1.15rem;
        background: var(--c-white);
        border: 1px solid rgba(18, 84, 79, 0.14);
        box-shadow: 0 18px 40px rgba(9, 35, 40, 0.16);
    }
    .agr-cal-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }
    .agr-cal-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--c-deep);
    }
    .agr-cal-nav {
        display: flex;
        gap: 0.35rem;
    }
    .agr-cal-btn {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        border: 1px solid rgba(18, 84, 79, 0.15);
        background: var(--c-paper);
        color: var(--c-deep);
        display: grid;
        place-items: center;
        cursor: pointer;
    }
    .agr-cal-btn:hover {
        background: var(--c-deep);
        color: var(--c-white);
    }
    .agr-cal-weekdays,
    .agr-cal-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        gap: 0.2rem;
    }
    .agr-cal-weekdays span {
        font-size: 0.72rem;
        font-weight: 700;
        color: rgba(9, 35, 40, 0.5);
        padding-bottom: 0.35rem;
    }
    .agr-cal-day {
        aspect-ratio: 1;
        border: 0;
        border-radius: 0.55rem;
        background: transparent;
        font-size: 0.84rem;
        font-weight: 600;
        color: var(--c-ink);
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .agr-cal-day:hover:not(:disabled) {
        background: rgba(139, 187, 146, 0.35);
        color: var(--c-deep);
    }
    .agr-cal-day.is-selected {
        background: var(--c-deep) !important;
        color: var(--c-white) !important;
    }
    .agr-cal-day.is-today {
        border: 1.5px solid var(--c-green);
    }
    .agr-cal-day:disabled {
        opacity: 0.28;
        cursor: not-allowed;
    }
</style>

<section class="catalog-page">
    <div class="container catalog-shell">

        {{-- Hero Eduwisata --}}
        <div class="catalog-hero">
            <div>
                <h1 class="text-white mt-2 mb-2" style="font-size: clamp(1.9rem, 4.2vw, 3.2rem); line-height: 1.08;">
                    Eduwisata Kebun &amp; Green House
                </h1>
                <p class="mb-4" style="color: rgba(255, 255, 255, 0.82); max-width: 35rem; font-size: 1rem;">
                    Pengalaman interaktif mengenal hidroponik modern dan memetik melon segar langsung dari kebun untuk sekolah, komunitas, maupun keluarga.
                </p>
                <a href="#form-reservasi" class="btn-pill btn-pill--light">
                    Reservasi Kunjungan
                    <span class="btn-pill-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                    </span>
                </a>
            </div>

            <div class="catalog-hero-note">
                <span class="catalog-hero-note-icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                </span>
                <strong>Selasa – Minggu (08.00 – 16.00)</strong>
                <span>Wajib reservasi minimal H-3 sebelum jadwal kedatangan rombongan.</span>
            </div>
        </div>

        {{-- Paket Kunjungan Eduwisata (Teks Ringkas + Chip Badges) --}}
        <div class="mt-2">
            <div class="catalog-results-head">
                <div>
                    <h2>Paket Kunjungan Eduwisata</h2>
                </div>
            </div>

            <div class="catalog-product-grid">
                @foreach ($paketEduwisata as $paket)
                    <article class="catalog-product-card">
                        <div class="catalog-product-media catalog-product-media--placeholder">
                            <span class="product-badge product-badge--preorder">{{ $paket['tag'] }}</span>
                            <span>Foto Eduwisata Menyusul</span>
                        </div>

                        <div class="catalog-product-body">
                            <h3>{{ $paket['judul'] }}</h3>
                            <p style="min-height: auto;">{{ $paket['deskripsi'] }}</p>

                            {{-- Chip Fasilitas Singkat (Ramah Mobile) --}}
                            <div class="d-flex flex-wrap gap-1 my-2">
                                @foreach ($paket['chips'] as $chip)
                                    <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill" style="background: var(--c-paper); color: var(--c-deep); font-size: 0.74rem; font-weight: 600;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        {{ $chip }}
                                    </span>
                                @endforeach
                            </div>

                            <div class="catalog-product-price pt-2 border-top">
                                <strong>{{ $paket['harga'] }}</strong>
                                <span>{{ $paket['satuan'] }}</span>
                            </div>
                        </div>

                        <div class="catalog-card-actions mt-auto">
                            <a href="#form-reservasi" class="catalog-buy-button" data-select-paket="{{ $paket['id'] }}">
                                Pilih Paket Ini
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        {{-- Info Fasilitas Kebun (Bento Layout Seimbang 5 + 7 Kolom) --}}
        <div class="mt-3">
            <div class="catalog-results-head">
                <div>
                    <h2>Fasilitas Area Eduwisata</h2>
                </div>
            </div>

            <div class="bento">
                <div class="bento-card bento-a" style="min-height: 21rem;">
                    <span class="bento-photo">Foto Area Green House Menyusul</span>
                    <div class="bento-a-box">
                        <span class="eyebrow">Lingkungan Nyaman</span>
                        <h3 class="mt-2 mb-1 fs-4">Dirancang Ramah Rombongan</h3>
                        <p class="small mb-0">Jalur kunjungan tertata rapi, bersih dari lumpur, dan didampingi pemandu kebun.</p>
                    </div>
                </div>

                <div class="edu-bento-right">
                    @foreach ($fasilitasList as $idx => $f)
                        <div class="bento-card {{ $idx % 2 === 0 ? 'bento-b' : 'bento-c' }} d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="bento-label mb-0">Fasilitas {{ $f['no'] }}</span>
                                <span class="d-inline-grid rounded-circle" style="width: 2rem; height: 2rem; place-items: center; background: rgba(18, 84, 79, 0.1); color: var(--c-deep);">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                </span>
                            </div>
                            <div>
                                <h3 class="fs-5 mb-1">{{ $f['nama'] }}</h3>
                                <p class="small mb-0">{{ $f['ket'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Formulir Reservasi Kunjungan --}}
        <div id="form-reservasi" class="mt-3 pt-2">
            @if (session('success_eduwisata'))
                <div class="catalog-alert catalog-alert--success mb-4" role="alert">
                    <strong>Reservasi Terkirim!</strong> {{ session('success_eduwisata') }}
                </div>
            @endif

            <div class="checkout-layout">
                <form action="{{ route('eduwisata.reserve') }}" method="POST" class="checkout-form">
                    @csrf
                    <div class="checkout-step">
                        <span class="checkout-step-number">1</span>
                        <div>
                            <h2>Formulir Reservasi Kunjungan</h2>
                            <p>Pilih paket, jadwal, dan jumlah peserta. Admin akan mengonfirmasi kuota via WhatsApp.</p>
                        </div>
                    </div>

                    <div class="checkout-fields">
                        <div class="checkout-field">
                            <label for="nama_pic">Nama Penanggung Jawab (PIC)</label>
                            <input type="text" id="nama_pic" name="nama_pic" placeholder="Contoh: Ibu Siti Aminah" required value="{{ old('nama_pic') }}">
                        </div>

                        <div class="checkout-field">
                            <label for="asal_rombongan">Sekolah / Komunitas / Instansi</label>
                            <input type="text" id="asal_rombongan" name="asal_rombongan" placeholder="Contoh: SDN 01 Cikarang" required value="{{ old('asal_rombongan') }}">
                        </div>

                        {{-- Pilihan Paket Interaktif --}}
                        <div class="checkout-field checkout-field--full">
                            <label>Pilih Paket Eduwisata</label>
                            <div class="agr-choice-grid">
                                @foreach ($paketEduwisata as $idx => $p)
                                    <label class="agr-choice-card">
                                        <input type="radio" name="kategori_kunjungan" value="{{ $p['id'] }}" {{ old('kategori_kunjungan', $paketEduwisata[0]['id']) === $p['id'] ? 'checked' : '' }} required>
                                        <div class="agr-choice-title">
                                            <div>{{ $p['judul'] }}</div>
                                            <div class="agr-choice-dot"></div>
                                        </div>
                                        <div class="agr-choice-sub">{{ $p['harga'] }} {{ $p['satuan'] }}</div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Custom Visual Datepicker (Tanpa Kalender Kaku Browser) --}}
                        <div class="checkout-field">
                            <label for="tanggal_kunjungan">Rencana Tanggal Kunjungan</label>
                            <div class="agr-date-wrap" id="agrDateWrap">
                                <input type="hidden" id="tanggal_kunjungan" name="tanggal_kunjungan" value="{{ old('tanggal_kunjungan') }}" required>
                                <button type="button" class="agr-date-trigger" id="agrDateTrigger" aria-expanded="false">
                                    <span id="agrDateLabel">Pilih tanggal kunjungan...</span>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--c-green);"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                </button>

                                <div class="agr-calendar-pop" id="agrCalendarPop" hidden>
                                    <div class="agr-cal-head">
                                        <span class="agr-cal-title" id="agrCalMonthYear">Oktober 2026</span>
                                        <div class="agr-cal-nav">
                                            <button type="button" class="agr-cal-btn" id="agrCalPrev" aria-label="Bulan sebelumnya">‹</button>
                                            <button type="button" class="agr-cal-btn" id="agrCalNext" aria-label="Bulan berikutnya">›</button>
                                        </div>
                                    </div>
                                    <div class="agr-cal-weekdays">
                                        <span>Min</span><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span>
                                    </div>
                                    <div class="agr-cal-days" id="agrCalDays"></div>
                                </div>
                            </div>
                        </div>

                        <div class="checkout-field">
                            <label for="whatsapp">Nomor WhatsApp PIC</label>
                            <input type="tel" id="whatsapp" name="whatsapp" placeholder="0812xxxxxxxx" required value="{{ old('whatsapp') }}">
                        </div>

                        {{-- Pilihan Sesi Waktu Interaktif --}}
                        <div class="checkout-field">
                            <label>Sesi Waktu Kunjungan</label>
                            <div class="agr-choice-grid">
                                <label class="agr-choice-card">
                                    <input type="radio" name="sesi_waktu" value="Sesi Pagi (08.30 - 11.30 WIB)" checked required>
                                    <div class="agr-choice-title">
                                        <div>Sesi Pagi</div>
                                        <div class="agr-choice-dot"></div>
                                    </div>
                                    <div class="agr-choice-sub">08.30 – 11.30 WIB</div>
                                </label>
                                <label class="agr-choice-card">
                                    <input type="radio" name="sesi_waktu" value="Sesi Siang (13.00 - 15.30 WIB)">
                                    <div class="agr-choice-title">
                                        <div>Sesi Siang</div>
                                        <div class="agr-choice-dot"></div>
                                    </div>
                                    <div class="agr-choice-sub">13.00 – 15.30 WIB</div>
                                </label>
                            </div>
                        </div>

                        <div class="checkout-field">
                            <label for="jumlah_peserta">Estimasi Jumlah Peserta</label>
                            <input type="number" id="jumlah_peserta" name="jumlah_peserta" min="5" max="200" required value="{{ old('jumlah_peserta', 20) }}">
                            <small>Min. 5 org (keluarga) atau 15–20 org (rombongan).</small>
                        </div>

                        <div class="checkout-field checkout-field--full">
                            <label for="catatan">Catatan Tambahan <span>(Opsional)</span></label>
                            <textarea id="catatan" name="catatan" rows="2" placeholder="Contoh: Rombongan menggunakan 1 bus besar...">{{ old('catatan') }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="commerce-checkout-button checkout-submit">
                        Ajukan Reservasi Kunjungan
                    </button>
                </form>

                <aside class="commerce-summary">
                    <h2>Alur Reservasi</h2>
                    <div class="commerce-summary-row">
                        <span>1. Isi Formulir</span>
                        <span class="text-end">Pilih paket &amp; jadwal</span>
                    </div>
                    <div class="commerce-summary-row">
                        <span>2. Cek Kuota</span>
                        <span class="text-end">Verifikasi admin</span>
                    </div>
                    <div class="commerce-summary-row">
                        <span>3. Konfirmasi WA</span>
                        <span class="text-end">Pengiriman jadwal</span>
                    </div>
                    <div class="commerce-summary-total">
                        <span>Respon Admin</span>
                        <span>Maks. 1x24 Jam</span>
                    </div>
                    <p class="commerce-summary-note">
                        Ketersediaan sesi petik melon menyesuaikan jadwal blok Green House yang siap panen.
                    </p>
                </aside>
            </div>
        </div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Auto-select Paket saat tombol "Pilih Paket Ini" di kartu atas diklik
    document.querySelectorAll('[data-select-paket]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const val = btn.getAttribute('data-select-paket');
            const radio = document.querySelector(`input[name="kategori_kunjungan"][value="${val}"]`);
            if (radio) radio.checked = true;
        });
    });

    // Custom Visual Calendar Agrinova
    const wrap = document.getElementById('agrDateWrap');
    const trigger = document.getElementById('agrDateTrigger');
    const label = document.getElementById('agrDateLabel');
    const hiddenInput = document.getElementById('tanggal_kunjungan');
    const pop = document.getElementById('agrCalendarPop');
    const monthYearEl = document.getElementById('agrCalMonthYear');
    const daysGrid = document.getElementById('agrCalDays');
    const prevBtn = document.getElementById('agrCalPrev');
    const nextBtn = document.getElementById('agrCalNext');

    const namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();
    let selectedDate = hiddenInput.value ? new Date(hiddenInput.value) : null;

    function formatValue(d) {
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${day}`;
    }

    function formatDisplay(d) {
        return `${d.getDate()} ${namaBulan[d.getMonth()]} ${d.getFullYear()}`;
    }

    if (selectedDate && !isNaN(selectedDate)) {
        label.textContent = formatDisplay(selectedDate);
    } else {
        // Default H+3
        const def = new Date(today);
        def.setDate(def.getDate() + 3);
        selectedDate = def;
        hiddenInput.value = formatValue(def);
        label.textContent = formatDisplay(def);
        currentMonth = def.getMonth();
        currentYear = def.getFullYear();
    }

    function renderCalendar() {
        monthYearEl.textContent = `${namaBulan[currentMonth]} ${currentYear}`;
        daysGrid.innerHTML = '';

        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

        for (let i = 0; i < firstDay; i++) {
            const empty = document.createElement('span');
            daysGrid.appendChild(empty);
        }

        for (let d = 1; d <= daysInMonth; d++) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'agr-cal-day';
            btn.textContent = d;

            const cellDate = new Date(currentYear, currentMonth, d);
            if (cellDate < today) {
                btn.disabled = true;
            }
            if (cellDate.getTime() === today.getTime()) {
                btn.classList.add('is-today');
            }
            if (selectedDate && cellDate.getTime() === selectedDate.getTime()) {
                btn.classList.add('is-selected');
            }

            btn.addEventListener('click', function () {
                selectedDate = cellDate;
                hiddenInput.value = formatValue(cellDate);
                label.textContent = formatDisplay(cellDate);
                pop.hidden = true;
                trigger.classList.remove('is-open');
                renderCalendar();
            });

            daysGrid.appendChild(btn);
        }
    }

    trigger.addEventListener('click', function () {
        const isHidden = pop.hidden;
        pop.hidden = !isHidden;
        trigger.classList.toggle('is-open', isHidden);
        if (isHidden) renderCalendar();
    });

    prevBtn.addEventListener('click', function () {
        currentMonth--;
        if (currentMonth < 0) { currentMonth = 11; currentYear--; }
        renderCalendar();
    });

    nextBtn.addEventListener('click', function () {
        currentMonth++;
        if (currentMonth > 11) { currentMonth = 0; currentYear++; }
        renderCalendar();
    });

    document.addEventListener('click', function (e) {
        if (!wrap.contains(e.target)) {
            pop.hidden = true;
            trigger.classList.remove('is-open');
        }
    });
});
</script>
@endsection
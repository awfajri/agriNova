@extends('layouts.app')

@section('title', 'Daftar Kelas Pelatihan - Agrinova Farm')
@section('meta_description', 'Program pelatihan budidaya melon, sayur hidroponik, dan pembekalan usaha tani masa pensiun di Agrinova Farm.')

@section('content')
<section class="catalog-page">
    <div class="container catalog-shell">

        {{-- Hero Banner Pelatihan --}}
        <div class="catalog-hero">
            <div>
                <span class="eyebrow eyebrow--light">Edukasi Agribisnis</span>
                <h1 class="text-white mt-2 mb-2" style="font-size: clamp(1.9rem, 4.2vw, 3.2rem); line-height: 1.08;">
                    Pelatihan &amp; Pembekalan Tani
                </h1>
                <p class="mb-0" style="color: rgba(255, 255, 255, 0.82); max-width: 34rem; font-size: 1rem;">
                    Praktik langsung di kebun Agrinova: dari budidaya melon Green House hingga wirausaha masa purnabakti.
                </p>
            </div>

            <div class="catalog-hero-note">
                <span class="catalog-hero-note-icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                </span>
                <strong>Bersertifikat &amp; Aplikatif</strong>
                <span>Termasuk modul cetak, starter kit bibit, dan pendampingan.</span>
            </div>
        </div>

        <style>
            .pelatihan-filter-row {
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                align-items: center;
                gap: 0.6rem;
                overflow-x: auto;
                width: 100%;
                padding-bottom: 0.35rem;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }
            .pelatihan-filter-row::-webkit-scrollbar {
                display: none;
            }
            .pelatihan-filter-row .catalog-chip {
                flex: 0 0 auto;
                white-space: nowrap;
            }
        </style>

        {{-- Filter Kategori Kelas --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="pelatihan-filter-row">
                <a href="{{ route('pelatihan.index') }}" class="catalog-chip {{ !$kategoriAktif ? 'active' : '' }}">
                    Semua Kelas
                </a>
                <a href="{{ route('pelatihan.index', ['kategori' => 'Budidaya Tanaman']) }}" class="catalog-chip {{ $kategoriAktif === 'Budidaya Tanaman' ? 'active' : '' }}">
                    Budidaya Tanaman
                </a>
                <a href="{{ route('pelatihan.index', ['kategori' => 'Pembekalan Pensiun']) }}" class="catalog-chip {{ $kategoriAktif === 'Pembekalan Pensiun' ? 'active' : '' }}">
                    Pembekalan Pensiun
                </a>
            </div>
        </div>

        {{-- Daftar Kartu Kelas (Ringkas & Scannable) --}}
        <div class="catalog-product-grid">
            @forelse ($kelasList as $item)
                <article class="catalog-product-card">
                    <a href="{{ route('pelatihan.show', $item['slug']) }}" class="catalog-product-link">
                        <div class="catalog-product-media catalog-product-media--placeholder">
                            <span class="product-badge {{ $item['is_available'] ? 'product-badge--preorder' : '' }}">
                                {{ $item['status'] }}
                            </span>
                            <span>Foto Kelas Menyusul</span>
                        </div>

                        <div class="catalog-product-body">
                            <span class="product-category">{{ $item['kategori'] }}</span>
                            <h3>{{ $item['judul'] }}</h3>
                            <p style="min-height: auto;">{{ $item['ringkasan'] }}</p>

                            {{-- Meta Pill Ikon SVG --}}
                            <div class="d-flex flex-wrap gap-2 mt-2 pt-2 border-top">
                                <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill" style="background: var(--c-paper); color: var(--c-deep); font-size: 0.76rem; font-weight: 600;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                    {{ $item['jadwal'] }}
                                </span>
                                <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill" style="background: var(--c-paper); color: var(--c-deep); font-size: 0.76rem; font-weight: 600;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    {{ $item['durasi'] }}
                                </span>
                            </div>

                            <div class="catalog-product-price pt-1">
                                <strong>Rp {{ number_format($item['harga'], 0, ',', '.') }} <small class="fw-normal text-muted">/ org</small></strong>
                                <span>{{ $item['kuota'] }}</span>
                            </div>
                        </div>
                    </a>

                    <div class="catalog-card-actions mt-auto">
                        <a href="{{ route('pelatihan.show', $item['slug']) }}" class="catalog-buy-button">
                            {{ $item['is_available'] ? 'Detail & Daftar' : 'Lihat Detail' }}
                        </a>
                    </div>
                </article>
            @empty
                <div class="catalog-empty">
                    <h2>Belum ada kelas pada kategori ini</h2>
                    <a href="{{ route('pelatihan.index') }}" class="catalog-action">Lihat Semua Kelas</a>
                </div>
            @endforelse
        </div>

    </div>
</section>
@endsection
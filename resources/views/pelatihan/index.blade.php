@extends('layouts.app')

@section('title', 'Daftar Kelas Pelatihan - Agrinova Farm')
@section('meta_description', 'Program pelatihan budidaya melon, sayur hidroponik, dan pembekalan usaha tani masa pensiun di Agrinova Farm.')

@section('content')
<section class="catalog-page">
    <div class="container catalog-shell">

        {{-- Hero Banner Pelatihan --}}
        <div class="catalog-hero pelatihan-hero">
            <div>
                <span class="eyebrow eyebrow--light">Edukasi Agribisnis</span>
                <h1 class="text-white mt-2 mb-2 pelatihan-hero-title">
                    Pelatihan &amp; Pembekalan Tani
                </h1>
                <p class="mb-0 pelatihan-hero-desc">
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

        {{-- Filter Kategori Kelas --}}
        <div class="pelatihan-filter-wrapper">
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
                            <p class="pelatihan-card-summary">{{ $item['ringkasan'] }}</p>

                            {{-- Meta Pill Ikon SVG --}}
                            <div class="pelatihan-meta-row">
                                <span class="pelatihan-meta-pill">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                    {{ $item['jadwal'] }}
                                </span>
                                <span class="pelatihan-meta-pill">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    {{ $item['durasi'] }}
                                </span>
                            </div>

                            <div class="catalog-product-price pelatihan-price-row">
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

<style>
    /* ===== Pelatihan Index: mobile-first scoped styles ===== */

    /* -- Hero responsive -- */
    .pelatihan-hero-title {
        font-size: clamp(1.9rem, 4.2vw, 3.2rem);
        line-height: 1.08;
    }
    .pelatihan-hero-desc {
        color: rgba(255, 255, 255, 0.82);
        max-width: 34rem;
        font-size: 1rem;
    }

    /* -- Filter row: horizontal scrollable on mobile, no overflow -- */
    .pelatihan-filter-wrapper {
        width: 100%;
        overflow: hidden;
    }
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
        min-height: 2.75rem;
        padding: 0.65rem 1.1rem;
    }

    /* -- Card summary text -- */
    .pelatihan-card-summary {
        min-height: auto;
    }

    /* -- Meta pills inside card -- */
    .pelatihan-meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        margin-top: 0.35rem;
        padding-top: 0.65rem;
        border-top: 1px solid rgba(9, 35, 40, 0.08);
    }
    .pelatihan-meta-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.35rem 0.55rem;
        border-radius: 999px;
        background: var(--c-paper);
        color: var(--c-deep);
        font-size: 0.74rem;
        font-weight: 600;
        white-space: nowrap;
    }

    /* -- Price row -- */
    .pelatihan-price-row {
        flex-wrap: wrap;
    }

    /* ===== Small phone ≤ 576px ===== */
    @media (max-width: 575.98px) {
        .pelatihan-hero-title {
            font-size: 1.55rem;
            line-height: 1.15;
        }
        .pelatihan-hero-desc {
            font-size: 0.9rem;
        }
        .catalog-hero.pelatihan-hero {
            padding: 1.25rem;
            border-radius: 1.25rem;
            gap: 1.25rem;
        }
        .catalog-hero.pelatihan-hero .catalog-hero-note {
            padding: 0.9rem;
            gap: 0.35rem;
        }
        .catalog-hero.pelatihan-hero .catalog-hero-note strong {
            font-size: 0.95rem;
        }
        .catalog-hero.pelatihan-hero .catalog-hero-note span:last-child {
            font-size: 0.82rem;
        }

        .pelatihan-filter-row .catalog-chip {
            padding: 0.55rem 0.85rem;
            font-size: 0.88rem;
        }

        .catalog-product-body h3 {
            font-size: 1.05rem;
        }
        .pelatihan-card-summary {
            font-size: 0.84rem;
        }
        .pelatihan-meta-pill {
            font-size: 0.7rem;
            padding: 0.3rem 0.5rem;
        }

        .catalog-card-actions {
            padding: 0 0.75rem 0.75rem;
        }
        .catalog-buy-button {
            min-height: 2.75rem;
            font-size: 0.92rem;
        }

        .catalog-empty {
            padding: 2rem 1rem;
        }
        .catalog-empty h2 {
            font-size: 1.15rem;
        }
    }

    /* ===== Tiny phone ≤ 375px ===== */
    @media (max-width: 375px) {
        .pelatihan-hero-title {
            font-size: 1.35rem;
        }
        .pelatihan-hero-desc {
            font-size: 0.85rem;
        }
        .pelatihan-meta-row {
            gap: 0.3rem;
        }
        .pelatihan-meta-pill {
            font-size: 0.66rem;
            padding: 0.25rem 0.4rem;
        }
        .catalog-product-price strong {
            font-size: 0.92rem;
        }
    }
</style>
@endsection
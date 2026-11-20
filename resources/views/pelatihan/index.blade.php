@extends('layouts.app')

@section('title', 'Budidaya Agrinova')
@section('meta_description', 'Program pelatihan budidaya melon, sayur hidroponik, dan pembuatan pupuk organik di Agrinova Farm.')
@section('body_class', 'page-mobile-subpage')

@section('content')
<div class="agr-edu-page">
    <div class="agr-edu-container">
        {{-- Mobile Top Bar --}}
        <header class="agr-edu-topbar">
            <a href="{{ route('catalog') }}" class="agr-edu-back" aria-label="Kembali ke katalog">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </a>
            <h1 class="agr-edu-topbar-title">Budidaya Agrinova</h1>
        </header>

        {{-- Segmented Control Tab (Eduwisata | Budidaya) --}}
        <div class="agr-segmented-tabs" role="tablist">
            <a href="{{ route('eduwisata.index') }}" class="agr-segmented-tab" role="tab" aria-selected="false">
                Eduwisata
            </a>
            <a href="{{ route('pelatihan.index') }}" class="agr-segmented-tab is-active" role="tab" aria-selected="true">
                Budidaya
            </a>
        </div>

        {{-- List Card Pelatihan --}}
        <div class="agr-edu-card-list">
            @forelse ($classes as $classModel)
                @php
                    $item = $classModel->toPublicArray();
                @endphp
                <article class="agr-edu-card">
                    <div class="agr-edu-card-media">
                        <img src="{{ asset($item['image']) }}" alt="{{ $item['judul'] }}" loading="lazy">
                        <span class="agr-edu-card-badge">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                            </svg>
                            {{ $item['kategori'] }}
                        </span>
                    </div>

                    <div class="agr-edu-card-body">
                        <h2>{{ $item['judul'] }}</h2>
                        <p>{{ $item['ringkasan'] }}</p>

                        <div class="agr-edu-card-meta">
                            <div>
                                <strong>Rp{{ number_format($item['harga'], 0, ',', '.') }}</strong>
                                <span>/ Peserta</span>
                            </div>
                            <small>{{ $item['kuota'] }}</small>
                        </div>

                        <a href="{{ route('pelatihan.show', $item['slug']) }}" class="agr-edu-primary-btn">
                            Lihat detail
                        </a>
                    </div>
                </article>
            @empty
                <div class="catalog-empty">
                    <h2>Belum ada program pelatihan</h2>
                    <p>Program pelatihan budidaya akan segera tersedia.</p>
                </div>
            @endforelse
        </div>

        {{-- Custom Pagination --}}
        @if ($classes->hasPages())
            <div class="agr-edu-pagination-wrap">
                <p class="agr-edu-pagination-info">
                    Menampilkan {{ $classes->count() }} dari {{ $classes->total() }} paket
                </p>
                <nav class="agr-edu-pagination" aria-label="Navigasi halaman pelatihan">
                    @if ($classes->onFirstPage())
                        <span class="agr-page-btn is-disabled" aria-disabled="true">&lsaquo;</span>
                    @else
                        <a href="{{ $classes->previousPageUrl() }}" class="agr-page-btn" aria-label="Halaman sebelumnya">&lsaquo;</a>
                    @endif

                    @for ($page = 1; $page <= $classes->lastPage(); $page++)
                        @if ($page === $classes->currentPage())
                            <span class="agr-page-btn is-active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $classes->url($page) }}" class="agr-page-btn">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($classes->hasMorePages())
                        <a href="{{ $classes->nextPageUrl() }}" class="agr-page-btn" aria-label="Halaman berikutnya">&rsaquo;</a>
                    @else
                        <span class="agr-page-btn is-disabled" aria-disabled="true">&rsaquo;</span>
                    @endif
                </nav>
            </div>
        @endif
    </div>
</div>
@endsection
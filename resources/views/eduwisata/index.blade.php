@extends('layouts.app')

@section('title', 'Eduwisata Agrinova')
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
            <h1 class="agr-edu-topbar-title">Eduwisata Agrinova</h1>
        </header>

        {{-- Segmented Control Tab (Eduwisata | Budidaya) --}}
        <div class="agr-segmented-tabs" role="tablist">
            <a href="{{ route('eduwisata.index') }}" class="agr-segmented-tab is-active" role="tab" aria-selected="true">
                Eduwisata
            </a>
            <a href="{{ route('pelatihan.index') }}" class="agr-segmented-tab" role="tab" aria-selected="false">
                Budidaya
            </a>
        </div>

        {{-- List Card Paket --}}
        <div class="agr-edu-card-list">
            @foreach ($packages as $paket)
                <article class="agr-edu-card">
                    <div class="agr-edu-card-media">
                        <img src="{{ asset($paket['image']) }}" alt="{{ $paket['title'] }}" loading="lazy">
                        <span class="agr-edu-card-badge">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                            </svg>
                            {{ $paket['tag'] }}
                        </span>
                    </div>

                    <div class="agr-edu-card-body">
                        <h2>{{ $paket['short_title'] }}</h2>
                        <p>{{ $paket['summary'] }}</p>

                        <div class="agr-edu-card-meta">
                            <div>
                                <strong>Rp{{ number_format($paket['price'], 0, ',', '.') }}</strong>
                                <span>/ Peserta</span>
                            </div>
                            <small>Kuota: {{ $paket['quota'] }}</small>
                        </div>

                        <a href="{{ route('eduwisata.show', $paket['slug']) }}" class="agr-edu-primary-btn">
                            Pilih paket ini
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Custom Pagination --}}
        @if ($packages->hasPages())
            <div class="agr-edu-pagination-wrap">
                <p class="agr-edu-pagination-info">
                    Menampilkan {{ $packages->count() }} dari {{ $packages->total() }} paket
                </p>
                <nav class="agr-edu-pagination" aria-label="Navigasi halaman paket">
                    @if ($packages->onFirstPage())
                        <span class="agr-page-btn is-disabled" aria-disabled="true">&lsaquo;</span>
                    @else
                        <a href="{{ $packages->previousPageUrl() }}" class="agr-page-btn" aria-label="Halaman sebelumnya">&lsaquo;</a>
                    @endif

                    @for ($page = 1; $page <= $packages->lastPage(); $page++)
                        @if ($page === $packages->currentPage())
                            <span class="agr-page-btn is-active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $packages->url($page) }}" class="agr-page-btn">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($packages->hasMorePages())
                        <a href="{{ $packages->nextPageUrl() }}" class="agr-page-btn" aria-label="Halaman berikutnya">&rsaquo;</a>
                    @else
                        <span class="agr-page-btn is-disabled" aria-disabled="true">&rsaquo;</span>
                    @endif
                </nav>
            </div>
        @endif
    </div>
</div>
@endsection
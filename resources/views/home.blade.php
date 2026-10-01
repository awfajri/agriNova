@extends('layouts.app')

@section('title', 'Kebun Melon & Sayur Segar Bekasi | Agrinova Farm')
@section('meta_description', 'Agrinova Farm: kebun melon, sayur segar, pelatihan budidaya, dan eduwisata agribisnis.')
@section('body_class', 'has-hero')

@section('content')

{{-- ===== HERO ===== --}}
@php
    $farmerLabel = 'Komunitas Petani';
@endphp

<section class="hero" id="hero">
    <div class="container hero-inner">
        <span class="hero-badge">Kebun Melon Agrinova</span>

        <h1 class="hero-title">
            Kebun melon &amp; sayur segar,<br>
            langsung dari kebun kami
        </h1>

        <p class="hero-desc">
            Belanja produk segar, pre-order panen melon, ikuti pelatihan budidaya,
            dan kunjungi eduwisata kami dalam satu tempat.
        </p>

        <div class="hero-actions">
            <a href="#" class="btn-pill btn-pill--solid">
                Belanja Sekarang
                <span class="btn-pill-icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </span>
            </a>
            <a href="#" class="btn-pill btn-pill--glass">
                Jelajahi Produk
                <span class="btn-pill-icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                </span>
            </a>
        </div>
    </div>

    <div class="hero-bottom">
        <a href="#pilar" class="scroll-hint">
            Scroll
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
        </a>

        <div class="farmer-pill">
            <div class="avatars" aria-hidden="true">
                <span>A</span><span>B</span><span>C</span>
            </div>
            {{ $farmerLabel }}
        </div>
    </div>
</section>

{{-- ===== MARQUEE ===== --}}
@php
    $marqueeItems = [
        'Melon Premium',
        'Sayur Segar Bekasi',
        'Pre-Order Panen',
        'Pelatihan Budidaya',
        'Pembekalan Pensiun',
        'Eduwisata Agribisnis',
        'Perlengkapan Kebun',
        'Pupuk',
    ];
@endphp

<section class="marquee" aria-label="Produk dan layanan Agrinova">
    <div class="marquee-track">
        @for ($i = 0; $i < 4; $i++)
            <div class="marquee-group" @if($i > 0) aria-hidden="true" @endif>
                @foreach ($marqueeItems as $item)
                    <span class="marquee-item">{{ $item }}</span>
                @endforeach
            </div>
        @endfor
    </div>
</section>

{{-- ===== TIGA PILAR (kartu menumpuk saat scroll) ===== --}}
@php
    $pillars = [
        [
            'no'    => '01',
            'tag'   => 'Produk Segar',
            'title' => 'Buah & Sayur Segar',
            'desc'  => 'Melon, buah, dan sayur dipanen langsung dari kebun Agrinova. Bisa dibeli langsung atau di-pre-order sebelum panen.',
            'cta'   => 'Lihat Produk',
            'href'  => '#',
            'theme' => 'deep',
        ],
        [
            'no'    => '02',
            'tag'   => 'Pelatihan',
            'title' => 'Pelatihan Budidaya & Pembekalan Pensiun',
            'desc'  => 'Kelas budidaya melon, anggur, dan tanaman lainnya, serta pembekalan usaha tani bagi yang memasuki masa pensiun.',
            'cta'   => 'Lihat Kelas',
            'href'  => '#',
            'theme' => 'green',
        ],
        [
            'no'    => '03',
            'tag'   => 'Eduwisata',
            'title' => 'Eduwisata Kebun',
            'desc'  => 'Kunjungan edukatif ke kebun untuk sekolah, komunitas, dan keluarga. Reservasi mudah lewat formulir online.',
            'cta'   => 'Reservasi Kunjungan',
            'href'  => '#',
            'theme' => 'sage',
        ],
    ];
@endphp

<section class="section-pilar" id="pilar">
    <div class="container">
        <div class="section-head">
            <h2>Tiga Pilar Agrinova</h2>
            <p>Produk segar, pelatihan, dan eduwisata dalam satu platform.</p>
        </div>

        <div class="pilar-stack">
            @foreach ($pillars as $i => $p)
                <article class="pilar-item" style="--i: {{ $i }}">
                    <div class="pilar-card pilar-card--{{ $p['theme'] }}">
                        <div class="pilar-text">
                            <span class="pilar-no">{{ $p['no'] }}</span>
                            <span class="pilar-tag">{{ $p['tag'] }}</span>
                            <h3>{{ $p['title'] }}</h3>
                            <p>{{ $p['desc'] }}</p>
                            <a href="{{ $p['href'] }}" class="pilar-link">
                                {{ $p['cta'] }}
                                <span aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
                                </span>
                            </a>
                        </div>
                        <div class="pilar-visual">
                            <span>Foto menyusul</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

@endsection
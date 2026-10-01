@extends('layouts.app')

@section('title', 'Kebun Melon & Sayur Segar Bekasi | Agrinova Farm')
@section('meta_description', 'Agrinova Farm: kebun melon, sayur segar, pelatihan budidaya, dan eduwisata agribisnis.')
@section('body_class', 'has-hero')

@section('content')

{{-- ===== HERO ===== --}}
<section class="hero">
    <div class="container hero-inner">
        <h1 class="hero-title">
            Kebun melon &amp; sayur segar,<br>
            <span class="hero-accent">langsung dari kebun kami</span>
        </h1>
        <p class="hero-desc">
            Belanja produk segar, pre-order panen melon, ikuti pelatihan budidaya,
            dan kunjungi eduwisata kami dalam satu tempat.
        </p>
        <div class="hero-actions">
            <a href="#" class="btn-ghost-dark">Belanja Sekarang</a>
            <a href="#" class="btn-agri-pill">
                Pre-Order Melon
                <span class="arrow-circle" aria-hidden="true">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
                </span>
            </a>
        </div>
    </div>

    {{-- Ruang untuk kartu 3D melengkung (langkah berikutnya) --}}
    <div class="hero-stage" id="heroStage"></div>
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

@endsection
@extends('layouts.app')

@section('title', $kelas['judul'] . ' - Detail Pelatihan Agrinova')
@section('body_class', 'page-mobile-subpage')

@section('content')
<div class="agr-edu-page has-sticky-bottom">
    <div class="agr-edu-container">
        {{-- Mobile Top Bar --}}
        <header class="agr-edu-topbar">
            <a href="{{ route('pelatihan.index') }}" class="agr-edu-back" aria-label="Kembali ke daftar pelatihan">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </a>
            <h1 class="agr-edu-topbar-title">Detail Pelatihan</h1>
        </header>

        <div class="agr-edu-detail-hero">
            <img src="{{ asset($kelas['image']) }}" alt="{{ $kelas['judul'] }}">
        </div>

        <div class="agr-edu-detail-head">
            <h2>{{ $kelas['judul'] }}</h2>
            <p>{{ $kelas['ringkasan'] ?: 'Terbuka untuk keluarga, sekolah, & instansi.' }}</p>
        </div>

        <section class="agr-edu-highlights-section">
            <h3>Materi pelatihan</h3>
            <div class="agr-edu-highlights-box">
                <ul class="agr-edu-highlights-list">
                    @foreach ($kelas['materi'] as $idx => $materi)
                        <li>
                            <span class="agr-edu-bullet-icon" aria-hidden="true">{{ $idx + 1 }}</span>
                            <span>{{ $materi }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    </div>

    {{-- Sticky Bottom Bar --}}
    <div class="agr-edu-bottom-bar">
        <div class="agr-edu-bottom-inner">
            @if ($kelas['is_available'])
                <a href="{{ route('pelatihan.create', $kelas['slug']) }}" class="agr-edu-primary-btn">
                    Daftar pelatihan
                </a>
            @else
                <span class="agr-edu-primary-btn" style="opacity: 0.55; cursor: not-allowed;">
                    Kuota Kelas Penuh
                </span>
            @endif
        </div>
    </div>
</div>
@endsection
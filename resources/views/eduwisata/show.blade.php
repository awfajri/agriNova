@extends('layouts.app')

@section('title', $package['title'] . ' - Eduwisata Agrinova')
@section('body_class', 'page-mobile-subpage')

@section('content')
<div class="agr-edu-page has-sticky-bottom">
    <div class="agr-edu-container">
        <header class="agr-edu-topbar">
            <a href="{{ route('eduwisata.index') }}" class="agr-edu-back" aria-label="Kembali ke daftar paket">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </a>
            <h1 class="agr-edu-topbar-title">Detail paket</h1>
        </header>

        <div class="agr-edu-detail-hero">
            <img src="{{ asset($package['image']) }}" alt="{{ $package['title'] }}">
        </div>

        <div class="agr-edu-detail-head">
            <h2>{{ $package['title'] }} &ndash; Rp{{ number_format($package['price'], 0, ',', '.') }}/peserta</h2>
            <p>{{ $package['subtitle'] }}</p>
        </div>

        <section class="agr-edu-highlights-section">
            <h3>Ada apa di kebun?</h3>
            <div class="agr-edu-highlights-box">
                <ul class="agr-edu-highlights-list">
                    @foreach ($package['highlights'] as $item)
                        <li>
                            <span class="agr-edu-bullet-icon" aria-hidden="true">&#10003;</span>
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    </div>

    {{-- Sticky Bottom Bar --}}
    <div class="agr-edu-bottom-bar">
        <div class="agr-edu-bottom-inner">
            <a href="{{ route('eduwisata.create', $package['slug']) }}" class="agr-edu-primary-btn">
                Pilih paket
            </a>
        </div>
    </div>
</div>
@endsection
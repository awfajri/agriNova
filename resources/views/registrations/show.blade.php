@extends('layouts.app')

@php
    $isTraining = $type === 'pelatihan';
    $serviceLabel = $isTraining ? 'Pendaftaran Pelatihan' : 'Reservasi Eduwisata';

    $statusIndex = match ($registration->status) {
        'confirmed' => 2,
        'completed' => 3,
        default => 1,
    };

    $priceMap = [
        'Paket A (Basic)' => 35000,
        'Paket B' => 65000,
        'Paket C' => 80000,
        'Paket D' => 60000,
        'Paket E' => 170000,
        'Paket F' => 55000,
    ];

    $unitPrice = ! $isTraining ? ($priceMap[$registration->package_name] ?? 0) : 0;
    $estimatedTotal = $unitPrice > 0 ? $unitPrice * (int) $registration->participants : 0;

    $details = $isTraining
        ? [
            ['label' => 'Program pelatihan', 'value' => $registration->trainingClass->title ?? $registration->class_title],
            ['label' => 'Nama peserta', 'value' => $registration->name],
            ['label' => 'Jumlah peserta', 'value' => $registration->participants . ' orang'],
            ['label' => 'Kontak', 'value' => 'WhatsApp terdaftar'],
        ]
        : [
            ['label' => 'Paket eduwisata', 'value' => $registration->package_name],
            ['label' => 'Nama PIC', 'value' => $registration->contact_name],
            ['label' => 'Rombongan', 'value' => $registration->group_name],
            ['label' => 'Tanggal kunjungan', 'value' => $registration->visit_date?->translatedFormat('d M Y') ?? '-'],
            ['label' => 'Sesi waktu', 'value' => $registration->session],
            ['label' => 'Jumlah peserta', 'value' => $registration->participants . ' orang'],
        ];
@endphp

@section('title', $serviceLabel . ' ' . $registration->reference_number . ' - Agrinova')
@section('meta_description', 'Informasi dan status terbaru ' . strtolower($serviceLabel) . ' Agrinova.')
@section('body_class', 'page-checkout-flow')

@section('content')
<div class="agr-flow-page">
    <div class="agr-flow-container">
        {{-- Mobile Top Bar --}}
        <header class="agr-flow-mobile-topbar">
            <a href="{{ $isTraining ? route('pelatihan.index') : route('eduwisata.index') }}"
               class="agr-flow-back"
               aria-label="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </a>
            <h1 class="agr-flow-title">Detail Konfirmasi</h1>
        </header>

        {{-- Stepper Status Dinamis --}}
        <nav class="agr-flow-stepper" aria-label="Tahapan pendaftaran">
            <div class="agr-flow-step">
                <div class="agr-flow-step-circle is-done">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span class="agr-flow-step-label">Tercatat</span>
            </div>

            <div class="agr-flow-step-line {{ $statusIndex >= 2 ? 'is-done' : '' }}"></div>

            <div class="agr-flow-step">
                <div class="agr-flow-step-circle {{ $statusIndex >= 2 ? 'is-done' : 'is-active' }}">
                    @if ($statusIndex >= 2)
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    @else
                        2
                    @endif
                </div>
                <span class="agr-flow-step-label {{ $statusIndex === 1 ? 'is-active' : '' }}">Konfirmasi</span>
            </div>

            <div class="agr-flow-step-line {{ $statusIndex >= 3 ? 'is-done' : '' }}"></div>

            <div class="agr-flow-step">
                <div class="agr-flow-step-circle {{ $statusIndex >= 3 ? 'is-done' : 'is-pending' }}">
                    @if ($statusIndex >= 3)
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    @else
                        3
                    @endif
                </div>
                <span class="agr-flow-step-label {{ $statusIndex < 3 ? 'is-pending' : '' }}">Selesai</span>
            </div>
        </nav>

        {{-- Status Hero --}}
        <section class="agr-confirm-hero">
            <div class="agr-confirm-icon" aria-hidden="true">
                @if ($registration->status === 'confirmed' || $registration->status === 'completed')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                    </svg>
                @endif
            </div>

            <h2 class="agr-confirm-heading">
                @if ($registration->status === 'rejected')
                    Pendaftaran Belum Dapat Diproses
                @elseif ($registration->status === 'confirmed')
                    Jadwalmu Sudah Dikonfirmasi
                @elseif ($registration->status === 'completed')
                    Kegiatan Selesai
                @else
                    {{ $serviceLabel }} Tercatat
                @endif
            </h2>

            <span class="agr-confirm-badge">{{ $registration->statusLabel() }}</span>

            <p class="agr-confirm-subtitle">
                @if ($registration->status === 'rejected')
                    Pendaftaran belum dapat dikonfirmasi. Hubungi admin untuk mendiskusikan jadwal alternatif.
                @elseif ($registration->status === 'confirmed')
                    Simpan kode referensi ini dan pastikan nomor WhatsApp tetap aktif hingga hari pelaksanaan.
                @elseif ($registration->status === 'completed')
                    Terima kasih telah berkegiatan bersama Agrinova Farm. Sampai jumpa di kesempatan berikutnya!
                @else
                    Kirim rincian pendaftaran ke WhatsApp admin untuk pengecekan kuota jadwal &amp; pembayaran DP.
                @endif
            </p>
        </section>

        {{-- Kartu Rincian Pendaftaran --}}
        <section class="agr-confirm-receipt">
            <div class="agr-confirm-receipt-head">
                <span>#{{ $registration->reference_number }}</span>
                <button type="button"
                        class="agr-copy-btn"
                        data-copy-target="#{{ $registration->reference_number }}"
                        aria-label="Salin kode referensi">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <rect x="9" y="9" width="11" height="11" rx="2"/>
                        <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                    </svg>
                    <span>Salin</span>
                </button>
            </div>

            <div class="agr-confirm-receipt-rows">
                @foreach ($details as $detail)
                    @if (filled($detail['value']) && $detail['value'] !== '-')
                        <div class="agr-confirm-row">
                            <span>{{ $detail['label'] }}</span>
                            <strong>{{ $detail['value'] }}</strong>
                        </div>
                    @endif
                @endforeach

                @if ($estimatedTotal > 0)
                    <div class="agr-confirm-row is-total">
                        <span>Estimasi total biaya</span>
                        <strong>Rp{{ number_format((float) $estimatedTotal, 0, ',', '.') }}</strong>
                    </div>
                @endif
            </div>
        </section>
    </div>

    {{-- Bottom Action Area --}}
    <div class="agr-flow-bottom-bar">
        <div class="agr-flow-bottom-inner">
            <h3 class="agr-confirm-action-title">Konfirmasi ke Admin</h3>
            <p class="agr-confirm-action-desc">
                Format rincian otomatis terisi di pesan WhatsApp. Kamu bisa langsung mengirimkannya ke admin.
            </p>

            @if ($whatsappUrl)
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="agr-flow-primary-btn">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18" aria-hidden="true">
                        <path d="M12.04 2a9.96 9.96 0 0 0-8.58 15.08L2 22l5.08-1.42A9.96 9.96 0 1 0 12.04 2Zm0 18.18a8.2 8.2 0 0 1-4.18-1.14l-.3-.18-3.02.84.85-2.94-.2-.3a8.2 8.2 0 1 1 6.85 3.72Zm4.5-6.15c-.25-.13-1.48-.73-1.71-.82-.23-.08-.4-.13-.57.13-.17.25-.65.82-.8.99-.15.17-.3.19-.55.06-.25-.13-1.04-.38-1.98-1.21-.73-.65-1.22-1.45-1.36-1.7-.14-.25-.01-.39.11-.52.11-.11.25-.3.38-.44.13-.15.17-.25.25-.42.08-.17.04-.32-.02-.44-.06-.13-.57-1.36-.78-1.86-.21-.49-.42-.42-.57-.43h-.49c-.17 0-.44.06-.67.32-.23.25-.88.86-.88 2.09s.9 2.42 1.03 2.59c.13.17 1.77 2.71 4.29 3.8.6.26 1.07.41 1.43.53.6.19 1.14.16 1.57.1.48-.07 1.48-.61 1.69-1.2.21-.59.21-1.1.15-1.2-.06-.1-.23-.16-.48-.29Z"/>
                    </svg>
                    <span>Konfirmasi via WhatsApp</span>
                </a>
            @endif

            <a href="{{ route('catalog') }}" class="agr-flow-secondary-btn">Kembali ke Katalog</a>
        </div>
    </div>
</div>
@endsection
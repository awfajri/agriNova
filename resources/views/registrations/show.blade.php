@extends('layouts.app')

@php
    $isTraining = $type === 'pelatihan';
    $serviceLabel = $isTraining ? 'Pendaftaran pelatihan' : 'Reservasi eduwisata';
    $statusIndex = match ($registration->status) {
        'confirmed' => 1,
        'completed' => 2,
        default => 0,
    };
    $details = $isTraining
        ? [
            ['label' => 'Nama peserta', 'value' => $registration->name],
            ['label' => 'Kelas', 'value' => $registration->class_title],
            ['label' => 'Jumlah peserta', 'value' => $registration->participants . ' orang'],
            ['label' => 'Kontak', 'value' => 'WhatsApp terdaftar'],
        ]
        : [
            ['label' => 'Nama PIC', 'value' => $registration->contact_name],
            ['label' => 'Rombongan', 'value' => $registration->group_name],
            ['label' => 'Tanggal kunjungan', 'value' => $registration->visit_date?->translatedFormat('d F Y') ?? '-'],
            ['label' => 'Sesi', 'value' => $registration->session],
            ['label' => 'Jumlah peserta', 'value' => $registration->participants . ' orang'],
        ];
@endphp

@section('title', $serviceLabel . ' ' . $registration->reference_number . ' | Agrinova')
@section('meta_description', 'Informasi dan status terbaru ' . strtolower($serviceLabel) . ' Agrinova.')

@section('content')
<section class="registration-tracking-page registration-result-page">
    <div class="container registration-tracking-shell">
        <a href="{{ route('registrations.track') }}" class="tracking-back-link">← Lacak pendaftaran lain</a>

        <div class="registration-result-header">
            <span class="eyebrow">{{ $serviceLabel }}</span>
            <h1>Status pendaftaran</h1>
            <p>Gunakan kode <strong>{{ $registration->reference_number }}</strong> saat menghubungi admin Agrinova.</p>
        </div>

        <div class="registration-result-grid">
            <div class="registration-result-main">
                <section class="registration-status-card">
                    <div class="registration-status-card__top">
                        <div>
                            <span class="registration-status-card__label">Status saat ini</span>
                            <h2>{{ $registration->statusLabel() }}</h2>
                        </div>
                        <span class="registration-status-badge registration-status-badge--{{ $registration->status }}">
                            {{ $registration->statusLabel() }}
                        </span>
                    </div>

                    @if ($registration->status === 'rejected')
                        <div class="registration-alert registration-alert--error">
                            Pendaftaran belum dapat dikonfirmasi. Hubungi admin untuk mengetahui alasannya atau mendiskusikan jadwal alternatif.
                        </div>
                    @else
                        <div class="registration-timeline" aria-label="Tahapan pendaftaran">
                            @foreach ($registration->statusSteps() as $index => $step)
                                <div class="registration-timeline-step {{ $index < $statusIndex ? 'is-complete' : '' }} {{ $index === $statusIndex ? 'is-current' : '' }}">
                                    <span class="registration-timeline-marker">
                                        @if ($index < $statusIndex)
                                            ✓
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </span>
                                    <div>
                                        <strong>{{ $step['label'] }}</strong>
                                        <p>{{ $step['description'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                <section class="registration-details-card">
                    <div class="registration-card-heading">
                        <div>
                            <span class="eyebrow">Ringkasan</span>
                            <h2>Detail pendaftaran</h2>
                        </div>
                        <span class="registration-reference">{{ $registration->reference_number }}</span>
                    </div>
                    <div class="registration-details-grid">
                        @foreach ($details as $detail)
                            <div>
                                <span>{{ $detail['label'] }}</span>
                                <strong>{{ $detail['value'] }}</strong>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <aside class="registration-next-card">
                <span class="registration-guide-icon" aria-hidden="true">→</span>
                <span class="eyebrow">Langkah berikutnya</span>
                @if ($registration->status === 'pending')
                    <h2>Menunggu konfirmasi admin</h2>
                    <p>Admin akan meninjau data dan menghubungi nomor WhatsApp yang kamu daftarkan untuk konfirmasi.</p>
                @elseif ($registration->status === 'confirmed')
                    <h2>Siapkan keikutsertaanmu</h2>
                    <p>Jadwalmu sudah dikonfirmasi. Simpan halaman ini dan pastikan nomor WhatsApp tetap aktif.</p>
                @elseif ($registration->status === 'completed')
                    <h2>Terima kasih sudah bersama Agrinova</h2>
                    <p>Kegiatan sudah selesai. Sampai jumpa di kegiatan Agrinova berikutnya.</p>
                @else
                    <h2>Perlu bantuan?</h2>
                    <p>Admin dapat membantu menjelaskan status pendaftaran atau pilihan jadwal lainnya.</p>
                @endif

                @if ($whatsappUrl)
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="registration-whatsapp-button">
                        Hubungi admin via WhatsApp
                        <span aria-hidden="true">↗</span>
                    </a>
                @endif
                <a href="{{ $isTraining ? route('pelatihan.index') : route('eduwisata.index') }}" class="registration-secondary-link">
                    {{ $isTraining ? 'Lihat pelatihan lain' : 'Lihat info eduwisata' }}
                </a>
            </aside>
        </div>
    </div>
</section>
@endsection

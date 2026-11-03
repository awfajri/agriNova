@extends('layouts.app')

@section('title', 'Lacak Pendaftaran | Agrinova')
@section('meta_description', 'Lacak status pendaftaran pelatihan atau reservasi eduwisata Agrinova menggunakan kode referensi dan nomor WhatsApp.')

@section('content')
<section class="registration-tracking-page">
    <div class="container registration-tracking-shell">
        <div class="registration-tracking-intro">
            <span class="eyebrow">Pusat informasi pendaftar</span>
            <h1>Lacak pendaftaranmu</h1>
            <p>Gunakan kode referensi dan nomor WhatsApp yang kamu masukkan saat mendaftar untuk melihat status terbaru.</p>
        </div>

        <div class="registration-tracking-layout">
            <form action="{{ route('registrations.lookup') }}" method="POST" class="registration-tracking-form">
                @csrf
                <div class="checkout-step">
                    <span class="checkout-step-number">1</span>
                    <div>
                        <h2>Masukkan data pendaftaran</h2>
                        <p>Kode biasanya diawali <strong>TRN-</strong> untuk pelatihan atau <strong>EDU-</strong> untuk eduwisata.</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="registration-alert registration-alert--error" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="checkout-fields">
                    <div class="checkout-field checkout-field--full">
                        <label for="reference_number">Kode referensi</label>
                        <input type="text" id="reference_number" name="reference_number" value="{{ old('reference_number') }}" placeholder="Contoh: TRN-261103-ABC123" required autocomplete="off">
                    </div>
                    <div class="checkout-field checkout-field--full">
                        <label for="tracking_whatsapp">Nomor WhatsApp</label>
                        <input type="tel" id="tracking_whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="Nomor yang digunakan saat daftar" required autocomplete="tel">
                    </div>
                </div>

                <button type="submit" class="commerce-checkout-button">Lihat status pendaftaran</button>
            </form>

            <aside class="registration-tracking-guide">
                <span class="registration-guide-icon" aria-hidden="true">?</span>
                <h2>Belum menemukan kodenya?</h2>
                <p>Kode referensi muncul setelah formulir berhasil dikirim. Cek kembali halaman konfirmasi atau hubungi admin Agrinova melalui WhatsApp.</p>
                <div class="registration-guide-links">
                    <a href="{{ route('pelatihan.index') }}">Lihat pelatihan <span aria-hidden="true">↗</span></a>
                    <a href="{{ route('eduwisata.index') }}">Lihat eduwisata <span aria-hidden="true">↗</span></a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

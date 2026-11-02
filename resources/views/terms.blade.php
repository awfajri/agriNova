@extends('layouts.app')

@section('title', 'Ketentuan Penggunaan — Agrinova Farm')
@section('body_class', 'profile-page')

@section('content')
<main class="profile-main">
    <article class="profile-card profile-terms-card">
        <span class="eyebrow">Informasi akun</span>
        <h1>Ketentuan penggunaan</h1>
        <p class="profile-lead">Panduan singkat saat menggunakan layanan Agrinova.</p>

        <div class="profile-terms-content">
            <section>
                <h2>Pemesanan produk</h2>
                <p>Stok dikurangi saat pesanan dibuat. Pesanan baru diproses setelah pembayaran terkonfirmasi atau setelah admin mengonfirmasi transfer manual.</p>
            </section>
            <section>
                <h2>Pengambilan pesanan</h2>
                <p>Agrinova tidak menggunakan kurir untuk pesanan ini. Pesanan diambil di lokasi Agrinova setelah status berubah menjadi “Siap diambil”.</p>
            </section>
            <section>
                <h2>Perubahan dan pembatalan</h2>
                <p>Hubungi Agrinova melalui WhatsApp jika ada perubahan data atau kendala pembayaran. Pembatalan mengikuti status stok dan proses yang sedang berjalan.</p>
            </section>
        </div>

        <a class="back" href="{{ route('profile.show') }}">Kembali ke akun</a>
    </article>
</main>
@endsection

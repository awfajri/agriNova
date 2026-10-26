@extends('layouts.app')

@section('title', 'Checkout | Agrinova')
@section('meta_description', 'Selesaikan pesanan produk Agrinova dengan checkout sederhana dan pembayaran online yang aman.')
@section('body_class', 'has-checkout')

@section('content')
<section class="commerce-page">
    <div class="container commerce-shell">
        <div class="commerce-heading">
            <span class="eyebrow">Langkah terakhir</span>
            <h1>Checkout</h1>
            <p>Isi data kontak, lalu selesaikan pembayaran langsung di halaman Agrinova.</p>
        </div>

        @if ($errors->any())
            <div class="catalog-alert catalog-alert--error" role="alert">
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="checkout-layout">
            <form method="POST" action="{{ route('checkout.store') }}" class="checkout-form">
                @csrf
                <div class="checkout-step">
                    <span class="checkout-step-number">1</span>
                    <div>
                        <h2>Kontak pemesan</h2>
                        <p>Nomor WhatsApp dipakai untuk mengonfirmasi stok, total akhir, dan instruksi pembayaran.</p>
                    </div>
                </div>
                <div class="checkout-fields">
                    <div class="checkout-field">
                        <label for="customer-name">Nama lengkap</label>
                        <input id="customer-name" name="customer_name" type="text" value="{{ old('customer_name') }}" autocomplete="name" required maxlength="120">
                    </div>
                    <div class="checkout-field">
                        <label for="customer-whatsapp">Nomor WhatsApp</label>
                        <input id="customer-whatsapp" name="customer_whatsapp" type="tel" value="{{ old('customer_whatsapp') }}" placeholder="Contoh: 081234567890" autocomplete="tel" required maxlength="24">
                        <small>Gunakan nomor yang aktif dan bisa menerima pesan.</small>
                    </div>
                    <div class="checkout-field checkout-field--full">
                        <label for="order-notes">Catatan pesanan <span>(opsional)</span></label>
                        <textarea id="order-notes" name="notes" rows="4" maxlength="1000" placeholder="Contoh: waktu yang nyaman untuk dihubungi atau catatan produk.">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="checkout-step">
                    <span class="checkout-step-number">2</span>
                    <div>
                        <h2>Metode pembayaran</h2>
                        <p>Pilih metode pembayaran yang paling nyaman. Pembayaran online akan dibuka dalam popup yang aman.</p>
                    </div>
                </div>
                @if (filled(config('services.midtrans.client_key')) && filled(config('services.midtrans.server_key')))
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="midtrans_snap" @checked(old('payment_method', 'midtrans_snap') === 'midtrans_snap')>
                        <span>
                            <strong>Bayar online</strong>
                            <small>Gunakan QRIS, transfer bank, e-wallet, atau kartu melalui Midtrans.</small>
                        </span>
                    </label>
                @endif
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="manual_transfer" @checked(old('payment_method', filled(config('services.midtrans.client_key')) && filled(config('services.midtrans.server_key')) ? 'midtrans_snap' : 'manual_transfer') === 'manual_transfer')>
                    <span>
                        <strong>Transfer manual</strong>
                        <small>Admin mengirimkan instruksi rekening setelah pesanan tercatat.</small>
                    </span>
                </label>
                <button type="submit" class="commerce-checkout-button checkout-submit">Lanjut ke pembayaran</button>
                <a href="{{ route('cart') }}" class="checkout-back">Kembali ke keranjang</a>
            </form>

            <aside class="commerce-summary checkout-summary">
                <h2>Pesanan kamu</h2>
                @foreach ($items as $item)
                    <div class="checkout-summary-item">
                        <span>{{ $item['product']->name }} <strong>&times; {{ $item['quantity'] }}</strong></span>
                        <strong>Rp {{ number_format($item['line_total'], 0, ',', '.') }}</strong>
                    </div>
                @endforeach
                <div class="commerce-summary-total">
                    <span>Total sementara</span>
                    <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                </div>
                <p class="commerce-summary-note">Harga akhir dan ketersediaan akan dikonfirmasi admin sebelum pembayaran.</p>
            </aside>
        </div>
    </div>
</section>
@endsection

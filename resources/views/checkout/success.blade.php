@extends('layouts.app')

@section('title', 'Pesanan Berhasil | Agrinova')
@section('meta_description', 'Pesanan Agrinova berhasil dicatat. Simpan nomor pesanan untuk konfirmasi berikutnya.')
@section('body_class', 'has-checkout')

@section('content')
<section class="commerce-page">
    <div class="container commerce-shell">
        <div class="order-success-card">
            <div class="order-success-icon" aria-hidden="true">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 4 4L19 6"/></svg>
            </div>
            <span class="eyebrow">Pesanan tercatat</span>
            <h1>Terima kasih, {{ $order->customer_name }}.</h1>
            <p>Pesananmu sudah masuk ke Agrinova. Simpan nomor ini untuk melihat status pembayaran dan konfirmasi berikutnya.</p>
            <div class="order-number">
                <span>Nomor pesanan</span>
                <strong>{{ $order->order_number }}</strong>
            </div>
            <div class="order-success-total">
                <span>Total sementara</span>
                <strong>Rp {{ number_format((float) $order->total, 0, ',', '.') }}</strong>
            </div>
            @if ($order->payment_method === 'midtrans_snap' && session('last_payment_token'))
                <div class="order-next-step">
                    <strong>Selesaikan pembayaran</strong>
                    <p>Pilih QRIS, transfer bank, e-wallet, atau kartu. Status pesanan akan diperbarui setelah Midtrans mengonfirmasi pembayaran ke server Agrinova.</p>
                    <button type="button" id="pay-order-button" class="commerce-checkout-button">Bayar sekarang</button>
                    <p id="payment-status-message" class="payment-status-message" role="status"></p>
                </div>
            @else
                <div class="order-next-step">
                    <strong>Langkah berikutnya</strong>
                    <p>Admin akan mengirimkan detail pembayaran manual ke nomor WhatsApp yang kamu berikan.</p>
                </div>
            @endif
            <div class="order-success-actions">
                <a href="{{ route('catalog') }}" class="catalog-action">Kembali belanja</a>
                <a href="{{ route('home') }}" class="catalog-action catalog-action--secondary">Ke beranda</a>
            </div>
        </div>
    </div>
</section>
@endsection

@if ($order->payment_method === 'midtrans_snap' && session('last_payment_token'))
    @push('scripts')
        <script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script>
            document.getElementById('pay-order-button')?.addEventListener('click', function () {
                const button = this;
                const status = document.getElementById('payment-status-message');
                button.disabled = true;
                status.textContent = 'Membuka pembayaran...';

                if (!window.snap) {
                    status.textContent = 'Layanan pembayaran belum termuat. Periksa koneksi lalu coba lagi.';
                    button.disabled = false;
                    return;
                }

                window.snap.pay(@json(session('last_payment_token')), {
                    onSuccess: function () {
                        status.textContent = 'Pembayaran diterima dan sedang dikonfirmasi.';
                        button.disabled = false;
                    },
                    onPending: function () {
                        status.textContent = 'Pembayaran belum selesai. Kamu bisa melanjutkannya dari popup Midtrans.';
                        button.disabled = false;
                    },
                    onError: function () {
                        status.textContent = 'Pembayaran gagal. Silakan coba lagi.';
                        button.disabled = false;
                    },
                    onClose: function () {
                        status.textContent = 'Popup pembayaran ditutup. Kamu bisa mencoba lagi kapan saja.';
                        button.disabled = false;
                    }
                });
            });
        </script>
    @endpush
@endif

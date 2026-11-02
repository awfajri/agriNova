@extends('layouts.app')

@section('title', 'Pesanan Berhasil | Agrinova')
@section('meta_description', 'Pesanan Agrinova berhasil dicatat. Simpan nomor pesanan untuk konfirmasi berikutnya.')
@section('body_class', 'has-checkout')

@section('content')
@php
    $formattedTotal = 'Rp ' . number_format((float) $order->total, 0, ',', '.');
    $whatsappMessage = "Halo Admin Agrinova, saya ingin konfirmasi pembayaran untuk pesanan {$order->order_number}. Total pesanan: {$formattedTotal}.";
    $whatsappUrl = config('agrinova.whatsapp')
        ? 'https://wa.me/' . config('agrinova.whatsapp') . '?text=' . rawurlencode($whatsappMessage)
        : null;
@endphp
<section class="commerce-page">
    <div class="container commerce-shell">
        <div class="order-success-card">
            <div class="order-success-icon" aria-hidden="true">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 4 4L19 6"/></svg>
            </div>
            <span class="eyebrow">Pesanan tercatat</span>
            <h1>Pesananmu berhasil dicatat, {{ ucwords(strtolower($order->customer_name)) }}!</h1>
            <p>Kami sedang memproses pesananmu. Catat nomor pesanan di bawah ini sebagai referensi.</p>
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
                    <strong>Selesaikan pembayaran</strong>
                    <p>Klik tombol di bawah untuk mengirim detail pesananmu ke WhatsApp Admin Agrinova dan mendapatkan instruksi pembayaran.</p>
                    @if ($whatsappUrl)
                        <a href="{{ $whatsappUrl }}" class="order-whatsapp-button" target="_blank" rel="noopener">
                            <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2a9.96 9.96 0 0 0-8.58 15.08L2 22l5.08-1.42A9.96 9.96 0 1 0 12.04 2Zm0 18.18a8.2 8.2 0 0 1-4.18-1.14l-.3-.18-3.02.84.85-2.94-.2-.3a8.2 8.2 0 1 1 6.85 3.72Zm4.5-6.15c-.25-.13-1.48-.73-1.71-.82-.23-.08-.4-.13-.57.13-.17.25-.65.82-.8.99-.15.17-.3.19-.55.06-.25-.13-1.04-.38-1.98-1.21-.73-.65-1.22-1.45-1.36-1.7-.14-.25-.01-.39.11-.52.11-.11.25-.3.38-.44.13-.15.17-.25.25-.42.08-.17.04-.32-.02-.44-.06-.13-.57-1.36-.78-1.86-.21-.49-.42-.42-.57-.43h-.49c-.17 0-.44.06-.67.32-.23.25-.88.86-.88 2.09s.9 2.42 1.03 2.59c.13.17 1.77 2.71 4.29 3.8.6.26 1.07.41 1.43.53.6.19 1.14.16 1.57.1.48-.07 1.48-.61 1.69-1.2.21-.59.21-1.1.15-1.2-.06-.1-.23-.16-.48-.29Z"/></svg>
                            Hubungi Admin via WhatsApp
                        </a>
                    @else
                        <p class="order-next-step-warning">Kontak WhatsApp Admin belum dikonfigurasi. Silakan hubungi Agrinova melalui kontak resmi.</p>
                    @endif
                </div>
            @endif
            <div class="order-success-actions">
                @auth
                    <a href="{{ route('profile.show') }}" class="catalog-action">Cek status pesanan</a>
                @endauth
                <a href="{{ route('catalog') }}" class="catalog-action catalog-action--secondary">Kembali ke katalog</a>
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

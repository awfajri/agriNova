@extends('layouts.app')

@section('title', 'Keranjang Belanja | Agrinova')
@section('meta_description', 'Periksa produk pilihanmu sebelum melanjutkan pemesanan di Agrinova.')
@section('body_class', 'has-cart page-mobile-subpage')

@section('content')
@php
    $formattedSubtotal = 'Rp' . number_format((float) $subtotal, 0, ',', '.');
    $checkoutCtaLabel = auth()->check() ? 'Lanjut ke pemesanan' : 'Masuk & lanjutkan pesanan';

    $preorderItem = $items->first(fn ($item) => (bool) ($item['product']->is_preorder ?? false));
    $harvestEstimateText = $preorderItem && ! empty($preorderItem['product']->harvest_estimate)
        ? \Carbon\Carbon::parse($preorderItem['product']->harvest_estimate)->translatedFormat('d M Y')
        : null;
@endphp

<section class="agr-cart-page">
    <div class="agr-cart-container">
        {{-- Mobile Top Bar (< 768px) --}}
        <header class="agr-cart-mobile-topbar">
            <a href="{{ route('catalog') }}" class="agr-cart-back" aria-label="Kembali ke katalog">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M11 6l-6 6 6 6"/>
                </svg>
            </a>
            <h1 class="agr-cart-mobile-title">Keranjang</h1>
        </header>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="catalog-alert catalog-alert--success" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="catalog-alert catalog-alert--error" role="alert">{{ session('error') }}</div>
        @endif
        @if ($errors->has('cart'))
            <div class="catalog-alert catalog-alert--error" role="alert">{{ $errors->first('cart') }}</div>
        @endif

        @if ($items->isEmpty())
            <div class="commerce-empty">
                <div class="commerce-empty-icon" aria-hidden="true">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <path d="M3 6h18"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>
                <h2>Keranjangmu masih kosong</h2>
                <p>Pilih hasil panen atau perlengkapan kebun Agrinova yang ingin kamu pesan.</p>
                <a href="{{ route('catalog') }}" class="catalog-action">Mulai belanja</a>
            </div>
        @else
            <h2 class="agr-cart-section-title">Pesanan pilihanmu</h2>

            {{-- Daftar Item Keranjang --}}
            <form id="cart-update-form" method="POST" action="{{ route('cart.update') }}" class="agr-cart-items">
                @csrf
                @method('PATCH')

                @foreach ($items as $item)
                    @php
                        $product = $item['product'];
                        $cartImage = $product->image_url ?: asset('images/sayursegar.webp');
                        $unitLabel = $product->unit ?? 'kg';
                        $minQty = max(1, (int) $product->min_order_quantity);
                        $maxQty = max(1, (int) $product->stock);
                    @endphp

                    <article class="agr-cart-card {{ ! $item['available'] ? 'is-unavailable' : '' }}">
                        <a href="{{ route('products.show', $product) }}" class="agr-cart-thumb">
                            <img src="{{ $cartImage }}" alt="{{ $product->name }}">
                        </a>

                        <div class="agr-cart-card-body">
                            <div class="agr-cart-card-top">
                                <span class="agr-cart-tag">
                                    {{ $product->is_preorder ? 'Pre-order' : ($product->category->name ?? 'Siap panen') }}
                                </span>
                                <button type="submit"
                                        form="remove-{{ $product->id }}"
                                        class="agr-cart-remove-btn"
                                        aria-label="Hapus {{ $product->name }}">
                                    Hapus
                                </button>
                            </div>

                            <h3>
                                <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                            </h3>

                            <p class="agr-cart-unit-price">
                                Rp{{ number_format((float) $product->price, 0, ',', '.') }} / {{ $unitLabel }}
                            </p>

                            <div class="agr-cart-qty-line">
                                <span>Jumlah: {{ $item['quantity'] }} {{ $unitLabel }}</span>
                                <div class="agr-cart-qty-box">
                                    <label for="quantity-{{ $product->id }}" class="visually-hidden">Ubah jumlah {{ $product->name }}</label>
                                    <input id="quantity-{{ $product->id }}"
                                           type="number"
                                           name="quantities[{{ $product->id }}]"
                                           value="{{ $item['quantity'] }}"
                                           min="{{ $minQty }}"
                                           max="{{ $maxQty }}"
                                           onchange="this.form.submit()"
                                           required>
                                </div>
                            </div>

                            @if (! $item['available'])
                                <p class="agr-cart-warning">Stok berubah. Sesuaikan jumlah sebelum checkout.</p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </form>

            @foreach ($items as $item)
                <form id="remove-{{ $item['product']->id }}" method="POST" action="{{ route('cart.destroy', $item['product']) }}" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach

            {{-- Info Pengambilan & Estimasi Panen --}}
            <div class="agr-cart-info-box">
                <div class="agr-cart-info-head">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                    <strong>
                        @if ($harvestEstimateText)
                            Estimasi panen: {{ $harvestEstimateText }}
                        @else
                            Pengambilan langsung di kebun
                        @endif
                    </strong>
                </div>
                <p>
                    Khusus ambil di kebun (Senin&ndash;Minggu, 08.00&ndash;16.00 WIB). Jadwal pasti dikonfirmasi admin via WhatsApp.
                </p>
            </div>

            {{-- Ringkasan Belanja --}}
            <div class="agr-cart-summary">
                <h2 class="agr-cart-section-title">Ringkasan belanja</h2>
                <div class="agr-cart-summary-row">
                    <span>Subtotal &middot; {{ $items->count() }} produk</span>
                    <strong>{{ $formattedSubtotal }}</strong>
                </div>
                <div class="agr-cart-summary-row">
                    <span>Pengambilan di kebun</span>
                    <strong>Gratis</strong>
                </div>
            </div>

            {{-- Sticky Bottom Bar --}}
            <div class="agr-cart-bottom-bar">
                <div class="agr-cart-bottom-inner">
                    <div class="agr-cart-bottom-total">
                        <span>Total</span>
                        <strong>{{ $formattedSubtotal }}</strong>
                    </div>

                    <p class="agr-cart-bottom-hint">
                        @guest
                            Masuk cepat 1 klik dengan Google untuk mengunci slot panenmu.
                        @else
                            Lanjutkan ke detail pemesanan untuk mengunci slot panenmu.
                        @endguest
                    </p>

                    <a href="{{ route('checkout.create') }}" class="agr-cart-primary-btn">
                        {{ $checkoutCtaLabel }}
                    </a>

                    <a href="{{ route('catalog') }}" class="agr-cart-secondary-btn">
                        Lanjut belanja
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
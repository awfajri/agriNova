@extends('layouts.app')

@section('title', 'Keranjang Belanja | Agrinova')
@section('meta_description', 'Periksa produk pilihanmu sebelum melanjutkan checkout di Agrinova.')
@section('body_class', 'has-cart')

@section('content')
<section class="commerce-page">
    <div class="container commerce-shell">
        <div class="commerce-heading">
            <span class="eyebrow">Belanja Agrinova</span>
            <h1>Keranjang belanja</h1>
            <p>Periksa jumlah produk sebelum lanjut ke checkout.</p>
        </div>

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
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
                <h2>Keranjangmu masih kosong</h2>
                <p>Pilih produk Agrinova yang ingin kamu pesan.</p>
                <a href="{{ route('catalog') }}" class="catalog-action">Mulai belanja</a>
            </div>
        @else
            <div class="commerce-layout">
                <form method="POST" action="{{ route('cart.update') }}" class="cart-list">
                    @csrf
                    @method('PATCH')
                    @foreach ($items as $item)
                        <article class="cart-item {{ ! $item['available'] ? 'cart-item--unavailable' : '' }}">
                            <a href="{{ route('products.show', $item['product']) }}" class="cart-item-media {{ $item['product']->image_url ? '' : 'catalog-product-media--placeholder' }}">
                                @if ($item['product']->image_url)
                                    <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}">
                                @else
                                    <span>{{ $item['product']->category->name ?? 'Produk' }}</span>
                                @endif
                            </a>
                            <div class="cart-item-copy">
                                <span class="product-category">{{ $item['product']->category->name ?? 'Produk' }}</span>
                                <h2><a href="{{ route('products.show', $item['product']) }}">{{ $item['product']->name }}</a></h2>
                                <span>Rp {{ number_format((float) $item['product']->price, 0, ',', '.') }} / unit</span>
                                @if (! $item['available'])
                                    <strong class="cart-item-warning">Stok berubah. Sesuaikan jumlah sebelum checkout.</strong>
                                @endif
                            </div>
                            <div class="cart-item-controls">
                                <label for="quantity-{{ $item['product']->id }}">Jumlah</label>
                                <input id="quantity-{{ $item['product']->id }}" type="number" name="quantities[{{ $item['product']->id }}]" value="{{ $item['quantity'] }}" min="{{ max(1, $item['product']->min_order_quantity) }}" max="{{ max(1, $item['product']->stock) }}" required>
                                <strong>Rp {{ number_format($item['line_total'], 0, ',', '.') }}</strong>
                            </div>
                            <button type="submit" form="remove-{{ $item['product']->id }}" class="cart-remove">Hapus</button>
                        </article>
                    @endforeach
                    <div class="cart-list-actions">
                        <a href="{{ route('catalog') }}" class="catalog-action catalog-action--secondary">Lanjut belanja</a>
                        <button type="submit" class="catalog-action">Perbarui keranjang</button>
                    </div>
                </form>

                @foreach ($items as $item)
                    <form id="remove-{{ $item['product']->id }}" method="POST" action="{{ route('cart.destroy', $item['product']) }}" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach

                <aside class="commerce-summary">
                    <h2>Ringkasan pesanan</h2>
                    <div class="commerce-summary-row">
                        <span>Subtotal</span>
                        <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                    </div>
                    <div class="commerce-summary-row commerce-summary-row--muted">
                        <span>Ongkos kirim</span>
                        <span>Dikonfirmasi admin</span>
                    </div>
                    <div class="commerce-summary-total">
                        <span>Total sementara</span>
                        <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                    </div>
                    <p class="commerce-summary-note">Kamu belum perlu membuat akun. Siapkan nama dan nomor WhatsApp untuk melanjutkan.</p>
                    <a href="{{ route('checkout.create') }}" class="commerce-checkout-button">Lanjut checkout</a>
                </aside>
            </div>
        @endif
    </div>
</section>
@endsection

@extends('layouts.app')

@section('title', $product->name . ' | Agrinova')
@section('meta_description', $product->short_description ?: 'Pesan ' . $product->name . ' segar langsung dari kebun Agrinova.')
@section('body_class', 'has-product-detail page-mobile-subpage')

@section('content')
@php
    $cartCount = array_sum(session('cart', []));
    $productImage = $product->image_url ?: asset('images/sayursegar.webp');
    $unitLabel = $product->unit ?? 'kg';
    $minQty = max(1, (int) $product->min_order_quantity);
    $maxQty = max($minQty, (int) $product->stock);
    $isAvailable = (int) $product->stock >= $minQty;
    $initialTotal = (float) $product->price * $minQty;

    $harvestEstimate = ! empty($product->harvest_estimate)
        ? \Carbon\Carbon::parse($product->harvest_estimate)->translatedFormat('d M Y')
        : null;
@endphp

<section class="agr-pdp-page">
    <div class="agr-pdp-container">
        {{-- Mobile Top Bar (< 768px) --}}
        <header class="agr-pdp-mobile-topbar">
            <a href="{{ route('catalog') }}" class="agr-pdp-icon-btn" aria-label="Kembali ke katalog">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M11 6l-6 6 6 6"/>
                </svg>
            </a>
            <h1 class="agr-pdp-mobile-title">Detail produk</h1>
            <a href="{{ route('cart') }}" class="agr-pdp-cart-btn" aria-label="Buka keranjang">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                    <path d="M3 4h2l2.2 11.1a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.5L21 8H6"/>
                    <circle cx="10" cy="20" r="1.25"/><circle cx="18" cy="20" r="1.25"/>
                </svg>
                @if ($cartCount > 0)
                    <span class="agr-pdp-cart-badge">{{ $cartCount }}</span>
                @endif
            </a>
        </header>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="catalog-alert catalog-alert--success" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="catalog-alert catalog-alert--error" role="alert">{{ $errors->first() }}</div>
        @endif

        {{-- Hero Product Photo Card --}}
        <div class="agr-pdp-hero-card">
            <img src="{{ $productImage }}" alt="{{ $product->name }}">
            <span class="agr-pdp-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
                @if ($product->is_preorder)
                    Pre-order{{ $product->preorder_lead_days ? ' · H-' . $product->preorder_lead_days : '' }}
                @else
                    {{ $isAvailable ? 'Siap panen' : 'Stok habis' }}
                @endif
            </span>
        </div>

        {{-- Title, Price & Description --}}
        <div class="agr-pdp-head">
            <h2>{{ $product->name }}</h2>
            <div class="agr-pdp-price">
                <strong>Rp{{ number_format((float) $product->price, 0, ',', '.') }}</strong>
                <span>/ {{ $unitLabel }}</span>
            </div>
            <p class="agr-pdp-desc">
                {{ $product->description ?: ($product->short_description ?: 'Hasil budidaya segar dari rumah tanam Agrinova, dipanen sesuai jadwal.') }}
            </p>
        </div>

        {{-- Info Box: Estimasi Panen & Pengambilan di Kebun --}}
        <div class="agr-pdp-info-box">
            <div class="agr-pdp-info-head">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
                <strong>
                    @if ($harvestEstimate)
                        Estimasi panen: {{ $harvestEstimate }}
                    @elseif ($product->is_preorder)
                        Pre-order panen berikutnya
                    @else
                        Siap panen &middot; Stok {{ $product->stock }} {{ $unitLabel }}
                    @endif
                </strong>
            </div>
            <p>
                Khusus ambil di kebun (Senin&ndash;Minggu, 08.00&ndash;16.00 WIB). Jadwal pasti dikonfirmasi admin via WhatsApp.
            </p>
        </div>

        {{-- Form Order & Stepper Jumlah --}}
        <form id="pdp-order-form" method="POST" action="{{ route('cart.store') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" id="pdp-qty-input" name="quantity" value="{{ $minQty }}">
            <input type="hidden" id="pdp-buy-now-input" name="buy_now" value="1">

            <div class="agr-pdp-qty-row">
                <div>
                    <strong class="agr-pdp-qty-label">Jumlah pesanan</strong>
                    <span class="agr-pdp-qty-sub">Minimum {{ $minQty }} {{ $unitLabel }}</span>
                </div>

                <div class="agr-pdp-stepper"
                     data-min="{{ $minQty }}"
                     data-max="{{ $maxQty }}"
                     data-unit="{{ $unitLabel }}"
                     data-price="{{ (float) $product->price }}">
                    <button type="button" id="pdp-qty-minus" aria-label="Kurangi jumlah">&minus;</button>
                    <span id="pdp-qty-display">{{ $minQty }} {{ $unitLabel }}</span>
                    <button type="button" id="pdp-qty-plus" aria-label="Tambah jumlah">+</button>
                </div>
            </div>

            <p class="agr-pdp-payment-note">
                Pembayaran via QRIS &bull; Wajib masuk akun Google saat checkout.
            </p>

            {{-- Sticky Bottom Bar --}}
            <div class="agr-pdp-bottom-bar">
                <div class="agr-pdp-bottom-inner">
                    <div class="agr-pdp-bottom-total">
                        <span id="pdp-bottom-summary">Total &middot; {{ $minQty }} {{ $unitLabel }}</span>
                        <strong id="pdp-bottom-price">Rp{{ number_format($initialTotal, 0, ',', '.') }}</strong>
                    </div>

                    <div class="agr-pdp-bottom-actions">
                        <button type="button"
                                id="pdp-add-cart-btn"
                                class="agr-pdp-cart-add-btn"
                                aria-label="Tambah ke keranjang"
                                {{ ! $isAvailable ? 'disabled' : '' }}>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20" aria-hidden="true">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                <path d="M3 6h18"/>
                                <path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </button>

                        <button type="submit"
                                id="pdp-buy-now-btn"
                                class="agr-pdp-primary-btn"
                                {{ ! $isAvailable ? 'disabled' : '' }}>
                            {{ $isAvailable ? 'Pesan sekarang' : 'Stok habis' }}
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Section: Ulasan & Komentar Pembeli --}}
        <section class="agr-pdp-reviews" aria-labelledby="pdp-reviews-heading">
            <div class="agr-pdp-reviews-head">
                <div>
                    <h3 id="pdp-reviews-heading">Ulasan pembeli</h3>
                    <p>
                        @if ($reviewCount > 0)
                            <strong>&#9733; {{ number_format($averageRating, 1) }}</strong> / 5.0 ({{ $reviewCount }} ulasan)
                        @else
                            Belum ada ulasan untuk produk ini
                        @endif
                    </p>
                </div>
            </div>

            {{-- Form Tulis Ulasan (Hanya untuk pembeli terverifikasi) --}}
            @if ($reviewableOrder)
                <form method="POST" action="{{ route('products.reviews.store', $product) }}" class="agr-pdp-review-form">
                    @csrf
                    <strong>{{ $existingReview ? 'Perbarui ulasanmu' : 'Bagikan pengalamanmu' }}</strong>

                    <div class="agr-pdp-rating-select">
                        <label for="review-rating">Rating</label>
                        <select id="review-rating" name="rating" required>
                            @for ($star = 5; $star >= 1; $star--)
                                <option value="{{ $star }}" @selected((int) old('rating', $existingReview->rating ?? 5) === $star)>
                                    {{ $star }} Bintang {{ str_repeat('★', $star) }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="agr-pdp-comment-field">
                        <label for="review-comment" class="visually-hidden">Komentar ulasan</label>
                        <textarea id="review-comment"
                                  name="comment"
                                  rows="3"
                                  placeholder="Ceritakan kesegaran produk atau pengalaman ambil di kebun...">{{ old('comment', $existingReview->comment ?? '') }}</textarea>
                    </div>

                    <button type="submit" class="agr-pdp-review-submit">
                        {{ $existingReview ? 'Simpan perubahan' : 'Kirim ulasan' }}
                    </button>
                </form>
            @endif

            {{-- Daftar Ulasan --}}
            @if ($reviews->isEmpty())
                <div class="agr-pdp-review-empty">
                    Ulasan akan muncul setelah pembeli menyelesaikan pesanan produk ini.
                </div>
            @else
                <div class="agr-pdp-review-list">
                    @foreach ($reviews as $review)
                        <article class="agr-pdp-review-card">
                            <div class="agr-pdp-review-top">
                                <div class="agr-pdp-reviewer">
                                    @if ($review->user?->avatar_url)
                                        <img src="{{ $review->user->avatar_url }}" alt="" referrerpolicy="no-referrer">
                                    @else
                                        <span class="agr-pdp-reviewer-initial">
                                            {{ mb_strtoupper(mb_substr($review->user->name ?? 'P', 0, 1)) }}
                                        </span>
                                    @endif
                                    <div>
                                        <strong>{{ $review->user->name ?? 'Pelanggan Agrinova' }}</strong>
                                        <span>{{ $review->created_at?->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <span class="agr-pdp-stars" aria-label="{{ $review->rating }} dari 5 bintang">
                                    {{ str_repeat('★', (int) $review->rating) }}
                                </span>
                            </div>

                            @if (filled($review->comment))
                                <p class="agr-pdp-review-comment">{{ $review->comment }}</p>
                            @endif

                            @if (filled($review->admin_reply))
                                <div class="agr-pdp-admin-reply" style="margin-top: 10px; padding: 10px 12px; background-color: #f2f7f5; border-left: 3px solid #236e59; border-radius: 8px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 6px; margin-bottom: 4px;">
                                        <div style="display: flex; align-items: center; gap: 6px;">
                                            <span style="background-color: #236e59; color: #ffffff; font-size: 10px; padding: 1px 6px; border-radius: 4px; font-weight: 700;">Admin</span>
                                            <strong style="font-size: 12px; color: #11332b;">Balasan Toko Agrinova</strong>
                                        </div>
                                        <span style="font-size: 11px; color: #4a635d;">{{ $review->admin_reply_at?->diffForHumans() }}</span>
                                    </div>
                                    <p style="margin: 0; font-size: 13px; line-height: 1.45; color: #2d5248;">{{ $review->admin_reply }}</p>
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</section>
@endsection
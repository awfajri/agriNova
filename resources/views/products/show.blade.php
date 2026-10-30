@extends('layouts.app')

@section('title', $product->name . ' | Agrinova')
@section('meta_description', $product->short_description ?: 'Detail produk ' . $product->name . ' dari Agrinova.')
@section('body_class', 'has-product-detail')

@section('content')
<section class="product-detail-page">
    <div class="container">
        <nav class="breadcrumb-nav" aria-label="Breadcrumb">
            <a href="{{ route('catalog') }}">Katalog</a>
            <span>/</span>
            <span>{{ $product->name }}</span>
        </nav>

        @if (session('success'))
            <div class="catalog-alert catalog-alert--success" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->has('cart'))
            <div class="catalog-alert catalog-alert--error" role="alert">{{ $errors->first('cart') }}</div>
        @endif

        <div class="product-detail-grid">
            <div class="product-detail-media {{ $product->image_url ? '' : 'product-detail-media--placeholder' }}">
                @if ($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                @else
                    <span>{{ $product->category->name ?? 'Produk Agrinova' }}</span>
                @endif
                @if ($product->is_preorder)
                    <span class="product-badge product-badge--preorder">Pre-order</span>
                @endif
            </div>

            <div class="product-detail-copy">
                <span class="product-category">{{ $product->category->name ?? 'Produk' }}</span>
                <h1>{{ $product->name }}</h1>
                <div class="product-detail-price">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</div>
                <p class="product-detail-description">{{ $product->description ?: $product->short_description ?: 'Deskripsi produk akan diperbarui oleh admin.' }}</p>

                <div class="product-detail-status">
                    <span class="{{ $product->stock >= $product->min_order_quantity ? 'is-available' : 'is-unavailable' }}">
                        {{ $product->stock >= $product->min_order_quantity ? 'Tersedia untuk dipesan' : 'Stok sedang habis' }}
                    </span>
                    @if ($product->is_preorder)
                        <span>Pre-order panen</span>
                    @endif
                </div>

                @if ($product->stock >= $product->min_order_quantity)
                    <form method="POST" action="{{ route('cart.store') }}" class="product-buy-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <label for="product-quantity">Jumlah</label>
                        <div class="product-buy-controls">
                            <input id="product-quantity" name="quantity" type="number" min="{{ $product->min_order_quantity }}" max="{{ $product->stock }}" value="{{ $product->min_order_quantity }}" required>
                            <button type="submit" class="catalog-add-button">Tambah ke keranjang</button>
                        </div>
                        <small>Maksimal {{ $product->stock }} unit tersedia saat ini.</small>
                    </form>
                @else
                    <p class="product-unavailable-note">Produk ini belum bisa dipesan. Silakan cek kembali nanti.</p>
                @endif
            </div>
        </div>

        <section class="product-reviews" aria-labelledby="reviews-title">
            <div class="product-reviews-head">
                <div>
                    <span class="eyebrow">Pengalaman pembeli</span>
                    <h2 id="reviews-title">Rating dan komentar</h2>
                </div>
                <div class="product-rating-summary">
                    <strong>{{ $reviewCount > 0 ? number_format($averageRating, 1) : '—' }}</strong>
                    <span aria-label="{{ $reviewCount > 0 ? number_format($averageRating, 1) . ' dari 5' : 'Belum ada rating' }}">★</span>
                    <small>{{ $reviewCount }} ulasan</small>
                </div>
            </div>

            @if ($errors->has('review'))
                <div class="catalog-alert catalog-alert--error" role="alert">{{ $errors->first('review') }}</div>
            @endif

            @auth
                @if ($reviewableOrder)
                    <form method="POST" action="{{ route('products.reviews.store', $product) }}" class="product-review-form">
                        @csrf
                        <div>
                            <label for="review-rating">Rating kamu</label>
                            <select id="review-rating" name="rating" required>
                                @for ($rating = 5; $rating >= 1; $rating--)
                                    <option value="{{ $rating }}" @selected((int) old('rating', $existingReview?->rating ?? 5) === $rating)>{{ $rating }} dari 5</option>
                                @endfor
                            </select>
                        </div>
                        <div class="product-review-comment">
                            <label for="review-comment">Komentar <span>(opsional)</span></label>
                            <textarea id="review-comment" name="comment" rows="3" maxlength="1000" placeholder="Bagaimana pengalamanmu dengan produk ini?">{{ old('comment', $existingReview?->comment) }}</textarea>
                        </div>
                        <button type="submit" class="catalog-action">{{ $existingReview ? 'Perbarui ulasan' : 'Kirim ulasan' }}</button>
                    </form>
                @else
                    <p class="product-review-note">Beli dan selesaikan pembayaran produk ini untuk memberikan rating dan komentar.</p>
                @endif
            @else
                <p class="product-review-note">Login dengan Google setelah membeli produk ini untuk memberikan rating dan komentar.</p>
            @endauth

            @if ($reviews->isNotEmpty())
                <div class="product-review-list">
                    @foreach ($reviews as $review)
                        <article class="product-review">
                            <div class="product-review-meta">
                                <strong>{{ $review->user->name }}</strong>
                                <span>{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                            </div>
                            @if ($review->comment)
                                <p>{{ $review->comment }}</p>
                            @endif
                            <small>{{ $review->created_at->translatedFormat('d F Y') }}</small>
                        </article>
                    @endforeach
                </div>
            @else
                <p class="product-review-note">Belum ada ulasan untuk produk ini.</p>
            @endif
        </section>

        @if ($relatedProducts->isNotEmpty())
            <div class="related-products">
                <div class="catalog-results-head">
                    <div>
                        <span class="eyebrow">Masih satu kategori</span>
                        <h2>Produk lainnya</h2>
                    </div>
                    <a href="{{ route('catalog', ['category' => $product->category?->slug]) }}" class="related-products-label">Lihat semua produk kategori</a>
                </div>
                <div class="catalog-product-grid">
                    @foreach ($relatedProducts as $related)
                        <article class="catalog-product-card">
                            <a href="{{ route('products.show', $related) }}" class="catalog-product-link">
                                <div class="catalog-product-media {{ $related->image_url ? '' : 'catalog-product-media--placeholder' }}">
                                    @if ($related->image_url)
                                        <img src="{{ $related->image_url }}" alt="{{ $related->name }}" loading="lazy">
                                    @else
                                        <span>{{ $related->category->name ?? 'Produk Agrinova' }}</span>
                                    @endif
                                </div>
                                <div class="catalog-product-body">
                                    <span class="product-category">{{ $related->category->name ?? 'Produk' }}</span>
                                    <h3>{{ $related->name }}</h3>
                                    <div class="catalog-product-price">
                                        <strong>Rp {{ number_format((float) $related->price, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

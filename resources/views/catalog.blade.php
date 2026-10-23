@extends('layouts.app')

@section('title', 'Belanja Produk Agrinova | Kebun Melon & Sayur Segar')
@section('meta_description', 'Belanja melon, sayur segar, perlengkapan kebun, dan pupuk dari Agrinova. Cari produk, pilih kategori, dan pesan langsung secara online.')
@section('body_class', 'has-catalog')

@section('content')
<section class="catalog-page">
    <div class="container">
        <div class="catalog-slider" data-catalog-slider tabindex="0" aria-label="Banner Agrinova">
            <div class="catalog-slider-viewport">
                <div class="catalog-slider-track" data-catalog-slider-track>
                    <div class="catalog-slide" data-catalog-slide>
                        <img src="{{ asset('images/banner-1.jpg') }}" alt="Suasana Agrinova Farm" draggable="false">
                        <div class="catalog-slide-copy">
                            <h1>Segar langsung dari kebun.</h1>
                            <p>Pilih hasil panen dan kebutuhan kebun untuk rumahmu.</p>
                        </div>
                    </div>
                    <div class="catalog-slide" data-catalog-slide>
                        <img src="{{ asset('images/banner-2.jpg') }}" alt="Hasil panen Agrinova" draggable="false">
                        <div class="catalog-slide-copy">
                            <h1>Temukan produk pilihanmu.</h1>
                            <p>Belanja produk segar dan perlengkapan kebun dengan mudah.</p>
                        </div>
                    </div>
                    <div class="catalog-slide" data-catalog-slide>
                        <img src="{{ asset('images/banner-3.png') }}" alt="Produk segar Agrinova" draggable="false">
                        <div class="catalog-slide-copy">
                            <h1>Dari kebun untuk kebutuhan sehari-hari.</h1>
                            <p>Pesan produk Agrinova dalam satu alur belanja yang sederhana.</p>
                        </div>
                    </div>
                </div>
            </div>
            <button class="catalog-slider-control catalog-slider-control--prev" type="button" data-catalog-prev aria-label="Banner sebelumnya">
                <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <button class="catalog-slider-control catalog-slider-control--next" type="button" data-catalog-next aria-label="Banner berikutnya">
                <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </button>
            <div class="catalog-slider-dots" role="tablist" aria-label="Pilih banner">
                <button type="button" class="is-active" data-catalog-dot="0" role="tab" aria-selected="true" aria-label="Banner 1"></button>
                <button type="button" data-catalog-dot="1" role="tab" aria-selected="false" aria-label="Banner 2"></button>
                <button type="button" data-catalog-dot="2" role="tab" aria-selected="false" aria-label="Banner 3"></button>
            </div>
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

        <div class="catalog-toolbar">
            <form method="GET" action="{{ route('catalog') }}" class="catalog-search" role="search">
                <label class="visually-hidden" for="catalog-search">Cari produk</label>
                <svg class="catalog-search-icon" aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                <input id="catalog-search" name="q" type="search" value="{{ $search }}" placeholder="Cari melon, sayur, pupuk..." autocomplete="off">
                @if ($selectedCategory)
                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                @endif
                @if ($availability)
                    <input type="hidden" name="availability" value="{{ $availability }}">
                @endif
                @if ($sort)
                    <input type="hidden" name="sort" value="{{ $sort }}">
                @endif
                <button type="submit" class="catalog-search-submit" aria-label="Cari produk">
                    <svg aria-hidden="true" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                </button>
                <button type="button" class="catalog-filter-trigger" data-bs-toggle="modal" data-bs-target="#filterModal" aria-label="Buka filter produk">
                    <svg aria-hidden="true" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5h16M7 12h10M10 19h4"/></svg>
                </button>
            </form>
        </div>

        <div class="catalog-layout" id="daftar-produk">
            <div class="catalog-results">
                <div class="catalog-results-head">
                    <div>
                        <span class="eyebrow">Katalog Agrinova</span>
                        <h2>{{ $products->total() }} produk</h2>
                    </div>
                </div>

                <div class="catalog-product-grid">
                    @forelse ($products as $product)
                        <article class="catalog-product-card">
                            <a href="{{ route('products.show', $product) }}" class="catalog-product-link">
                                <div class="catalog-product-media {{ $product->image_url ? '' : 'catalog-product-media--placeholder' }}">
                                    @if ($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                                    @else
                                        <span>{{ $product->category->name ?? 'Produk Agrinova' }}</span>
                                    @endif
                                    @if ($product->is_preorder)
                                        <span class="product-badge product-badge--preorder">Pre-order</span>
                                    @endif
                                </div>
                                <div class="catalog-product-body">
                                    <span class="product-category">{{ $product->category->name ?? 'Kategori' }}</span>
                                    <h3>{{ $product->name }}</h3>
                                    <p>{{ $product->short_description ?: 'Informasi produk akan diperbarui oleh admin.' }}</p>
                                    <div class="catalog-product-price">
                                        <strong>Rp {{ number_format((float) $product->price, 0, ',', '.') }}</strong>
                                        <span>{{ $product->stock > 0 ? 'Tersedia' : 'Stok habis' }}</span>
                                    </div>
                                </div>
                            </a>
                            <div class="catalog-card-actions">
                                <form method="POST" action="{{ route('cart.store') }}" class="catalog-add-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="{{ max(1, $product->min_order_quantity) }}">
                                    <button type="submit" class="catalog-add-button catalog-add-button--icon" aria-label="Tambah {{ $product->name }} ke keranjang" {{ $product->stock < $product->min_order_quantity ? 'disabled' : '' }}>
                                        <svg aria-hidden="true" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('cart.store') }}" class="catalog-buy-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="{{ max(1, $product->min_order_quantity) }}">
                                    <input type="hidden" name="buy_now" value="1">
                                    <button type="submit" class="catalog-buy-button" {{ $product->stock < $product->min_order_quantity ? 'disabled' : '' }}>
                                        {{ $product->stock < $product->min_order_quantity ? 'Stok habis' : 'Beli sekarang' }}
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="catalog-empty">
                            <h2>Produk tidak ditemukan</h2>
                            <p>Coba gunakan kata kunci lain atau reset filter untuk melihat semua produk.</p>
                            <a href="{{ route('catalog') }}" class="catalog-action">Reset pencarian</a>
                        </div>
                    @endforelse
                </div>

                @if ($products->hasPages())
                    <div class="catalog-pagination">{{ $products->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</section>

<div class="modal fade filter-modal" id="filterModal" tabindex="-1" aria-labelledby="filterModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-bottom">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="eyebrow">Katalog</span>
                    <h2 id="filterModalTitle">Filter produk</h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form method="GET" action="{{ route('catalog') }}">
                <div class="modal-body">
                    @if ($search)<input type="hidden" name="q" value="{{ $search }}">@endif
                    <div class="modal-filter-section">
                        <h3>Kategori</h3>
                        <label><input type="radio" name="category" value="" {{ empty($selectedCategory) ? 'checked' : '' }}> Semua produk</label>
                        @foreach ($categories as $category)
                            <label><input type="radio" name="category" value="{{ $category->slug }}" {{ $selectedCategory === $category->slug ? 'checked' : '' }}> {{ $category->name }}</label>
                        @endforeach
                    </div>
                    <div class="modal-filter-section">
                        <h3>Ketersediaan</h3>
                        <label><input type="radio" name="availability" value="" {{ empty($availability) ? 'checked' : '' }}> Semua</label>
                        <label><input type="radio" name="availability" value="in_stock" {{ $availability === 'in_stock' ? 'checked' : '' }}> Tersedia</label>
                        <label><input type="radio" name="availability" value="preorder" {{ $availability === 'preorder' ? 'checked' : '' }}> Pre-order</label>
                    </div>
                    <div class="modal-filter-section">
                        <label for="catalog-sort">Urutkan</label>
                        <select id="catalog-sort" name="sort">
                            <option value="" {{ empty($sort) ? 'selected' : '' }}>Rekomendasi</option>
                            <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Harga terendah</option>
                            <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Harga tertinggi</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('catalog') }}" class="catalog-action catalog-action--secondary">Reset</a>
                    <button type="submit" class="catalog-action">Terapkan filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

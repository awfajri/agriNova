@extends('layouts.app')

@section('title', 'Belanja Produk Agrinova | Kebun Melon & Sayur Segar')
@section('meta_description', 'Belanja melon, sayur segar, perlengkapan kebun, dan pupuk dari Agrinova. Cari produk, pilih kategori, dan pesan langsung secara online.')
@section('body_class', 'has-catalog')

@section('content')
@php($cartCount = array_sum(session('cart', [])))
<section class="catalog-page">
    <div class="container">
        <div class="catalog-hero">
            <div class="catalog-hero-copy">
                <span class="eyebrow eyebrow--light">Belanja langsung dari Agrinova</span>
                <h1>Produk segar untuk rumah, usaha, dan kebunmu.</h1>
                <p>
                    Pilih produk, masukkan ke keranjang, lalu selesaikan pesanan tanpa pindah ke marketplace.
                    Data stok dan harga di halaman ini menjadi acuan saat checkout.
                </p>
                <a href="#daftar-produk" class="btn-pill btn-pill--light">
                    Lihat semua produk
                    <span class="btn-pill-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                    </span>
                </a>
            </div>
            <div class="catalog-hero-note">
                <span class="catalog-hero-note-icon" aria-hidden="true">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18M3 12h18"/><path d="M5.5 8.5c2.4-1.9 4.7-1.9 6.5 0M12 15.5c2.4 1.9 4.7 1.9 6.5 0"/></svg>
                </span>
                <strong>Pesan dengan alur singkat</strong>
                <span>Produk &rarr; Keranjang &rarr; Checkout</span>
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
                <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
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
                <button type="submit">Cari</button>
            </form>

            <a href="{{ route('cart') }}" class="catalog-cart-link">
                <svg aria-hidden="true" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                Keranjang
                @if (($cartCount ?? 0) > 0)
                    <span>{{ $cartCount }}</span>
                @endif
            </a>
        </div>

        <div class="catalog-layout" id="daftar-produk">
            <aside class="catalog-filters" aria-label="Filter produk">
                <div class="catalog-filter-head">
                    <span class="eyebrow">Filter</span>
                    @if ($search || $selectedCategory || $availability || $sort)
                        <a href="{{ route('catalog') }}">Reset</a>
                    @endif
                </div>

                <div class="catalog-filter-group">
                    <h2>Kategori</h2>
                    <a href="{{ route('catalog', array_filter(['q' => $search, 'availability' => $availability, 'sort' => $sort])) }}" class="{{ empty($selectedCategory) ? 'active' : '' }}">
                        Semua produk <span>{{ $categories->sum('products_count') }}</span>
                    </a>
                    @foreach ($categories as $category)
                        <a href="{{ route('catalog', array_filter(['q' => $search, 'category' => $category->slug, 'availability' => $availability, 'sort' => $sort])) }}" class="{{ $selectedCategory === $category->slug ? 'active' : '' }}">
                            {{ $category->name }} <span>{{ $category->products_count }}</span>
                        </a>
                    @endforeach
                </div>

                <div class="catalog-filter-group">
                    <h2>Ketersediaan</h2>
                    <a href="{{ route('catalog', array_filter(['q' => $search, 'category' => $selectedCategory, 'sort' => $sort])) }}" class="{{ empty($availability) ? 'active' : '' }}">Semua</a>
                    <a href="{{ route('catalog', array_filter(['q' => $search, 'category' => $selectedCategory, 'availability' => 'in_stock', 'sort' => $sort])) }}" class="{{ $availability === 'in_stock' ? 'active' : '' }}">Tersedia</a>
                    <a href="{{ route('catalog', array_filter(['q' => $search, 'category' => $selectedCategory, 'availability' => 'preorder', 'sort' => $sort])) }}" class="{{ $availability === 'preorder' ? 'active' : '' }}">Pre-order</a>
                </div>
            </aside>

            <div class="catalog-results">
                <div class="catalog-results-head">
                    <div>
                        <span class="eyebrow">Katalog Agrinova</span>
                        <h2>{{ $products->total() }} produk</h2>
                    </div>
                    <form method="GET" action="{{ route('catalog') }}" class="catalog-sort">
                        @if ($search)<input type="hidden" name="q" value="{{ $search }}">@endif
                        @if ($selectedCategory)<input type="hidden" name="category" value="{{ $selectedCategory }}">@endif
                        @if ($availability)<input type="hidden" name="availability" value="{{ $availability }}">@endif
                        <label for="catalog-sort">Urutkan</label>
                        <select id="catalog-sort" name="sort" onchange="this.form.submit()">
                            <option value="" {{ empty($sort) ? 'selected' : '' }}>Rekomendasi</option>
                            <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Harga terendah</option>
                            <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Harga tertinggi</option>
                        </select>
                    </form>
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
                            <form method="POST" action="{{ route('cart.store') }}" class="catalog-add-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="{{ max(1, $product->min_order_quantity) }}">
                                <button type="submit" class="catalog-add-button" {{ $product->stock < $product->min_order_quantity ? 'disabled' : '' }}>
                                    {{ $product->stock < $product->min_order_quantity ? 'Stok habis' : 'Tambah ke keranjang' }}
                                </button>
                            </form>
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
@endsection

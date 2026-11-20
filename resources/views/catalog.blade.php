@extends('layouts.app')

@section('title', 'Belanja Produk Agrinova | Kebun Melon & Sayur Segar')
@section('meta_description', 'Belanja melon, sayur segar, perlengkapan kebun, dan pupuk dari Agrinova. Booking panen dan ambil langsung di kebun.')
@section('body_class', 'has-catalog agr-store-body')

@section('content')
@php
    $waNumber = preg_replace('/[^0-9]/', '', config('agrinova.whatsapp_number', '6285156287360'));
    $waGeneralUrl = "https://wa.me/{$waNumber}?text=" . urlencode('Halo Admin Agrinova, saya ingin bertanya seputar produk & jadwal panen.');
    $mapsUrl = config('agrinova.maps_url', 'https://maps.google.com/?q=Agrinova+Farm');

    $bannerImage = \App\Models\SiteSetting::get('catalog_banner_image', 'images/banner-1.jpg');
    $bannerCtaText = \App\Models\SiteSetting::get('catalog_banner_cta', 'Booking Melon Sekarang');
    $bannerCtaUrl = \App\Models\SiteSetting::get('catalog_banner_url', '');
    $heroActionUrl = filled($bannerCtaUrl)
        ? url($bannerCtaUrl)
        : route('catalog', ['availability' => 'preorder']) . '#hasil-katalog';

    $batchTitle = $activePreOrder?->title ?? 'Pre-Order Panen Batch #12';
    $harvestFormatted = $activePreOrder?->harvest_date
        ? \Carbon\Carbon::parse($activePreOrder->harvest_date)->translatedFormat('d M')
        : '14 Des';
    $deadlineTimestamp = $activePreOrder?->booking_close_at
        ? \Carbon\Carbon::parse($activePreOrder->booking_close_at)->timestamp * 1000
        : now()->addDays(2)->addHours(8)->addMinutes(30)->timestamp * 1000;
@endphp

<section class="agr-catalog-page">
    <div class="container agr-catalog-container">
        {{-- 1. Fulfillment Banner --}}
        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" class="agr-pickup-card">
            <div class="agr-pickup-card-copy">
                <strong>Khusus Ambil di Kebun Agrinova</strong>
                <span>Lihat Lokasi di Maps</span>
            </div>
            <span class="agr-pickup-card-arrow" aria-hidden="true">&rarr;</span>
        </a>

       {{-- 2. Hero Pre-Order Card --}}
        <div class="agr-hero-banner" style="--hero-bg: url('{{ asset($bannerImage) }}');">
            <div class="agr-hero-overlay">
                <span class="agr-hero-eyebrow">{{ $batchTitle }}</span>
                <h1>Booking sekarang, panen segar {{ $harvestFormatted }}.</h1>
                <p class="agr-hero-countdown" id="catalog-preorder-countdown" data-deadline="{{ $deadlineTimestamp }}">
                    Menghitung waktu...
                </p>
                <a href="{{ $heroActionUrl }}" class="agr-hero-cta">
                    <span>{{ $bannerCtaText }}</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>

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

        {{-- 3. Horizontal Category Pills --}}
        <nav class="agr-cat-pills" aria-label="Kategori produk">
            <a href="{{ route('catalog') }}" class="agr-cat-pill {{ ! $isFiltering ? 'is-active' : '' }}">
                Semua
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('catalog', ['category' => $category->slug]) }}#hasil-katalog"
                   class="agr-cat-pill {{ $selectedCategory === $category->slug ? 'is-active' : '' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </nav>

        {{-- 4. Konten Produk (Mode Terkurasi vs Mode Filter/Search) --}}
        <div id="hasil-katalog">
            @if ($isFiltering)
                {{-- Mode Filter / Search Aktif --}}
                <div class="agr-section-head">
                    <h2>Hasil Pencarian ({{ $products->total() }})</h2>
                    <a href="{{ route('catalog') }}">Reset</a>
                </div>

                @if ($products->isEmpty())
                    <div class="catalog-empty">
                        <h2>Produk tidak ditemukan</h2>
                        <p>Coba gunakan kata kunci lain atau lihat semua produk.</p>
                        <a href="{{ route('catalog') }}" class="catalog-action">Reset filter</a>
                    </div>
                @else
                    <div class="agr-grid-2col">
                        @foreach ($products as $product)
                            @php
                                $img = $product->image_url ?: asset('images/sayursegar.webp');
                                $avail = $product->stock >= max(1, $product->min_order_quantity);
                            @endphp
                            <article class="agr-vcard">
                                <a href="{{ route('products.show', $product) }}" class="agr-vcard-link">
                                    <div class="agr-vcard-media">
                                        <img src="{{ $img }}" alt="{{ $product->name }}" loading="lazy">
                                    </div>
                                    <span class="agr-vcard-tag">{{ $product->is_preorder ? 'Pre-order' : 'Siap panen' }}</span>
                                    <h3>{{ $product->name }}</h3>
                                    <div class="agr-vcard-price">
                                        <strong>Rp{{ number_format((float) $product->price, 0, ',', '.') }}</strong>
                                        <span>/{{ $product->unit ?? 'kg' }}</span>
                                    </div>
                                </a>
                                <form method="POST" action="{{ route('cart.store') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="{{ max(1, $product->min_order_quantity) }}">
                                    <button type="submit" class="agr-mint-pill-btn" {{ ! $avail ? 'disabled' : '' }}>
                                        {{ $avail ? '+ Keranjang' : 'Stok habis' }}
                                    </button>
                                </form>
                            </article>
                        @endforeach
                    </div>

                    @if ($products->hasPages())
                        <div class="catalog-pagination">{{ $products->links() }}</div>
                    @endif
                @endif
            @else
                {{-- Section A: Siap Panen (2-Column Vertical Cards) --}}
                <section class="agr-catalog-section">
                    <div class="agr-section-head">
                        <h2>Siap panen</h2>
                        <a href="{{ route('catalog', ['availability' => 'in_stock']) }}#hasil-katalog">Lihat semua</a>
                    </div>

                    <div class="agr-grid-2col">
                        @foreach ($readyProducts as $product)
                            @php
                                $img = $product->image_url ?: asset('images/sayursegar.webp');
                            @endphp
                            <article class="agr-vcard">
                                <a href="{{ route('products.show', $product) }}" class="agr-vcard-link">
                                    <div class="agr-vcard-media">
                                        <img src="{{ $img }}" alt="{{ $product->name }}" loading="lazy">
                                    </div>
                                    <span class="agr-vcard-tag">Siap panen</span>
                                    <h3>{{ $product->name }}</h3>
                                    <div class="agr-vcard-price">
                                        <strong>Rp{{ number_format((float) $product->price, 0, ',', '.') }}</strong>
                                        <span>/{{ $product->unit ?? 'kg' }}</span>
                                    </div>
                                </a>
                                <form method="POST" action="{{ route('cart.store') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="{{ max(1, $product->min_order_quantity) }}">
                                    <button type="submit" class="agr-mint-pill-btn">+ Keranjang</button>
                                </form>
                            </article>
                        @endforeach
                    </div>
                </section>

                {{-- Section B: Pre-order Panen (Horizontal Cards) --}}
                @if ($preorderProducts->isNotEmpty())
                    <section class="agr-catalog-section">
                        <div class="agr-section-head">
                            <h2>Pre-order panen</h2>
                            <a href="{{ route('catalog', ['availability' => 'preorder']) }}#hasil-katalog">Lihat semua</a>
                        </div>

                        <div class="agr-hcard-list">
                            @foreach ($preorderProducts as $product)
                                @php
                                    $img = $product->image_url ?: asset('images/mln.webp');
                                    $harvestDate = $product->harvest_estimate
                                        ? \Carbon\Carbon::parse($product->harvest_estimate)->translatedFormat('d M Y')
                                        : '14 Des 2026';
                                @endphp
                                <article class="agr-hcard">
                                    <a href="{{ route('products.show', $product) }}" class="agr-hcard-media">
                                        <img src="{{ $img }}" alt="{{ $product->name }}" loading="lazy">
                                    </a>
                                    <div class="agr-hcard-body">
                                        <span class="agr-hcard-tag">Panen {{ $harvestDate }}</span>
                                        <h3>{{ $product->name }}</h3>
                                        <p class="agr-hcard-price">
                                            <strong>Rp{{ number_format((float) $product->price, 0, ',', '.') }}</strong> / {{ $product->unit ?? 'kg' }}
                                        </p>
                                        <a href="{{ route('products.show', $product) }}" class="agr-mint-pill-btn">
                                            Lihat produk &rarr;
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Section C: Pilihan Hidroponik (Horizontal Cards + Quick Add) --}}
                @if ($hydroponicProducts->isNotEmpty())
                    <section class="agr-catalog-section">
                        <div class="agr-section-head">
                            <h2>Pilihan hidroponik</h2>
                            <a href="{{ route('catalog', ['q' => 'hidroponik']) }}#hasil-katalog">Lihat semua</a>
                        </div>

                        <div class="agr-hcard-list">
                            @foreach ($hydroponicProducts as $product)
                                @php
                                    $img = $product->image_url ?: asset('images/sayursegar.webp');
                                @endphp
                                <article class="agr-hcard">
                                    <a href="{{ route('products.show', $product) }}" class="agr-hcard-media">
                                        <img src="{{ $img }}" alt="{{ $product->name }}" loading="lazy">
                                    </a>
                                    <div class="agr-hcard-body">
                                        <span class="agr-hcard-tag">{{ $product->is_preorder ? 'Pre-order' : 'Siap panen' }}</span>
                                        <h3>{{ $product->name }}</h3>
                                        <p class="agr-hcard-price">
                                            <strong>Rp{{ number_format((float) $product->price, 0, ',', '.') }}</strong> / {{ $product->unit ?? '250 g' }}
                                        </p>
                                        <form method="POST" action="{{ route('cart.store') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="{{ max(1, $product->min_order_quantity) }}">
                                            <button type="submit" class="agr-mint-pill-btn">Keranjang +</button>
                                        </form>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endif
        </div>

        {{-- 5. Section: Bukan Cuma Belanja --}}
        <section class="agr-explore-section" aria-labelledby="explore-programs-title">
            <div class="agr-explore-head">
                <h2 id="explore-programs-title">Bukan cuma belanja</h2>
                <p>Belajar dan jelajahi kebun bersama Agrinova.</p>
            </div>

            <div class="agr-explore-stack">
                <a href="{{ route('eduwisata.index') }}" class="agr-explore-card">
                    <div class="agr-explore-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
                        </svg>
                    </div>
                    <div class="agr-explore-copy">
                        <h3>Eduwisata</h3>
                        <p>Untuk sekolah, keluarga &amp; komunitas</p>
                        <span>Rencanakan kunjungan &rarr;</span>
                    </div>
                </a>

                <a href="{{ route('pelatihan.index') }}" class="agr-explore-card">
                    <div class="agr-explore-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                    </div>
                    <div class="agr-explore-copy">
                        <h3>Pelatihan budidaya</h3>
                        <p>Pembekalan pensiun &amp; belajar berkebun</p>
                        <span>Kenali program pelatihan &rarr;</span>
                    </div>
                </a>
            </div>
        </section>
    </div>
</section>

{{-- Floating WhatsApp Button --}}
<a href="{{ $waGeneralUrl }}" target="_blank" rel="noopener" class="agr-floating-wa" aria-label="Chat WhatsApp Admin Agrinova">
    <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18" aria-hidden="true">
        <path d="M12.04 2a9.96 9.96 0 0 0-8.58 15.08L2 22l5.08-1.42A9.96 9.96 0 1 0 12.04 2Zm0 18.18a8.2 8.2 0 0 1-4.18-1.14l-.3-.18-3.02.84.85-2.94-.2-.3a8.2 8.2 0 1 1 6.85 3.72Zm4.5-6.15c-.25-.13-1.48-.73-1.71-.82-.23-.08-.4-.13-.57.13-.17.25-.65.82-.8.99-.15.17-.3.19-.55.06-.25-.13-1.04-.38-1.98-1.21-.73-.65-1.22-1.45-1.36-1.7-.14-.25-.01-.39.11-.52.11-.11.25-.3.38-.44.13-.15.17-.25.25-.42.08-.17.04-.32-.02-.44-.06-.13-.57-1.36-.78-1.86-.21-.49-.42-.42-.57-.43h-.49c-.17 0-.44.06-.67.32-.23.25-.88.86-.88 2.09s.9 2.42 1.03 2.59c.13.17 1.77 2.71 4.29 3.8.6.26 1.07.41 1.43.53.6.19 1.14.16 1.57.1.48-.07 1.48-.61 1.69-1.2.21-.59.21-1.1.15-1.2-.06-.1-.23-.16-.48-.29Z"/>
    </svg>
    <span>WhatsApp</span>
</a>

{{-- Modal Filter --}}
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
                        <label><input type="radio" name="availability" value="in_stock" {{ $availability === 'in_stock' ? 'checked' : '' }}> Siap panen / Tersedia</label>
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
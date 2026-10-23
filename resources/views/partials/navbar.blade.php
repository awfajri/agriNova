<header class="site-nav">
    <div class="container">
        @php($cartCount = array_sum(session('cart', [])))
        @php($isHome = request()->routeIs('home'))
        <nav class="navbar navbar-dark nav-pill {{ $isHome ? 'nav-pill--home' : 'nav-pill--compact' }}">
            <a class="navbar-brand font-heading fw-bold" href="{{ route('home') }}#hero">Agrinova</a>

            @if ($isHome)
                <div class="nav-links home-nav-links" id="mainNav">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" data-spy="hero" href="{{ route('home') }}#hero">Beranda</a>
                    <a class="nav-link" data-spy="tentang" href="{{ route('home') }}#tentang">Profil</a>
                    <a class="nav-link" data-spy="produk" href="{{ route('home') }}#produk">Produk</a>
                    <a class="nav-link" data-spy="pilar" href="{{ route('home') }}#pilar">Pelatihan</a>
                    <a class="nav-link" data-spy="fasilitas" href="{{ route('home') }}#fasilitas">Eduwisata</a>
                    <a class="nav-link" data-spy="galeri" href="{{ route('home') }}#galeri">Galeri</a>
                </div>
            @endif

            <div class="nav-actions">
                @if ($isHome)
                    <a class="nav-cta" href="{{ route('catalog', ['availability' => 'preorder']) }}">
                        Pre-Order Melon
                        <span class="nav-cta-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
                        </span>
                    </a>
                @endif
                <a class="nav-cart" href="{{ route('cart') }}" aria-label="Buka keranjang{{ $cartCount > 0 ? ', '.$cartCount.' item' : '' }}">
                    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h2l2.2 11.1a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.5L21 8H6"/><circle cx="10" cy="20" r="1.25"/><circle cx="18" cy="20" r="1.25"/></svg>
                    @if ($cartCount > 0)
                        <span>{{ $cartCount }}</span>
                    @endif
                </a>
            </div>

            @if ($isHome)
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            @endif
        </nav>
    </div>
</header>

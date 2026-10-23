<header class="site-nav">
    <div class="container">
        @php($cartCount = array_sum(session('cart', [])))
        @php($isHome = request()->routeIs('home'))
        <nav class="navbar navbar-dark nav-pill nav-pill--home">
            <a class="navbar-brand font-heading fw-bold" href="{{ route('home') }}#hero">Agrinova</a>

            <div class="nav-links home-nav-links" id="mainNav">
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" @if($isHome) data-spy="hero" @endif href="{{ route('home') }}#hero">Beranda</a>
                <a class="nav-link" @if($isHome) data-spy="tentang" @endif href="{{ route('home') }}#tentang">Profil</a>
                <a class="nav-link {{ request()->routeIs('catalog*') || request()->routeIs('products.*') ? 'active' : '' }}" @if($isHome) data-spy="produk" @endif href="{{ $isHome ? route('home').'#produk' : route('catalog') }}">Produk</a>
                <a class="nav-link {{ request()->routeIs('pelatihan.*') ? 'active' : '' }}" href="{{ route('pelatihan.index') }}">Pelatihan</a>
                <a class="nav-link {{ request()->routeIs('eduwisata.*') ? 'active' : '' }}" href="{{ route('eduwisata.index') }}">Eduwisata</a>
                <a class="nav-link" @if($isHome) data-spy="galeri" @endif href="{{ route('home') }}#galeri">Galeri</a>
            </div>

            <div class="nav-actions">
                <a class="nav-cta" href="{{ route('catalog', ['availability' => 'preorder']) }}">
                    Pre-Order Melon
                    <span class="btn-pill-icon nav-cta-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
                    </span>
                </a>
                <a class="nav-cart" href="{{ route('cart') }}" aria-label="Buka keranjang{{ $cartCount > 0 ? ', '.$cartCount.' item' : '' }}">
                    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h2l2.2 11.1a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.5L21 8H6"/><circle cx="10" cy="20" r="1.25"/><circle cx="18" cy="20" r="1.25"/></svg>
                    @if ($cartCount > 0)
                        <span>{{ $cartCount }}</span>
                    @endif
                </a>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu">
                <span></span>
                <span></span>
            </button>
        </nav>
    </div>
</header>
<div class="announcement-topbar">
    <div class="container announcement-topbar-inner">
        <span>Panen melon berikutnya segera dibuka.</span>
        <a href="{{ route('catalog', ['availability' => 'preorder']) }}">Pre-Order Melon <span aria-hidden="true">↗</span></a>
    </div>
</div>

<header class="site-nav">
    <div class="container">
        @php($cartCount = array_sum(session('cart', [])))
        @php($isCatalogPage = request()->routeIs('catalog*') || request()->routeIs('products.*'))
        @php($isHome = request()->routeIs('home'))
        <nav class="navbar navbar-dark nav-pill">
            <a class="navbar-brand font-heading fw-bold" href="{{ route('home') }}#hero">Agrinova</a>

            @if (! $isCatalogPage)
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <div class="collapse navbar-collapse nav-links home-nav-links" id="mainNav">
                    <a class="nav-link {{ $isHome ? 'active' : '' }}" @if($isHome) data-spy="hero" @endif href="{{ route('home') }}#hero">Beranda</a>
                    <a class="nav-link" @if($isHome) data-spy="tentang" @endif href="{{ route('home') }}#tentang">Profil</a>
                    <a class="nav-link" @if($isHome) data-spy="produk" @endif href="{{ route('home') }}#produk">Produk</a>
                    <a class="nav-link {{ request()->routeIs('pelatihan.*') ? 'active' : '' }}" href="{{ route('pelatihan.index') }}">Pelatihan</a>
                    <a class="nav-link {{ request()->routeIs('eduwisata.*') ? 'active' : '' }}" href="{{ route('eduwisata.index') }}">Eduwisata</a>
                    <a class="nav-link" @if($isHome) data-spy="galeri" @endif href="{{ route('home') }}#galeri">Galeri</a>
                </div>
            @endif

            <div class="nav-actions">
                @if ($isCatalogPage)
                    <form method="GET" action="{{ route('catalog') }}" class="nav-search{{ request()->filled('q') ? ' has-query' : '' }}" role="search">
                        <label class="visually-hidden" for="nav-search">Cari produk</label>
                        <svg aria-hidden="true" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                        <input id="nav-search" name="q" type="search" value="{{ request('q') }}" placeholder="Cari produk" autocomplete="off">
                        <button type="button" class="nav-search-clear" aria-label="Hapus pencarian">
                            <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12M18 6 6 18"/></svg>
                        </button>
                    </form>
                @endif
                @auth
                    <a class="nav-account-link" href="{{ route('profile.show') }}" aria-label="Buka profil saya">
                        @if (auth()->user()->avatar_url)
                            <img src="{{ auth()->user()->avatar_url }}" alt="" referrerpolicy="no-referrer">
                        @else
                            <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/></svg>
                        @endif
                    </a>
                @else
                    <a class="nav-account-link" href="{{ route('auth.login') }}" aria-label="Masuk atau daftar">
                        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/></svg>
                    </a>
                @endauth
                <a class="nav-cart" href="{{ route('cart') }}" aria-label="Buka keranjang{{ $cartCount > 0 ? ', '.$cartCount.' item' : '' }}">
                    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h2l2.2 11.1a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.5L21 8H6"/><circle cx="10" cy="20" r="1.25"/><circle cx="18" cy="20" r="1.25"/></svg>
                    @if ($cartCount > 0)
                        <span>{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </nav>
    </div>
</header>

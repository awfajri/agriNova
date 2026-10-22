<header class="site-nav">
    <div class="container">
        @php($cartCount = array_sum(session('cart', [])))
        <nav class="navbar navbar-expand-lg navbar-dark nav-pill">
            <a class="navbar-brand font-heading fw-bold" href="{{ route('home') }}#hero">Agrinova</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" data-spy="hero" href="{{ route('home') }}#hero">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" data-spy="tentang" href="{{ route('home') }}#tentang">Profil</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('catalog', 'products.show') ? 'active' : '' }}" data-spy="produk" href="{{ route('catalog') }}">Produk</a></li>
                    <li class="nav-item"><a class="nav-link" data-spy="pilar" href="{{ route('home') }}#pilar">Pelatihan</a></li>
                    <li class="nav-item"><a class="nav-link" data-spy="fasilitas" href="{{ route('home') }}#fasilitas">Eduwisata</a></li>
                    <li class="nav-item"><a class="nav-link" data-spy="galeri" href="{{ route('home') }}#galeri">Galeri</a></li>
                </ul>

                <a class="nav-cta" href="{{ route('catalog') }}">
                    Pre-Order Melon
                    <span class="nav-cta-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
                    </span>
                </a>
                <a class="nav-cart" href="{{ route('cart') }}" aria-label="Buka keranjang{{ $cartCount > 0 ? ', '.$cartCount.' item' : '' }}">
                    <svg aria-hidden="true" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    @if ($cartCount > 0)
                        <span>{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </nav>
    </div>
</header>
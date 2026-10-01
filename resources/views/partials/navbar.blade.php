<header class="site-nav">
    <div class="container">
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
                    <li class="nav-item"><a class="nav-link" data-spy="produk" href="{{ route('home') }}#produk">Produk</a></li>
                    <li class="nav-item"><a class="nav-link" data-spy="pilar" href="{{ route('home') }}#pilar">Pelatihan</a></li>
                    <li class="nav-item"><a class="nav-link" data-spy="fasilitas" href="{{ route('home') }}#fasilitas">Eduwisata</a></li>
                    <li class="nav-item"><a class="nav-link" data-spy="galeri" href="{{ route('home') }}#galeri">Galeri</a></li>
                </ul>

                <a class="nav-cta" href="{{ route('home') }}#preorder">
                    Pre-Order Melon
                    <span class="nav-cta-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
                    </span>
                </a>
            </div>
        </nav>
    </div>
</header>
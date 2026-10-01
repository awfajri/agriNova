<header class="site-nav">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark nav-pill">
            <a class="navbar-brand font-heading fw-bold" href="{{ route('home') }}">Agrinova</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Pelatihan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Eduwisata</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Galeri</a></li>
                </ul>

                <a class="nav-cta" href="#">
                    Pre-Order Melon
                    <span class="nav-cta-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
                    </span>
                </a>
            </div>
        </nav>
    </div>
</header>
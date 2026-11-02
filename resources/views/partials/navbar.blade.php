<div class="announcement-topbar">
    <div class="container announcement-topbar-inner">
        <span>Panen melon berikutnya segera dibuka.</span>
        <a href="{{ route('home') }}#preorder">Pre-Order Melon <span aria-hidden="true">↗</span></a>
    </div>
</div>

<header class="site-nav">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark nav-pill">
            <a class="navbar-brand font-heading fw-bold" href="{{ route('home') }}#hero">Agrinova</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" data-spy="tentang" href="{{ route('home') }}#tentang">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link" data-spy="produk" href="{{ route('home') }}#produk">Produk</a></li>
                    <li class="nav-item"><a class="nav-link" data-spy="pilar" href="{{ route('home') }}#pilar">Layanan</a></li>
                </ul>

                @if (Route::has('auth.login'))
                    <a class="nav-login-text" href="{{ route('auth.login') }}">Masuk / Daftar</a>
                @endif
            </div>
        </nav>
    </div>
</header>
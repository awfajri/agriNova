<nav class="navbar navbar-expand-lg navbar-dark site-nav">
    <div class="container">
        <a class="navbar-brand font-heading fw-bold" href="{{ route('home') }}">Agrinova</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto align-items-lg-center gap-lg-4">
                <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Pelatihan</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Eduwisata</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Galeri</a></li>
            </ul>
            <a class="btn-agri-pill" href="#">Pre-Order Melon</a>
        </div>
    </div>
</nav>
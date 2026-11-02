<div class="announcement-topbar">
    <div class="container announcement-topbar-inner">
        <span>Panen melon berikutnya segera dibuka.</span>
        <a href="{{ route('catalog', ['availability' => 'preorder']) }}">Pre-Order Melon <span aria-hidden="true">↗</span></a>
    </div>
</div>

<header class="site-nav">
    <div class="container">
        @php($cartCount = array_sum(session('cart', [])))
        <nav class="navbar navbar-dark nav-pill">
            <a class="navbar-brand font-heading fw-bold" href="{{ route('home') }}#hero">Agrinova</a>

            <div class="nav-actions">
                @if (request()->routeIs('catalog*') || request()->routeIs('products.*'))
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
                    <span class="nav-user-text">Akun</span>
                @else
                    <a class="nav-login-text" href="{{ route('auth.login') }}">Masuk / Daftar</a>
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

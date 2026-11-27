@php
    $cartCount = array_sum(session('cart', []));
    $isCatalogPage = request()->routeIs('catalog*') || request()->routeIs('products.*') || request()->routeIs('home');
    $isHome = request()->routeIs('home');
    $waNumber = preg_replace('/[^0-9]/', '', config('agrinova.whatsapp_number', '6285156287360'));
    $waHelpUrl = "https://wa.me/{$waNumber}?text=" . urlencode('Halo Admin Agrinova, saya butuh bantuan terkait layanan Agrinova.');
@endphp

{{-- Mobile Top Bar (Hanya tampil di layar < 768px) --}}
<header class="agr-mob-topbar">
    <div class="agr-mob-topbar-inner">
        <form method="GET" action="{{ route('catalog') }}" class="agr-mob-search-pill" role="search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
            </svg>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari produk" aria-label="Cari produk">
            @if ($isCatalogPage)
                <button type="button" class="agr-mob-filter-inside" data-bs-toggle="modal" data-bs-target="#filterModal" aria-label="Filter produk">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                    </svg>
                </button>
            @endif
        </form>

        <a href="{{ route('cart') }}" class="agr-mob-circle-btn" aria-label="Buka keranjang">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                <path d="M3 4h2l2.2 11.1a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.5L21 8H6"/>
                <circle cx="10" cy="20" r="1.25"/><circle cx="18" cy="20" r="1.25"/>
            </svg>
            @if ($cartCount > 0)
                <span class="agr-mob-cart-badge">{{ $cartCount }}</span>
            @endif
        </a>

        <button type="button" class="agr-mob-circle-btn" id="agrDrawerOpenBtn" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="agrMobileDrawer">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <line x1="4" y1="7" x2="20" y2="7"/>
                <line x1="4" y1="12" x2="20" y2="12"/>
                <line x1="4" y1="17" x2="20" y2="17"/>
            </svg>
        </button>
    </div>
</header>

{{-- Full-Screen Mobile Drawer (State Login vs Guest) --}}
<aside id="agrMobileDrawer" class="agr-drawer" aria-hidden="true" hidden>
    <div class="agr-drawer-inner">
        <div class="agr-drawer-head">
            <a href="{{ route('catalog') }}" class="agr-drawer-brand">agrinova</a>
            <button type="button" class="agr-drawer-close" id="agrDrawerCloseBtn" aria-label="Tutup menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18"/>
                </svg>
            </button>
        </div>

        @auth
            {{-- Card Akun Saya (Logged In) --}}
            <a href="{{ route('profile.show') }}" class="agr-drawer-account-card {{ request()->routeIs('profile.*') ? 'is-active' : '' }}">
                <span class="agr-drawer-account-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/>
                    </svg>
                </span>
                <div class="agr-drawer-account-copy">
                    <strong>Akun saya</strong>
                    <span>Kelola profil dan informasi akun</span>
                </div>
                <span class="agr-drawer-chevron" aria-hidden="true">&rsaquo;</span>
            </a>
        @else
            {{-- Card Selamat Datang (Guest) --}}
            <div class="agr-drawer-welcome-card">
                <h2>Selamat datang di Agrinova</h2>
                <p>Jelajahi dulu, masuk saat ingin memesan.</p>
                <a href="{{ route('auth.login') }}" class="agr-drawer-login-btn">
                    Masuk dengan Google
                </a>
            </div>
        @endauth

        {{-- Group: Jelajahi Agrinova --}}
        <div class="agr-drawer-group">
            <p class="agr-drawer-group-title">Jelajahi Agrinova</p>

            <a href="{{ route('catalog') }}" class="agr-drawer-item {{ (request()->routeIs('catalog') || request()->routeIs('home')) && request('availability') !== 'preorder' ? 'is-active' : '' }}">
                <span class="agr-drawer-item-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                </span>
                <div class="agr-drawer-item-text">
                    <strong>Belanja produk</strong>
                    @guest
                        <small>Produk segar dan kebutuhan kebun</small>
                    @endguest
                </div>
                <span class="agr-drawer-chevron" aria-hidden="true">&rsaquo;</span>
            </a>

            <a href="{{ route('catalog', ['availability' => 'preorder']) }}" class="agr-drawer-item {{ request('availability') === 'preorder' ? 'is-active' : '' }}">
                <span class="agr-drawer-item-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                </span>
                <div class="agr-drawer-item-text">
                    <strong>Pre-order panen</strong>
                    @guest
                        <small>Pesan untuk panen berikutnya</small>
                    @endguest
                </div>
                <span class="agr-drawer-chevron" aria-hidden="true">&rsaquo;</span>
            </a>

            <a href="{{ route('eduwisata.index') }}" class="agr-drawer-item {{ request()->routeIs('eduwisata.*') ? 'is-active' : '' }}">
                <span class="agr-drawer-item-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                        <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
                    </svg>
                </span>
                <div class="agr-drawer-item-text">
                    <strong>Eduwisata</strong>
                    @guest
                        <small>Rencanakan kunjungan ke kebun</small>
                    @endguest
                </div>
                <span class="agr-drawer-chevron" aria-hidden="true">&rsaquo;</span>
            </a>

            <a href="{{ route('pelatihan.index') }}" class="agr-drawer-item {{ request()->routeIs('pelatihan.*') ? 'is-active' : '' }}">
                <span class="agr-drawer-item-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                </span>
                <div class="agr-drawer-item-text">
                    <strong>Pelatihan budidaya</strong>
                    @guest
                        <small>Belajar berkebun &amp; pembekalan pensiun</small>
                    @endguest
                </div>
                <span class="agr-drawer-chevron" aria-hidden="true">&rsaquo;</span>
            </a>
        </div>

        @auth
            {{-- Group: Aktivitas Saya (Khusus Logged In) --}}
            <div class="agr-drawer-group">
                <p class="agr-drawer-group-title">Aktivitas saya</p>

                <a href="{{ route('profile.show') }}#pesanan" class="agr-drawer-item {{ request()->routeIs('profile.show', 'orders.tracking') ? 'is-active' : '' }}">
                    <span class="agr-drawer-item-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                            <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
                            <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
                        </svg>
                    </span>
                    <div class="agr-drawer-item-text">
                        <strong>Pesanan saya</strong>
                    </div>
                    <span class="agr-drawer-chevron" aria-hidden="true">&rsaquo;</span>
                </a>

                <a href="{{ route('registrations.track') }}" class="agr-drawer-item {{ request()->routeIs('registrations.track', 'registrations.show') ? 'is-active' : '' }}">
                    <span class="agr-drawer-item-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                    </span>
                    <div class="agr-drawer-item-text">
                        <strong>Reservasi saya</strong>
                    </div>
                    <span class="agr-drawer-chevron" aria-hidden="true">&rsaquo;</span>
                </a>
            </div>
        @endauth

        {{-- Group: Informasi & Bantuan --}}
        <div class="agr-drawer-group agr-drawer-group--info">
            <a href="{{ route('about') }}" class="agr-drawer-item">
                <span class="agr-drawer-item-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                </span>
                <div class="agr-drawer-item-text">
                    <strong>Tentang Agrinova</strong>
                </div>
                <span class="agr-drawer-chevron" aria-hidden="true">&rsaquo;</span>
            </a>

            <a href="{{ $waHelpUrl }}" target="_blank" rel="noopener" class="agr-drawer-item">
                <span class="agr-drawer-item-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                    </svg>
                </span>
                <div class="agr-drawer-item-text">
                    <strong>Bantuan &amp; kontak</strong>
                </div>
                <span class="agr-drawer-chevron" aria-hidden="true">&rsaquo;</span>
            </a>
        </div>

        @auth
            <form method="POST" action="{{ route('auth.logout') }}" class="agr-drawer-logout-form">
                @csrf
                <button type="submit" class="agr-drawer-logout-btn">
                    <span class="agr-drawer-logout-left">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="19" height="19">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        <span>Keluar</span>
                    </span>
                    <span class="agr-drawer-chevron" aria-hidden="true">&rsaquo;</span>
                </button>
            </form>
        @endauth
    </div>
</aside>

{{-- Desktop Header (>= 768px) --}}
<div class="agr-desktop-nav-wrap">
    <div class="announcement-topbar">
        <div class="container announcement-topbar-inner">
            <span>Panen melon berikutnya segera dibuka.</span>
            <a href="{{ route('catalog', ['availability' => 'preorder']) }}">Pre-Order Melon <span aria-hidden="true">↗</span></a>
        </div>
    </div>

    <header class="site-nav">
        <div class="container">
            <nav class="navbar navbar-dark nav-pill">
                <a class="navbar-brand font-heading fw-bold" href="{{ route('catalog') }}">Agrinova</a>

                <div class="collapse navbar-collapse nav-links home-nav-links" id="mainNav">
                    <a class="nav-link {{ (request()->routeIs('catalog') || $isHome) && request('availability') !== 'preorder' ? 'active' : '' }}" href="{{ route('catalog') }}">Belanja produk</a>
                    <a class="nav-link {{ request('availability') === 'preorder' ? 'active' : '' }}" href="{{ route('catalog', ['availability' => 'preorder']) }}">Pre-order panen</a>
                    <a class="nav-link {{ request()->routeIs('eduwisata.*') ? 'active' : '' }}" href="{{ route('eduwisata.index') }}">Eduwisata</a>
                    <a class="nav-link {{ request()->routeIs('pelatihan.*') ? 'active' : '' }}" href="{{ route('pelatihan.index') }}">Pelatihan budidaya</a>

                    @auth
                        <details class="nav-menu-dropdown">
                            <summary class="nav-link {{ request()->routeIs('orders.tracking', 'registrations.track', 'registrations.show') ? 'active' : '' }}">
                                Aktivitas saya
                                <span aria-hidden="true">⌄</span>
                            </summary>
                            <div class="nav-menu-dropdown-panel">
                                <a href="{{ route('profile.show') }}#pesanan">Pesanan saya</a>
                                <a href="{{ route('registrations.track') }}">Reservasi saya</a>
                            </div>
                        </details>
                        <details class="nav-menu-dropdown">
                            <summary class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                Akun saya
                                <span aria-hidden="true">⌄</span>
                            </summary>
                            <div class="nav-menu-dropdown-panel">
                                <a href="{{ route('profile.show') }}">Akun saya</a>
                                <form method="POST" action="{{ route('auth.logout') }}">
                                    @csrf
                                    <button type="submit">Keluar</button>
                                </form>
                            </div>
                        </details>
                    @else
                        <a class="nav-link {{ request()->routeIs('auth.login') ? 'active' : '' }}" href="{{ route('auth.login') }}">Masuk dengan Google</a>
                    @endauth

                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Tentang Agrinova</a>
                    <a class="nav-link" href="{{ $waHelpUrl }}" target="_blank" rel="noopener">Bantuan &amp; kontak</a>
                </div>

                <div class="nav-actions">
                    <form method="GET" action="{{ route('catalog') }}" class="nav-search{{ request()->filled('q') ? ' has-query' : '' }}" role="search">
                        <label class="visually-hidden" for="nav-search">Cari produk</label>
                        <svg aria-hidden="true" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                        <input id="nav-search" name="q" type="search" value="{{ request('q') }}" placeholder="Cari produk" autocomplete="off">
                    </form>
                    <a class="nav-cart" href="{{ route('cart') }}" aria-label="Buka keranjang">
                        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M3 4h2l2.2 11.1a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.5L21 8H6"/><circle cx="10" cy="20" r="1.25"/><circle cx="18" cy="20" r="1.25"/></svg>
                        @if ($cartCount > 0)
                            <span>{{ $cartCount }}</span>
                        @endif
                    </a>
                </div>
            </nav>
        </div>
    </header>
</div>
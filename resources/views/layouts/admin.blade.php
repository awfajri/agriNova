<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Agrinova')</title>
    <meta name="robots" content="noindex, nofollow">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-body">
    @php
        $isProductMenuOpen = request()->routeIs('admin.products.*')
            || request()->routeIs('admin.categories.*')
            || request()->routeIs('admin.eduwisata-packages.*')
            || request()->routeIs('admin.trainings.*');

        $isOrderMenuOpen = request()->routeIs('admin.orders.*')
            || request()->routeIs('admin.eduwisata.*')
            || request()->routeIs('admin.training-registrations.*');
    @endphp

    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">Agrinova <span>ADMIN</span></a>

            <nav class="admin-nav" aria-label="Navigasi admin">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                    Dashboard
                </a>

                {{-- Dropdown Produk & Layanan --}}
                <details class="agr-admin-dropdown" {{ $isProductMenuOpen ? 'open' : '' }}>
                    <summary class="agr-admin-dropdown-btn {{ $isProductMenuOpen ? 'is-active' : '' }}">
                        <span>Produk</span>
                        <svg class="agr-dropdown-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="14" height="14" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </summary>

                    <div class="agr-admin-tree">
                        <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}">
                            Buah &amp; Sayur
                        </a>
                        <a href="{{ route('admin.eduwisata-packages.index') }}" class="{{ request()->routeIs('admin.eduwisata-packages.*') ? 'is-active' : '' }}">
                            Eduwisata
                        </a>
                        <a href="{{ route('admin.trainings.index') }}" class="{{ request()->routeIs('admin.trainings.*') ? 'is-active' : '' }}">
                            Pelatihan MPP
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}">
                            Kategori Produk
                        </a>
                    </div>
                </details>

                <a href="{{ route('admin.preorders.index') }}" class="{{ request()->routeIs('admin.preorders.*') ? 'is-active' : '' }}">
                    Banner &amp; Pre-order
                </a>

                {{-- Dropdown Order & Reservasi --}}
                <details class="agr-admin-dropdown" {{ $isOrderMenuOpen ? 'open' : '' }}>
                    <summary class="agr-admin-dropdown-btn {{ $isOrderMenuOpen ? 'is-active' : '' }}">
                        <span>Order</span>
                        <svg class="agr-dropdown-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="14" height="14" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </summary>

                    <div class="agr-admin-tree">
                        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'is-active' : '' }}">
                            Buah &amp; Sayur
                        </a>
                        <a href="{{ route('admin.eduwisata.index') }}" class="{{ request()->routeIs('admin.eduwisata.*') ? 'is-active' : '' }}">
                            Reservasi Eduwisata
                        </a>
                        <a href="{{ route('admin.training-registrations.index') }}" class="{{ request()->routeIs('admin.training-registrations.*') ? 'is-active' : '' }}">
                            Reservasi Pelatihan
                        </a>
                    </div>
                </details>

                <a href="{{ route('admin.gallery.index') }}" class="{{ request()->routeIs('admin.gallery.*') ? 'is-active' : '' }}">
                    Galeri
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews.*') ? 'is-active' : '' }}">
                    Rating &amp; komentar
                </a>
            </nav>

            <div class="admin-sidebar-footer">
                <a href="{{ route('home') }}">Lihat website</a>
                <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'is-active' : '' }}">Pengaturan</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit">Keluar</button>
                </form>
            </div>
        </aside>

        <main class="admin-main">
            <header class="admin-topbar">
                <div>
                    <span class="eyebrow">Panel operasional</span>
                    <h1>@yield('heading', 'Dashboard')</h1>
                </div>
                <span class="admin-user">{{ auth()->user()->name }}</span>
            </header>

            @if (session('success'))
                <div class="admin-alert admin-alert--success" role="status">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="admin-alert admin-alert--error" role="alert">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="admin-alert admin-alert--error" role="alert">{{ $errors->first() }}</div>
            @endif

            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
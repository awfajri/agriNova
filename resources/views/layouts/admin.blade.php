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
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">Agrinova <span>Admin</span></a>
            <nav class="admin-nav" aria-label="Navigasi admin">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}">Produk</a>
                <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'is-active' : '' }}">Order</a>
                <a href="{{ route('admin.preorders.index') }}" class="{{ request()->routeIs('admin.preorders.*') ? 'is-active' : '' }}">Pre-order</a>
                <a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews.*') ? 'is-active' : '' }}">Rating & komentar</a>
            </nav>
            <div class="admin-sidebar-footer">
                <a href="{{ route('home') }}">Lihat website</a>
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

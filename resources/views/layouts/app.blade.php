<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Agrinova Farm')</title>
    <meta name="description" content="@yield('meta_description', 'Agrinova Farm: kebun melon, sayur segar, pelatihan budidaya, dan eduwisata.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="@yield('body_class')">
    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    @if (!request()->routeIs('home'))
        <a href="{{ route('home') }}#hero" class="floating-home" aria-label="Kembali ke beranda">
            <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1z"/><path d="M9 21v-6h6v6"/></svg>
            <span>Beranda</span>
        </a>
    @endif

    @if (session('auth_required'))
        <div class="modal fade auth-gate-modal" id="authGateModal" tabindex="-1" aria-labelledby="authGateTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="auth-gate-mark" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="m10 17 5-5-5-5"/><path d="M15 12H3"/></svg>
                        </span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <span class="eyebrow">Satu langkah lagi</span>
                        <h2 id="authGateTitle">Silakan login terlebih dahulu</h2>
                        <p>Login dengan akun Google untuk menyelesaikan pembelian. Katalog dan keranjang tetap bisa digunakan tanpa login.</p>
                        <a href="{{ route('auth.google', ['redirect' => session('auth_intended', route('checkout.create'))]) }}" class="google-login-button">
                            <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24"><path fill="#4285F4" d="M21.6 12.23c0-.71-.06-1.4-.18-2.05H12v3.88h5.38a4.6 4.6 0 0 1-1.99 3.02v2.51h3.22c1.89-1.74 2.99-4.3 2.99-7.36Z"/><path fill="#34A853" d="M12 22c2.7 0 4.96-.89 6.61-2.41l-3.22-2.51c-.89.6-2.02.96-3.39.96-2.61 0-4.82-1.76-5.61-4.13H3.06v2.59A9.99 9.99 0 0 0 12 22Z"/><path fill="#FBBC05" d="M6.39 13.91A6 6 0 0 1 6.07 12c0-.66.11-1.3.32-1.91V7.5H3.06A10 10 0 0 0 2 12c0 1.61.39 3.14 1.06 4.5l3.33-2.59Z"/><path fill="#EA4335" d="M12 5.96c1.47 0 2.8.51 3.84 1.51l2.88-2.88C16.96 2.91 14.7 2 12 2a9.99 9.99 0 0 0-8.94 5.5l3.33 2.59C7.18 7.72 9.39 5.96 12 5.96Z"/></svg>
                            Lanjut dengan Google
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('auth_required'))
        <script>
            window.addEventListener('load', function () {
                const modal = document.getElementById('authGateModal');
                if (modal && window.bootstrap) {
                    window.bootstrap.Modal.getOrCreateInstance(modal).show();
                }
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>
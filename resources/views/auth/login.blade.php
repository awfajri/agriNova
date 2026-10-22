@extends('layouts.app')

@section('title', 'Login | Agrinova')
@section('meta_description', 'Login ke Agrinova menggunakan akun Google untuk melanjutkan pembelian.')
@section('body_class', 'has-auth')

@section('content')
<section class="auth-page">
    <div class="container">
        <div class="auth-card">
            <span class="eyebrow">Akun Agrinova</span>
            <h1>Login untuk melanjutkan pembelian.</h1>
            <p>Gunakan akun Google. Tidak ada password atau kode verifikasi tambahan yang perlu diingat.</p>

            @if (session('error'))
                <div class="catalog-alert catalog-alert--error" role="alert">{{ session('error') }}</div>
            @endif

            @if ($googleConfigured)
                <a href="{{ route('auth.google', ['redirect' => $intended]) }}" class="google-login-button">
                    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24"><path fill="#4285F4" d="M21.6 12.23c0-.71-.06-1.4-.18-2.05H12v3.88h5.38a4.6 4.6 0 0 1-1.99 3.02v2.51h3.22c1.89-1.74 2.99-4.3 2.99-7.36Z"/><path fill="#34A853" d="M12 22c2.7 0 4.96-.89 6.61-2.41l-3.22-2.51c-.89.6-2.02.96-3.39.96-2.61 0-4.82-1.76-5.61-4.13H3.06v2.59A9.99 9.99 0 0 0 12 22Z"/><path fill="#FBBC05" d="M6.39 13.91A6 6 0 0 1 6.07 12c0-.66.11-1.3.32-1.91V7.5H3.06A10 10 0 0 0 2 12c0 1.61.39 3.14 1.06 4.5l3.33-2.59Z"/><path fill="#EA4335" d="M12 5.96c1.47 0 2.8.51 3.84 1.51l2.88-2.88C16.96 2.91 14.7 2 12 2a9.99 9.99 0 0 0-8.94 5.5l3.33 2.59C7.18 7.72 9.39 5.96 12 5.96Z"/></svg>
                    Lanjut dengan Google
                </a>
            @else
                <div class="catalog-alert catalog-alert--error" role="alert">
                    Login Google belum siap. Admin perlu mengisi <code>GOOGLE_CLIENT_ID</code> dan <code>GOOGLE_CLIENT_SECRET</code> di <code>.env</code>.
                </div>
            @endif

            <a href="{{ route('catalog') }}" class="auth-back">Kembali ke katalog</a>
        </div>
    </div>
</section>
@endsection

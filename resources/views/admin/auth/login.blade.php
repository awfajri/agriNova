@extends('layouts.admin-auth')

@section('title', 'Login Admin | Agrinova')
@section('meta_description', 'Login panel admin Agrinova.')
@section('body_class', 'has-admin-login')

@section('content')
<section class="admin-login-page">
    <div class="admin-login-card">
        <span class="eyebrow">Agrinova Farm</span>
        <h1>Panel admin</h1>
        <p>Kelola katalog, order, pre-order, dan komentar pelanggan dari satu tempat.</p>

        @if (! $configured)
            <div class="admin-alert admin-alert--error">
                Credential admin belum dikonfigurasi. Isi <code>ADMIN_EMAIL</code> dan <code>ADMIN_PASSWORD_HASH</code> di file .env.
            </div>
        @endif

        @if ($errors->any())
            <div class="admin-alert admin-alert--error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}" class="admin-login-form">
            @csrf
            <label for="admin-email">Email admin</label>
            <input id="admin-email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required>
            <label for="admin-password">Password</label>
            <div class="admin-password-field">
                <input id="admin-password" name="password" type="password" autocomplete="current-password" required>
                <button type="button" class="admin-password-toggle" aria-label="Tampilkan password" aria-pressed="false" data-password-toggle="admin-password">
                    <svg class="password-eye-open" aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                    <svg class="password-eye-closed" aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m3 3 18 18"/><path d="M10.6 5.1A10.7 10.7 0 0 1 12 5c6 0 9.5 7 9.5 7a17.5 17.5 0 0 1-3.1 3.9"/><path d="M6.7 6.7C4 8.3 2.5 12 2.5 12s3.5 7 9.5 7a9.7 9.7 0 0 0 3-.5"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
                </button>
            </div>
            <button type="submit" class="commerce-checkout-button" {{ ! $configured ? 'disabled' : '' }}>Masuk ke panel</button>
        </form>
        <a href="{{ route('home') }}" class="admin-back-link">Kembali ke website</a>
    </div>
</section>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-password-toggle]').forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                const input = document.getElementById(toggle.dataset.passwordToggle);
                const isVisible = input.type === 'text';
                input.type = isVisible ? 'password' : 'text';
                toggle.setAttribute('aria-pressed', isVisible ? 'false' : 'true');
                toggle.setAttribute('aria-label', isVisible ? 'Tampilkan password' : 'Sembunyikan password');
                toggle.classList.toggle('is-visible', !isVisible);
            });
        });
    </script>
@endpush

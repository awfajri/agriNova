@extends('layouts.app')

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
            <input id="admin-password" name="password" type="password" autocomplete="current-password" required>
            <button type="submit" class="commerce-checkout-button" {{ ! $configured ? 'disabled' : '' }}>Masuk ke panel</button>
        </form>
        <a href="{{ route('home') }}" class="admin-back-link">Kembali ke website</a>
    </div>
</section>
@endsection

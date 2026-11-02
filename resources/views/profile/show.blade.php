@extends('layouts.app')

@section('title', 'Profil Saya — Agrinova Farm')

@section('body_class', 'profile-page')

@section('content')
<main class="profile-main">
    <section class="profile-card" aria-labelledby="judul">
        <h1 id="judul">Profil Saya</h1>
        <p class="profile-lead">Kelola data kontak untuk mempercepat proses checkout.</p>

        @if (session('success'))
            <div class="profile-alert" role="status">{{ session('success') }}</div>
        @endif

        <div class="profile-identity">
            <div class="profile-avatar-lg" aria-hidden="true">
                @if ($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt="" referrerpolicy="no-referrer">
                @else
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="8" r="4.5"/><path d="M3.5 21c0-4.4 3.8-7 8.5-7s8.5 2.6 8.5 7z"/></svg>
                @endif
            </div>
            <div>
                <p class="profile-name">{{ $user->name }}</p>
                <p class="profile-email">{{ $user->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="nama">Nama Lengkap</label>
                <div class="control">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/></svg>
                    <input type="text" id="nama" name="name" value="{{ old('name', $user->name) }}" autocomplete="name" required maxlength="120">
                </div>
                @error('name')<p class="profile-error">{{ $message }}</p>@enderror
            </div>

            <div class="field">
                <label for="whatsapp">Nomor WhatsApp</label>
                <div class="control">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 16.5v3a1.8 1.8 0 0 1-2 1.8A17.5 17.5 0 0 1 2.7 5a1.8 1.8 0 0 1 1.8-2h3a1.8 1.8 0 0 1 1.8 1.5c.1 1 .4 1.9.7 2.8a1.8 1.8 0 0 1-.4 1.9L8.3 10.5a14 14 0 0 0 5.2 5.2l1.3-1.3a1.8 1.8 0 0 1 1.9-.4c.9.3 1.8.6 2.8.7a1.8 1.8 0 0 1 1.5 1.8z"/></svg>
                    <input type="tel" id="whatsapp" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="081234567890" inputmode="tel" autocomplete="tel" maxlength="24">
                </div>
                <p class="hint">Gunakan nomor aktif untuk konfirmasi pesanan.</p>
                @error('phone')<p class="profile-error">{{ $message }}</p>@enderror
            </div>

            <div class="field">
                <label for="alamat">Alamat Pengiriman</label>
                <div class="control">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg>
                    <textarea id="alamat" name="address" placeholder="Alamat lengkap untuk pengiriman" autocomplete="street-address" maxlength="255">{{ old('address', $user->address) }}</textarea>
                </div>
                <p class="hint">Tulis jalan, RT/RW, kecamatan, kota, dan kode pos.</p>
                @error('address')<p class="profile-error">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="btn-save">Simpan Perubahan</button>
        </form>

        <a class="back" href="{{ route('catalog') }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
            Kembali ke katalog
        </a>
    </section>
</main>
@endsection
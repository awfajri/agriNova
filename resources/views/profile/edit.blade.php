@extends('layouts.app')

@section('title', 'Edit Profil — Agrinova Farm')
@section('body_class', 'profile-page')

@section('content')
<main class="profile-main">
    <section class="profile-card profile-edit-card" aria-labelledby="judul-edit-profil">
        <div class="profile-section-heading">
            <div>
                <span class="eyebrow">Pengaturan akun</span>
                <h1 id="judul-edit-profil">Edit profil</h1>
                <p class="profile-lead">Perbarui data yang digunakan untuk konfirmasi pesanan pickup.</p>
            </div>
            <a href="{{ route('profile.show') }}" class="profile-close-link" aria-label="Kembali ke akun">×</a>
        </div>

        @if ($errors->any())
            <div class="profile-alert profile-alert--error" role="alert">
                <strong>Periksa data berikut.</strong>
                @foreach ($errors->all() as $error)
                    <span>{{ $error }}</span>
                @endforeach
            </div>
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
                    <input type="text" id="nama" name="name" value="{{ old('name', $user->name) }}" autocomplete="name" required maxlength="120">
                </div>
                @error('name')<p class="profile-error">{{ $message }}</p>@enderror
            </div>

            <div class="field">
                <label for="whatsapp">Nomor WhatsApp</label>
                <div class="control">
                    <input type="tel" id="whatsapp" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="081234567890" inputmode="tel" autocomplete="tel" maxlength="24">
                </div>
                <p class="hint">Gunakan nomor aktif untuk konfirmasi pembayaran dan kesiapan pickup.</p>
                @error('phone')<p class="profile-error">{{ $message }}</p>@enderror
            </div>

            <div class="field">
                <label for="alamat">Catatan Pickup <span>(Opsional)</span></label>
                <div class="control">
                    <textarea id="alamat" name="address" placeholder="Contoh: datang menggunakan kendaraan rombongan" maxlength="255">{{ old('address', $user->address) }}</textarea>
                </div>
                <p class="hint">Pesanan diambil di lokasi Agrinova, bukan dikirim melalui kurir.</p>
                @error('address')<p class="profile-error">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="btn-save">Simpan perubahan</button>
        </form>
    </section>
</main>
@endsection

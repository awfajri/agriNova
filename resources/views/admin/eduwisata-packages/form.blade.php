@extends('layouts.admin')

@section('title', ($package->exists ? 'Edit Paket Eduwisata' : 'Tambah Paket Eduwisata') . ' | Agrinova')
@section('heading', $package->exists ? 'Edit Paket Eduwisata' : 'Tambah Paket Eduwisata')

@section('content')
<form method="POST" action="{{ $formAction }}" class="admin-form-panel">
    @csrf
    @if ($formMethod !== 'POST') @method($formMethod) @endif

    <div class="admin-form-grid">
        <div class="admin-form-field">
            <label for="pkg-title">Nama Lengkap Paket</label>
            <input id="pkg-title" name="title" value="{{ old('title', $package->title) }}" placeholder="Contoh: Paket A (Basic)" required>
        </div>

        <div class="admin-form-field">
            <label for="pkg-short">Nama Singkat (di Detail)</label>
            <input id="pkg-short" name="short_title" value="{{ old('short_title', $package->short_title) }}" placeholder="Contoh: Paket A" required>
        </div>

        <div class="admin-form-field">
            <label for="pkg-tag">Label Badge / Sasaran</label>
            <input id="pkg-tag" name="tag" value="{{ old('tag', $package->tag) }}" placeholder="Sekolah & Komunitas" required>
        </div>

        <div class="admin-form-field">
            <label for="pkg-price">Tarif per Peserta (Rp)</label>
            <input id="pkg-price" name="price" type="number" min="0" value="{{ old('price', $package->price) }}" required>
        </div>

        <div class="admin-form-field">
            <label for="pkg-quota">Keterangan Kuota</label>
            <input id="pkg-quota" name="quota" value="{{ old('quota', $package->quota) }}" placeholder="100–120 peserta/sesi" required>
        </div>

        <div class="admin-form-field">
            <label for="pkg-order">Urutan Tampil</label>
            <input id="pkg-order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $package->sort_order) }}">
        </div>

        <div class="admin-form-field admin-form-field--wide">
            <label for="pkg-subtitle">Subjudul Singkat (Tampil di Halaman Detail)</label>
            <input id="pkg-subtitle" name="subtitle" value="{{ old('subtitle', $package->subtitle) }}" placeholder="Tur kebun & panen langsung">
        </div>

        <div class="admin-form-field admin-form-field--wide">
            <label for="pkg-image">Path Gambar Paket</label>
            <input id="pkg-image" name="image" value="{{ old('image', $package->image) }}" placeholder="images/edukasi2.webp">
        </div>

        <div class="admin-form-field admin-form-field--wide">
            <label for="pkg-summary">Ringkasan Paket (Tampil di Kartu List)</label>
            <textarea id="pkg-summary" name="summary" rows="3">{{ old('summary', $package->summary) }}</textarea>
        </div>

        <div class="admin-form-field admin-form-field--wide">
            <label for="pkg-highlights">Daftar Aktivitas "Ada apa di kebun?" <span>(Satu poin per baris)</span></label>
            <textarea id="pkg-highlights" name="highlights_text" rows="6" placeholder="Keliling kebun edukasi tanaman sayur, buah, & hidroponik&#10;Pengalaman memanen langsung sayur mayur">{{ old('highlights_text', is_array($package->highlights) ? implode("\n", $package->highlights) : '') }}</textarea>
        </div>
    </div>

    <div class="admin-check-grid">
        <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $package->exists ? $package->is_active : true))> Paket aktif ditampilkan</label>
    </div>

    <div class="admin-form-actions">
        <a href="{{ route('admin.eduwisata-packages.index') }}" class="admin-button admin-button--secondary">Batal</a>
        <button type="submit" class="admin-button">Simpan Paket Eduwisata</button>
    </div>
</form>
@endsection
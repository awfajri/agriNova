@extends('layouts.admin')

@section('title', ($item->exists ? 'Edit Foto Galeri' : 'Tambah Foto Galeri') . ' | Agrinova')
@section('heading', $item->exists ? 'Edit foto galeri' : 'Tambah foto galeri')

@section('content')
<form method="POST" action="{{ $formAction }}" class="admin-form-panel">
    @csrf
    @if ($formMethod !== 'POST') @method($formMethod) @endif
    <div class="admin-form-grid">
        <div class="admin-form-field">
            <label for="gallery-category">Kategori</label>
            <input id="gallery-category" name="category" value="{{ old('category', $item->category) }}" required maxlength="80">
        </div>
        <div class="admin-form-field">
            <label for="gallery-order">Urutan tampil</label>
            <input id="gallery-order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $item->sort_order) }}">
        </div>
        <div class="admin-form-field admin-form-field--wide">
            <label for="gallery-title">Judul foto</label>
            <input id="gallery-title" name="title" value="{{ old('title', $item->title) }}" required maxlength="160">
        </div>
        <div class="admin-form-field admin-form-field--wide">
            <label for="gallery-image">URL/path gambar</label>
            <input id="gallery-image" name="image_url" value="{{ old('image_url', $item->image_url) }}" required maxlength="1000" placeholder="/images/hero-bg.jpg">
            <small>Gunakan path lokal di folder public atau URL gambar publik.</small>
        </div>
        <div class="admin-form-field admin-form-field--wide">
            <label for="gallery-description">Deskripsi</label>
            <textarea id="gallery-description" name="description" rows="4" maxlength="500">{{ old('description', $item->description) }}</textarea>
        </div>
    </div>
    <div class="admin-check-grid">
        <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->exists ? $item->is_active : true))> Tampilkan di website</label>
    </div>
    <div class="admin-form-actions">
        <a href="{{ route('admin.gallery.index') }}" class="admin-button admin-button--secondary">Batal</a>
        <button type="submit" class="admin-button">Simpan foto</button>
    </div>
</form>
@endsection

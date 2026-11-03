@extends('layouts.admin')

@section('title', ($class->exists ? 'Edit Pelatihan' : 'Tambah Pelatihan') . ' | Agrinova')
@section('heading', $class->exists ? 'Edit pelatihan' : 'Tambah pelatihan')

@section('content')
<form method="POST" action="{{ $formAction }}" class="admin-form-panel">
    @csrf
    @if ($formMethod !== 'POST') @method($formMethod) @endif
    <div class="admin-form-grid">
        <div class="admin-form-field admin-form-field--wide">
            <label for="training-title">Judul kelas</label>
            <input id="training-title" name="title" value="{{ old('title', $class->title) }}" required maxlength="180">
        </div>
        <div class="admin-form-field">
            <label for="training-category">Kategori</label>
            <input id="training-category" name="category" value="{{ old('category', $class->category) }}" required maxlength="100">
        </div>
        <div class="admin-form-field">
            <label for="training-slug">Slug <span>(opsional)</span></label>
            <input id="training-slug" name="slug" value="{{ old('slug', $class->slug) }}" maxlength="200">
        </div>
        <div class="admin-form-field">
            <label for="training-price">Harga per peserta</label>
            <input id="training-price" name="price" type="number" min="0" step="100" value="{{ old('price', $class->price) }}" required>
        </div>
        <div class="admin-form-field">
            <label for="training-capacity">Kapasitas tersisa</label>
            <input id="training-capacity" name="capacity" type="number" min="0" value="{{ old('capacity', $class->capacity) }}" required>
        </div>
        <div class="admin-form-field">
            <label for="training-duration">Durasi</label>
            <input id="training-duration" name="duration" value="{{ old('duration', $class->duration) }}" required maxlength="100">
        </div>
        <div class="admin-form-field">
            <label for="training-schedule">Jadwal</label>
            <input id="training-schedule" name="schedule" value="{{ old('schedule', $class->schedule) }}" required maxlength="120">
        </div>
        <div class="admin-form-field">
            <label for="training-status">Label status</label>
            <input id="training-status" name="status" value="{{ old('status', $class->status ?: 'Tersedia') }}" required maxlength="40">
        </div>
        <div class="admin-form-field">
            <label for="training-order">Urutan tampil</label>
            <input id="training-order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $class->sort_order) }}">
        </div>
        <div class="admin-form-field admin-form-field--wide">
            <label for="training-image">URL/path gambar</label>
            <input id="training-image" name="image" value="{{ old('image', $class->image) }}" maxlength="1000" placeholder="/images/pelatihan.webp">
        </div>
        <div class="admin-form-field">
            <label for="training-highlights">Highlight <span>(satu per baris)</span></label>
            <textarea id="training-highlights" name="highlights" rows="5">{{ old('highlights', implode("\n", $class->highlights ?? [])) }}</textarea>
        </div>
        <div class="admin-form-field">
            <label for="training-materials">Materi <span>(satu per baris)</span></label>
            <textarea id="training-materials" name="materials" rows="5">{{ old('materials', implode("\n", $class->materials ?? [])) }}</textarea>
        </div>
        <div class="admin-form-field admin-form-field--wide">
            <label for="training-summary">Ringkasan</label>
            <textarea id="training-summary" name="summary" rows="3" maxlength="500">{{ old('summary', $class->summary) }}</textarea>
        </div>
        <div class="admin-form-field admin-form-field--wide">
            <label for="training-description">Deskripsi lengkap</label>
            <textarea id="training-description" name="description" rows="6" maxlength="10000">{{ old('description', $class->description) }}</textarea>
        </div>
    </div>
    <div class="admin-check-grid">
        <label><input type="checkbox" name="is_available" value="1" @checked(old('is_available', $class->exists ? $class->is_available : true))> Kelas tampil di website</label>
    </div>
    <div class="admin-form-actions">
        <a href="{{ route('admin.trainings.index') }}" class="admin-button admin-button--secondary">Batal</a>
        <button type="submit" class="admin-button">Simpan kelas</button>
    </div>
</form>
@endsection

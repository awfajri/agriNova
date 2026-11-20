@extends('layouts.admin')

@section('title', 'Kategori Produk | Agrinova')
@section('heading', 'Kategori Produk')

@section('content')
<div class="admin-grid-2">
    {{-- Form Tambah Kategori --}}
    <section class="admin-form-panel">
        <h2 style="margin-top: 0; margin-bottom: 14px; font-size: 18px;">Tambah Kategori Baru</h2>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="admin-form-field" style="margin-bottom: 14px;">
                <label for="cat-name">Nama Kategori</label>
                <input id="cat-name" name="name" type="text" placeholder="Contoh: Buah & Sayur" required>
            </div>

            <div class="admin-form-field" style="margin-bottom: 16px;">
                <label for="cat-desc">Deskripsi Singkat <span>(Opsional)</span></label>
                <textarea id="cat-desc" name="description" rows="3" placeholder="Keterangan kategori produk..."></textarea>
            </div>

            <button type="submit" class="admin-button" style="width: 100%; justify-content: center;">
                Simpan Kategori
            </button>
        </form>
    </section>

    {{-- Daftar Kategori --}}
    <section class="admin-table-panel">
        <div style="padding: 16px 20px 8px;">
            <h2 style="margin: 0; font-size: 18px;">Daftar Kategori ({{ $categories->count() }})</h2>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nama Kategori</th>
                    <th>Slug</th>
                    <th>Jumlah Produk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>
                            <form id="update-cat-{{ $category->id }}" method="POST" action="{{ route('admin.categories.update', $category) }}" class="admin-form-field" style="margin: 0;">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $category->name }}" required style="max-width: 220px; padding: 7px 10px;">
                            </form>
                        </td>
                        <td><code style="color: #1b4332; background: #e6f2ee; padding: 3px 8px; border-radius: 6px;">{{ $category->slug }}</code></td>
                        <td>{{ $category->products_count }} produk</td>
                        <td style="display: flex; gap: 6px; align-items: center;">
                            <button type="submit" form="update-cat-{{ $category->id }}" class="admin-button admin-button--secondary">
                                Simpan
                            </button>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-button admin-button--danger" @disabled($category->products_count > 0)>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Belum ada kategori produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>
@endsection
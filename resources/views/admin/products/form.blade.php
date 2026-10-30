@extends('layouts.admin')

@section('title', ($product->exists ? 'Edit Produk' : 'Tambah Produk') . ' | Agrinova')
@section('heading', $product->exists ? 'Edit produk' : 'Tambah produk')

@section('content')
<form method="POST" action="{{ $formAction }}" class="admin-form-panel">
    @csrf
    @if ($formMethod !== 'POST') @method($formMethod) @endif
    <div class="admin-form-grid">
        <div class="admin-form-field admin-form-field--wide">
            <label for="product-name">Nama produk</label>
            <input id="product-name" name="name" value="{{ old('name', $product->name) }}" required maxlength="160">
        </div>
        <div class="admin-form-field">
            <label for="product-category">Kategori</label>
            <select id="product-category" name="category_id" required>
                <option value="">Pilih kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((int) old('category_id', $product->category_id) === $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="admin-form-field">
            <label for="product-sku">SKU</label>
            <input id="product-sku" name="sku" value="{{ old('sku', $product->sku) }}" maxlength="80">
        </div>
        <div class="admin-form-field">
            <label for="product-slug">Slug <span>(opsional)</span></label>
            <input id="product-slug" name="slug" value="{{ old('slug', $product->slug) }}" maxlength="180">
        </div>
        <div class="admin-form-field">
            <label for="product-price">Harga</label>
            <input id="product-price" name="price" type="number" min="0" step="100" value="{{ old('price', $product->price) }}" required>
        </div>
        <div class="admin-form-field">
            <label for="product-stock">Stok</label>
            <input id="product-stock" name="stock" type="number" min="0" value="{{ old('stock', $product->stock) }}" required>
        </div>
        <div class="admin-form-field">
            <label for="product-minimum">Minimum order</label>
            <input id="product-minimum" name="min_order_quantity" type="number" min="1" value="{{ old('min_order_quantity', $product->min_order_quantity ?: 1) }}" required>
        </div>
        <div class="admin-form-field admin-form-field--wide">
            <label for="product-image">URL gambar</label>
            <input id="product-image" name="image_url" type="url" value="{{ old('image_url', $product->image_url) }}" placeholder="https://...">
            <small>Gunakan URL gambar yang bisa diakses publik. Upload file akan ditambahkan bersama media manager.</small>
        </div>
        <div class="admin-form-field admin-form-field--wide">
            <label for="product-short-description">Deskripsi singkat</label>
            <textarea id="product-short-description" name="short_description" rows="3" maxlength="500">{{ old('short_description', $product->short_description) }}</textarea>
        </div>
        <div class="admin-form-field admin-form-field--wide">
            <label for="product-description">Deskripsi lengkap</label>
            <textarea id="product-description" name="description" rows="7" maxlength="10000">{{ old('description', $product->description) }}</textarea>
        </div>
    </div>
    <div class="admin-check-grid">
        <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->exists ? $product->is_active : true))> Produk aktif</label>
        <label><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Produk unggulan</label>
        <label><input type="checkbox" name="is_preorder" value="1" @checked(old('is_preorder', $product->is_preorder))> Tersedia pre-order</label>
    </div>
    <div class="admin-form-actions">
        <a href="{{ route('admin.products.index') }}" class="admin-button admin-button--secondary">Batal</a>
        <button type="submit" class="admin-button">Simpan produk</button>
    </div>
</form>
@endsection

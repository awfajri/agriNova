@extends('layouts.admin')

@section('title', 'Kelola Produk | Agrinova')
@section('heading', 'Kelola produk')

@section('content')
<div class="admin-toolbar">
    <form method="GET" action="{{ route('admin.products.index') }}" class="admin-filter-form">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama atau SKU...">
        <select name="status" aria-label="Filter status produk">
            <option value="">Semua status</option>
            <option value="active" @selected(request('status') === 'active')>Aktif</option>
            <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
        </select>
        <button type="submit" class="admin-button admin-button--secondary">Terapkan</button>
    </form>
    <a href="{{ route('admin.products.create') }}" class="admin-button">Tambah produk</a>
</div>

<section class="admin-panel admin-table-panel">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse ($products as $product)
                <tr>
                    <td><strong>{{ $product->name }}</strong><small>{{ $product->sku ?: 'SKU belum diisi' }}</small></td>
                    <td>{{ $product->category->name ?? '—' }}</td>
                    <td>Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                    <td><span class="{{ $product->stock <= $product->min_order_quantity ? 'admin-stock-warning' : '' }}">{{ $product->stock }}</span></td>
                    <td><span class="admin-status admin-status--{{ $product->is_active ? 'success' : 'muted' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td class="admin-table-actions">
                        <a href="{{ route('admin.products.edit', $product) }}" class="admin-text-link">Edit</a>
                        @if ($product->is_active)
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Nonaktifkan produk ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="admin-danger-link">Nonaktifkan</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="admin-empty">Belum ada produk yang cocok.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $products->links() }}
</section>
@endsection

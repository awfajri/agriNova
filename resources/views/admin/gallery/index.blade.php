@extends('layouts.admin')

@section('title', 'Kelola Galeri | Agrinova')
@section('heading', 'Kelola galeri')

@section('content')
<div class="admin-toolbar">
    <p class="admin-toolbar-note">Atur foto kegiatan dan fasilitas yang tampil di halaman utama.</p>
    <a href="{{ route('admin.gallery.create') }}" class="admin-button">Tambah foto</a>
</div>
<section class="admin-panel admin-table-panel">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Foto</th><th>Kategori</th><th>Urutan</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse ($items as $item)
                <tr>
                    <td><strong>{{ $item->title }}</strong><small>{{ $item->description ?: 'Tanpa deskripsi' }}</small></td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->sort_order }}</td>
                    <td><span class="admin-status admin-status--{{ $item->is_active ? 'success' : 'muted' }}">{{ $item->is_active ? 'Tampil' : 'Disembunyikan' }}</span></td>
                    <td class="admin-table-actions">
                        <a href="{{ route('admin.gallery.edit', $item) }}" class="admin-text-link">Edit</a>
                        @if ($item->is_active)
                            <form method="POST" action="{{ route('admin.gallery.destroy', $item) }}" onsubmit="return confirm('Sembunyikan foto ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="admin-danger-link">Sembunyikan</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="admin-empty">Belum ada foto galeri.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $items->links() }}
</section>
@endsection

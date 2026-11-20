@extends('layouts.admin')

@section('title', 'Paket Eduwisata | Agrinova')
@section('heading', 'Paket Eduwisata')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
    <p style="margin: 0; color: #526660;">Kelola daftar paket eduwisata yang tampil di halaman publik Eduwisata.</p>
    <a href="{{ route('admin.eduwisata-packages.create') }}" class="admin-button">+ Tambah Paket</a>
</div>

<div class="admin-table-panel">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Urutan</th>
                <th>Nama Paket</th>
                <th>Kategori Tag</th>
                <th>Tarif / Peserta</th>
                <th>Kuota</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($packages as $pkg)
                <tr>
                    <td>{{ $pkg->sort_order }}</td>
                    <td>
                        <strong>{{ $pkg->title }}</strong>
                        <div style="font-size: 12px; color: #526660;">{{ $pkg->subtitle }}</div>
                    </td>
                    <td>{{ $pkg->tag }}</td>
                    <td><strong>Rp {{ number_format($pkg->price, 0, ',', '.') }}</strong></td>
                    <td>{{ $pkg->quota }}</td>
                    <td>{{ $pkg->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                    <td style="display: flex; gap: 6px;">
                        <a href="{{ route('admin.eduwisata-packages.edit', $pkg) }}" class="admin-button admin-button--secondary">Edit</a>
                        <form method="POST" action="{{ route('admin.eduwisata-packages.destroy', $pkg) }}" onsubmit="return confirm('Nonaktifkan {{ $pkg->title }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-button admin-button--danger">Nonaktifkan</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Belum ada paket eduwisata.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($packages->hasPages())
    <div style="margin-top: 16px;">{{ $packages->links() }}</div>
@endif
@endsection
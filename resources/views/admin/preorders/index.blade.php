@extends('layouts.admin')

@section('title', 'Kelola Pre-order | Agrinova')
@section('heading', 'Kelola pre-order')

@section('content')
<div class="admin-toolbar">
    <p class="admin-toolbar-note">Atur jadwal panen, periode booking, dan kapasitas pre-order.</p>
    <a href="{{ route('admin.preorders.create') }}" class="admin-button">Tambah pre-order</a>
</div>
<section class="admin-panel admin-table-panel">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Pre-order</th><th>Produk</th><th>Panen</th><th>Slot</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse ($preorders as $preorder)
                <tr>
                    <td><strong>{{ $preorder->title }}</strong><small>{{ $preorder->slug }}</small></td>
                    <td>{{ $preorder->product->name ?? '—' }}</td>
                    <td>{{ $preorder->harvest_date?->format('d M Y') ?? 'Belum diatur' }}</td>
                    <td>{{ $preorder->slot_booked }} / {{ $preorder->slot_available }}</td>
                    <td><span class="admin-status admin-status--{{ $preorder->is_active ? 'success' : 'muted' }}">{{ $preorder->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td class="admin-table-actions">
                        <a href="{{ route('admin.preorders.edit', $preorder) }}" class="admin-text-link">Edit</a>
                        <form method="POST" action="{{ route('admin.preorders.destroy', $preorder) }}" onsubmit="return confirm('Nonaktifkan pre-order ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="admin-danger-link">Nonaktifkan</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="admin-empty">Belum ada pre-order.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $preorders->links() }}
</section>
@endsection

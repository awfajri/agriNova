@extends('layouts.admin')

@section('title', 'Moderasi Review | Agrinova')
@section('heading', 'Rating & komentar')

@section('content')
<div class="admin-toolbar">
    <form method="GET" action="{{ route('admin.reviews.index') }}" class="admin-filter-form">
        <select name="status" aria-label="Filter status review">
            <option value="">Semua review</option>
            <option value="pending" @selected(request('status') === 'pending')>Menunggu moderasi</option>
            <option value="approved" @selected(request('status') === 'approved')>Tampil</option>
        </select>
        <button type="submit" class="admin-button admin-button--secondary">Terapkan</button>
    </form>
</div>
<section class="admin-panel admin-table-panel">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Produk</th><th>Pembeli</th><th>Rating</th><th>Komentar</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse ($reviews as $review)
                <tr>
                    <td><strong>{{ $review->product->name ?? 'Produk dihapus' }}</strong><small>{{ $review->order->order_number ?? 'Order dihapus' }}</small></td>
                    <td>{{ $review->user->name ?? 'User dihapus' }}</td>
                    <td class="admin-stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</td>
                    <td class="admin-review-copy">{{ $review->comment ?: 'Tanpa komentar' }}</td>
                    <td><span class="admin-status admin-status--{{ $review->is_approved ? 'success' : 'muted' }}">{{ $review->is_approved ? 'Tampil' : 'Disembunyikan' }}</span></td>
                    <td class="admin-table-actions">
                        <form method="POST" action="{{ route('admin.reviews.update', $review) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="is_approved" value="{{ $review->is_approved ? 0 : 1 }}">
                            <button type="submit" class="admin-text-button">{{ $review->is_approved ? 'Sembunyikan' : 'Tampilkan' }}</button>
                        </form>
                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Hapus ulasan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="admin-danger-link">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="admin-empty">Belum ada ulasan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $reviews->links() }}
</section>
@endsection

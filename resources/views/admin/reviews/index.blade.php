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
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Pembeli</th>
                    <th>Rating</th>
                    <th style="min-width: 280px;">Komentar &amp; Balasan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($reviews as $review)
                <tr>
                    <td>
                        <strong>{{ $review->product->name ?? 'Produk dihapus' }}</strong>
                        <small>{{ $review->order->order_number ?? 'Order dihapus' }}</small>
                    </td>
                    <td>{{ $review->user->name ?? 'User dihapus' }}</td>
                    <td class="admin-stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</td>
                    <td class="admin-review-copy">
                        <div class="admin-review-text" style="color: #11332b; font-size: 13.5px; line-height: 1.45;">
                            {{ $review->comment ?: 'Tanpa komentar' }}
                        </div>

                        {{-- Kotak Balasan Admin Jika Ada --}}
                        @if ($review->admin_reply)
                            <div class="admin-reply-box" style="margin-top: 10px; padding: 10px 12px; background: #eef7f4; border-left: 3px solid #236e59; border-radius: 8px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                    <strong style="font-size: 11px; color: #11332b;">Balasan Anda:</strong>
                                    <span style="font-size: 11px; color: #52796f;">{{ $review->admin_reply_at?->diffForHumans() }}</span>
                                </div>
                                <p style="margin: 0; font-size: 13px; color: #1b4332; line-height: 1.45;">{{ $review->admin_reply }}</p>
                                <div style="display: flex; gap: 10px; margin-top: 6px;">
                                    <button type="button" class="admin-text-button" style="padding: 0; font-size: 11px; color: #236e59; text-decoration: underline;" onclick="toggleReplyBox({{ $review->id }})">Ubah Balasan</button>
                                    <form id="delete-reply-form-{{ $review->id }}" method="POST" action="{{ route('admin.reviews.reply.delete', $review) }}" style="display: inline;">
                                        @csrf @method('DELETE')
                                        <button type="button" class="admin-danger-link" style="padding: 0; font-size: 11px; background: none; border: none; cursor: pointer;" onclick="openConfirmModal('Hapus Balasan?', 'Balasan komentar ini akan dihapus dari ulasan pelanggan.', document.getElementById('delete-reply-form-{{ $review->id }}'))">Hapus Balasan</button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        {{-- Form Tulis / Ubah Balasan --}}
                        <div id="reply-box-{{ $review->id }}" style="display: none; margin-top: 10px; padding: 10px; background: #f8faf9; border: 1px dashed #cbd5d1; border-radius: 8px;">
                            <form method="POST" action="{{ route('admin.reviews.reply', $review) }}">
                                @csrf
                                <label for="reply-input-{{ $review->id }}" style="display: block; font-size: 11px; font-weight: 700; color: #11332b; margin-bottom: 4px;">
                                    {{ $review->admin_reply ? 'Ubah Balasan Admin:' : 'Tulis Balasan Admin:' }}
                                </label>
                                <textarea id="reply-input-{{ $review->id }}" name="admin_reply" rows="2" style="width: 100%; padding: 8px; font-size: 13px; border: 1px solid #cbd5d1; border-radius: 6px; box-sizing: border-box; resize: vertical;" placeholder="Tulis tanggapan kepada pelanggan..." required>{{ old('admin_reply', $review->admin_reply) }}</textarea>
                                <div style="display: flex; gap: 8px; margin-top: 6px;">
                                    <button type="submit" class="admin-button admin-button--primary" style="padding: 5px 12px; font-size: 12px;">Simpan</button>
                                    <button type="button" class="admin-button admin-button--secondary" style="padding: 5px 12px; font-size: 12px;" onclick="toggleReplyBox({{ $review->id }})">Batal</button>
                                </div>
                            </form>
                        </div>
                    </td>
                    <td>
                        <span class="admin-status admin-status--{{ $review->is_approved ? 'success' : 'muted' }}">{{ $review->is_approved ? 'Tampil' : 'Disembunyikan' }}</span>
                    </td>
                    <td class="admin-table-actions">
                        @if (! $review->admin_reply)
                            <button type="button" class="admin-text-button" style="color: #236e59; font-weight: 600;" onclick="toggleReplyBox({{ $review->id }})">Balas</button>
                        @endif

                        <form method="POST" action="{{ route('admin.reviews.update', $review) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="is_approved" value="{{ $review->is_approved ? 0 : 1 }}">
                            <button type="submit" class="admin-text-button">{{ $review->is_approved ? 'Sembunyikan' : 'Tampilkan' }}</button>
                        </form>
                        <form id="delete-review-form-{{ $review->id }}" method="POST" action="{{ route('admin.reviews.destroy', $review) }}">
                            @csrf @method('DELETE')
                            <button type="button" class="admin-danger-link" style="background: none; border: none; cursor: pointer;" onclick="openConfirmModal('Hapus Ulasan?', 'Ulasan dari pembeli ini akan dihapus permanen dari sistem.', document.getElementById('delete-review-form-{{ $review->id }}'))">Hapus</button>
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

{{-- Sleek Custom Confirmation Modal --}}
<dialog id="admin-confirm-dialog" style="border: none; border-radius: 20px; padding: 0; max-width: 420px; width: 90%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); background: #ffffff; overflow: hidden; margin: auto;">
    <div style="padding: 26px 24px 22px; text-align: center;">
        <div style="width: 52px; height: 52px; background: #fee2e2; color: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2M10 11v6m4-6v6"/>
            </svg>
        </div>
        <h3 id="admin-confirm-title" style="margin: 0 0 8px; font-size: 18px; font-weight: 700; color: #111827;">Konfirmasi Hapus</h3>
        <p id="admin-confirm-message" style="margin: 0 0 22px; font-size: 14px; color: #6b7280; line-height: 1.5;">Apakah Anda yakin?</p>
        
        <div style="display: flex; gap: 12px; justify-content: center;">
            <button type="button" id="admin-confirm-cancel" style="flex: 1; padding: 10px 16px; border: 1px solid #d1d5db; background: #ffffff; color: #374151; font-size: 14px; font-weight: 600; border-radius: 12px; cursor: pointer; transition: background-color 0.15s ease;">
                Batal
            </button>
            <button type="button" id="admin-confirm-submit" style="flex: 1; padding: 10px 16px; border: none; background: #dc2626; color: #ffffff; font-size: 14px; font-weight: 600; border-radius: 12px; cursor: pointer; transition: background-color 0.15s ease; box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);">
                Ya, Hapus
            </button>
        </div>
    </div>
</dialog>
@endsection

@push('scripts')
<style>
    #admin-confirm-dialog::backdrop {
        background: rgba(17, 24, 39, 0.45);
        backdrop-filter: blur(4px);
    }
    #admin-confirm-dialog {
        animation: agrModalFadeIn 0.18s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes agrModalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    #admin-confirm-cancel:hover {
        background: #f3f4f6 !important;
    }
    #admin-confirm-submit:hover {
        background: #b91c1c !important;
    }
</style>
<script>
    function toggleReplyBox(id) {
        const box = document.getElementById('reply-box-' + id);
        if (!box) return;
        const isHidden = box.style.display === 'none';
        box.style.display = isHidden ? 'block' : 'none';
        if (isHidden) {
            const textarea = box.querySelector('textarea');
            if (textarea) textarea.focus();
        }
    }

    let pendingFormToSubmit = null;

    function openConfirmModal(title, message, formElement) {
        pendingFormToSubmit = formElement;
        const dialog = document.getElementById('admin-confirm-dialog');
        document.getElementById('admin-confirm-title').textContent = title;
        document.getElementById('admin-confirm-message').textContent = message;
        
        if (typeof dialog.showModal === 'function') {
            dialog.showModal();
        } else {
            dialog.setAttribute('open', '');
        }
    }

    document.getElementById('admin-confirm-cancel').addEventListener('click', function() {
        const dialog = document.getElementById('admin-confirm-dialog');
        if (typeof dialog.close === 'function') {
            dialog.close();
        } else {
            dialog.removeAttribute('open');
        }
        pendingFormToSubmit = null;
    });

    // Klik di area luar dialog untuk menutup
    document.getElementById('admin-confirm-dialog').addEventListener('click', function(event) {
        if (event.target === this) {
            this.close();
            pendingFormToSubmit = null;
        }
    });

    document.getElementById('admin-confirm-submit').addEventListener('click', function() {
        if (pendingFormToSubmit) {
            pendingFormToSubmit.submit();
        }
    });
</script>
@endpush

@extends('layouts.admin')

@section('title', 'Banner Katalog & Pre-order | Agrinova')
@section('heading', 'Banner Slider & Pre-order')

@section('content')
{{-- =========================================================================
     1. MANAJEMEN MULTI-BANNER SLIDER (TOKOPEDIA STYLE)
     ========================================================================= --}}
<section class="admin-panel" style="margin-bottom: 28px; background: #ffffff; border-radius: 18px; padding: 22px; border: 1px solid rgba(17, 51, 43, 0.1);">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 16px;">
        <div>
            <h2 style="margin: 0 0 4px; font-size: 19px; color: #11332b; font-weight: 800;">Kelola Multi-Banner Slider</h2>
            <p style="margin: 0; font-size: 13.5px; color: #526660;">
                Banner akan berputar otomatis dengan navigasi panah &amp; dots (seperti carousel Tokopedia) di halaman katalog.
            </p>
        </div>
        <button type="button" class="admin-button admin-button--primary" onclick="toggleAddBannerForm()" style="display: inline-flex; align-items: center; gap: 6px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>+ Tambah Banner Baru</span>
        </button>
    </div>

    {{-- Form Tambah Banner Baru (Collapsible) --}}
    <div id="add-banner-panel" style="display: {{ $errors->has('banner_image_file') || $errors->has('banner_image_path') ? 'block' : 'none' }}; margin-bottom: 22px; padding: 20px; background: #f4f9f7; border: 1.5px dashed #236e59; border-radius: 16px;">
        <h3 style="margin-top: 0; margin-bottom: 14px; font-size: 16px; color: #11332b; font-weight: 700;">Tambah Banner Baru ke Slider</h3>
        <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="admin-form-grid">
                <div class="admin-form-field">
                    <label for="new-banner-file">Upload Gambar Banner <span>(JPG, PNG, WEBP, maks 5MB)</span></label>
                    <input id="new-banner-file" name="banner_image_file" type="file" accept="image/*">
                </div>

                <div class="admin-form-field">
                    <label for="new-banner-path">Atau Gunakan Path Gambar yang Ada</label>
                    <input id="new-banner-path" name="banner_image_path" type="text" placeholder="Contoh: images/banner-1.jpg atau images/greenhouse.webp">
                </div>

                <div class="admin-form-field">
                    <label for="new-banner-title">Judul Banner <span>(Teks Utama)</span></label>
                    <input id="new-banner-title" name="title" type="text" placeholder="Contoh: Panen Raya Melon Golden Hidroponik">
                </div>

                <div class="admin-form-field">
                    <label for="new-banner-eyebrow">Eyebrow / Subjudul <span>(Teks Kecil di Atas Judul)</span></label>
                    <input id="new-banner-eyebrow" name="eyebrow" type="text" placeholder="Contoh: Pre-Order Batch #12 atau Kualitas Premium">
                </div>

                <div class="admin-form-field">
                    <label for="new-banner-cta-text">Teks Tombol CTA</label>
                    <input id="new-banner-cta-text" name="cta_text" type="text" placeholder="Contoh: Booking Sekarang atau Belanja Sayur">
                </div>

                <div class="admin-form-field">
                    <label for="new-banner-cta-url">Link Tujuan Tombol</label>
                    <input id="new-banner-cta-url" name="cta_url" type="text" placeholder="Contoh: /catalog?availability=preorder atau /eduwisata">
                </div>

                <div class="admin-form-field">
                    <label for="new-banner-order">Urutan Tampil <span>(Angka terkecil tampil duluan)</span></label>
                    <input id="new-banner-order" name="order" type="number" min="0" value="1">
                </div>

                <div class="admin-form-field" style="display: flex; align-items: center;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 600;">
                        <input type="checkbox" name="is_active" value="1" checked>
                        <span>Langsung aktifkan banner di website</span>
                    </label>
                </div>

                {{-- Pengaturan Hitung Mundur (Countdown Timer) --}}
                <div class="admin-form-field admin-form-field--wide" style="grid-column: 1 / -1; background: #ffffff; padding: 14px 16px; border-radius: 12px; border: 1px solid #cce3dc;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 700; color: #11332b;">
                        <input type="checkbox" id="new-banner-is-countdown" name="is_countdown" value="1" onchange="toggleCountdownInput('new')">
                        <span>Aktifkan Hitung Mundur (Countdown Timer) pada Banner Ini</span>
                    </label>

                    <div id="new-countdown-box" style="display: none; margin-top: 12px; padding-top: 12px; border-top: 1px dashed #b5d5cb;">
                        <label for="new-banner-countdown-end" style="font-size: 13px; font-weight: 600; color: #11332b; margin-bottom: 5px; display: block;">
                            Batas Waktu Countdown Berakhir (Tanggal &amp; Jam)
                        </label>
                        <input id="new-banner-countdown-end" name="countdown_end_at" type="datetime-local" style="max-width: 340px; width: 100%; padding: 8px 12px; border: 1px solid #cbd5d1; border-radius: 8px; font-size: 13.5px;">
                        <small style="display: block; color: #526660; margin-top: 5px; font-size: 12px;">
                            💡 Jika dikosongkan, countdown otomatis mengikuti waktu penutupan Pre-Order aktif.
                        </small>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 16px;">
                <button type="submit" class="admin-button admin-button--primary">Simpan Banner</button>
                <button type="button" class="admin-button admin-button--secondary" onclick="toggleAddBannerForm()">Batal</button>
            </div>
        </form>
    </div>

    {{-- Tabel Daftar Banner Yang Ada --}}
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 140px;">Preview</th>
                    <th>Judul &amp; Informasi</th>
                    <th>Tombol &amp; Link</th>
                    <th style="width: 70px; text-align: center;">Urutan</th>
                    <th>Countdown</th>
                    <th>Status</th>
                    <th style="width: 150px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($banners as $b)
                    <tr>
                        <td>
                            <img src="{{ $b->image_url }}" alt="Banner {{ $b->id }}" style="width: 120px; height: 68px; object-fit: cover; border-radius: 8px; border: 1px solid #d5e0dc; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                        </td>
                        <td>
                            @if ($b->eyebrow)
                                <small style="display: block; color: #236e59; font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.3px;">{{ $b->eyebrow }}</small>
                            @endif
                            <strong style="color: #11332b; font-size: 14px;">{{ $b->title ?: '(Tanpa Judul)' }}</strong>
                        </td>
                        <td>
                            @if ($b->cta_text)
                                <span style="display: inline-block; padding: 3px 10px; background: #eaf3f0; color: #11332b; font-size: 12px; font-weight: 700; border-radius: 999px;">
                                    {{ $b->cta_text }}
                                </span>
                                <small style="display: block; color: #526660; margin-top: 3px; font-size: 11.5px;">{{ $b->cta_url ?: route('catalog') }}</small>
                            @else
                                <span style="color: #889893; font-size: 12px;">(Tanpa Tombol)</span>
                            @endif
                        </td>
                        <td style="font-weight: 700; text-align: center;">{{ $b->order }}</td>
                        <td>
                            @if ($b->is_countdown)
                                <span class="admin-status admin-status--info" style="font-size: 11px; display: inline-flex; align-items: center; gap: 4px;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span>Aktif</span>
                                </span>
                                @if ($b->countdown_end_at)
                                    <small style="display: block; color: #526660; font-size: 11px; margin-top: 3px; font-variant-numeric: tabular-nums;">
                                        s/d {{ $b->countdown_end_at->format('d M, H:i') }}
                                    </small>
                                @else
                                    <small style="display: block; color: #889893; font-size: 10.5px; margin-top: 3px;">(Ikut Pre-Order)</small>
                                @endif
                            @else
                                <span style="color: #9ca3af; font-size: 11.5px;">Tidak</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.banners.toggle', $b) }}" style="display: inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="admin-status admin-status--{{ $b->is_active ? 'success' : 'muted' }}" style="border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; transition: transform 0.15s ease;" title="Klik untuk {{ $b->is_active ? 'menonaktifkan' : 'mengaktifkan' }} banner">
                                    <span style="width: 7px; height: 7px; border-radius: 50%; background-color: {{ $b->is_active ? '#15803d' : '#9ca3af' }}; display: inline-block;"></span>
                                    <span>{{ $b->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </button>
                            </form>
                        </td>
                        <td class="admin-table-actions" style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; align-items: center; gap: 6px;">
                                <button type="button" class="admin-button admin-button--secondary" onclick="openEditBannerModal({{ json_encode($b) }})" style="padding: 5px 10px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px; border-radius: 8px;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                                    <span>Edit</span>
                                </button>
                                <form id="delete-banner-form-{{ $b->id }}" method="POST" action="{{ route('admin.banners.destroy', $b) }}" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="button" class="admin-button admin-button--danger" onclick="openConfirmModal('Hapus Banner?', 'Banner ini akan dihapus dari slider halaman katalog.', document.getElementById('delete-banner-form-{{ $b->id }}'))" style="padding: 5px 10px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px; border-radius: 8px;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2M10 11v6m4-6v6"/></svg>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="admin-empty">Belum ada banner. Silakan klik <strong>+ Tambah Banner Baru</strong>.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

{{-- =========================================================================
     2. PENGATURAN COUNTDOWN PRE-ORDER
     ========================================================================= --}}
<section class="admin-panel" style="margin-bottom: 28px; background: #ffffff; border-radius: 18px; padding: 22px; border: 1px solid rgba(17, 51, 43, 0.1);">
    <h2 style="margin-top: 0; margin-bottom: 4px; font-size: 18px; color: #11332b; font-weight: 800;">Pengaturan Waktu Countdown Pre-Order</h2>
    <p style="margin-top: 0; margin-bottom: 16px; font-size: 13.5px; color: #526660;">
        Waktu hitung mundur ini akan otomatis digunakan oleh banner yang mengaktifkan countdown tanpa menentukan batas waktu khusus.
    </p>

    <form method="POST" action="{{ route('admin.preorders.banner') }}">
        @csrf
        <input type="hidden" name="cta_text" value="{{ $bannerSettings['cta_text'] }}">
        <input type="hidden" name="cta_url" value="{{ $bannerSettings['cta_url'] }}">

        @if ($activePreOrder)
            <input type="hidden" name="active_preorder_id" value="{{ $activePreOrder->id }}">
            <div class="admin-form-grid">
                <div class="admin-form-field">
                    <label for="batch-title">Judul Batch Aktif</label>
                    <input id="batch-title" name="batch_title" type="text" value="{{ old('batch_title', $activePreOrder->title) }}" placeholder="Pre-Order Panen Batch #12">
                </div>

                <div class="admin-form-field">
                    <label for="quick-harvest-date">Tanggal Panen Segar</label>
                    <input id="quick-harvest-date" name="harvest_date" type="date" value="{{ old('harvest_date', $activePreOrder->harvest_date?->format('Y-m-d')) }}">
                </div>

                <div class="admin-form-field admin-form-field--wide">
                    <label for="quick-countdown">Waktu Tutup Countdown (Hitung Mundur di Banner)</label>
                    <input id="quick-countdown" name="booking_close_at" type="datetime-local" value="{{ old('booking_close_at', $activePreOrder->booking_close_at?->format('Y-m-d\TH:i')) }}">
                </div>
            </div>
            <div style="margin-top: 14px;">
                <button type="submit" class="admin-button admin-button--primary">Perbarui Waktu Countdown</button>
            </div>
        @else
            <p style="color: #6b7280; font-size: 14px;">Belum ada batch pre-order aktif. Buat atau aktifkan batch di bawah ini terlebih dahulu.</p>
        @endif
    </form>
</section>

{{-- =========================================================================
     3. DAFTAR BATCH PRE-ORDER
     ========================================================================= --}}
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
    <h2 style="margin: 0; font-size: 18px; color: #11332b; font-weight: 800;">Daftar Batch Pre-order</h2>
    <a href="{{ route('admin.preorders.create') }}" class="admin-button admin-button--primary">+ Tambah Batch Pre-order</a>
</div>

<div class="admin-table-panel">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Judul Batch</th>
                <th>Produk</th>
                <th>Tanggal Panen</th>
                <th>Tutup Countdown</th>
                <th>Slot</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($preorders as $preorder)
                <tr>
                    <td><strong>{{ $preorder->title }}</strong></td>
                    <td>{{ $preorder->product->name ?? '-' }}</td>
                    <td>{{ $preorder->harvest_date?->translatedFormat('d M Y') ?? '-' }}</td>
                    <td>{{ $preorder->booking_close_at?->format('d M Y, H:i') ?? '-' }}</td>
                    <td>{{ $preorder->slot_booked }} / {{ $preorder->slot_available }}</td>
                    <td><span class="admin-status admin-status--{{ $preorder->is_active ? 'success' : 'muted' }}">{{ $preorder->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td class="admin-table-actions">
                        <a href="{{ route('admin.preorders.edit', $preorder) }}" class="admin-text-button">Edit</a>
                        <form id="delete-preorder-form-{{ $preorder->id }}" method="POST" action="{{ route('admin.preorders.destroy', $preorder) }}">
                            @csrf @method('DELETE')
                            <button type="button" class="admin-danger-link" onclick="openConfirmModal('Hapus Batch Pre-Order?', 'Batch ini akan dihapus dari sistem.', document.getElementById('delete-preorder-form-{{ $preorder->id }}'))">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-empty">Belum ada batch pre-order. Klik <strong>+ Tambah Batch Pre-order</strong> untuk membuat batch pertama.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($preorders->hasPages())
    <div style="margin-top: 16px;">{{ $preorders->links() }}</div>
@endif

{{-- =========================================================================
     MODAL EDIT BANNER (RESPONSIF DENGAN STICKY HEADER & FOOTER BUTTON SAVE)
     ========================================================================= --}}
<dialog id="edit-banner-dialog" style="border: none; border-radius: 20px; padding: 0; max-width: 650px; width: 92%; max-height: 88vh; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.28); background: #ffffff; overflow: hidden; margin: auto;">
    <form id="edit-banner-form" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; max-height: 88vh; height: 100%;">
        @csrf @method('PUT')
        
        {{-- Header Modal (Fixed / Sticky) --}}
        <div style="padding: 18px 24px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; background: #fafaf9; flex-shrink: 0;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: #11332b;">Edit Banner Slider</h3>
            <button type="button" onclick="closeEditBannerModal()" style="border: none; background: none; font-size: 26px; line-height: 1; color: #6b7280; cursor: pointer; padding: 0 4px;" aria-label="Tutup modal">&times;</button>
        </div>

        {{-- Body Modal (Scrollable Form Content) --}}
        <div style="padding: 22px 24px; overflow-y: auto; flex: 1;">
            <div class="admin-form-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 14px;">
                <div class="admin-form-field">
                    <label for="edit-banner-file">Ganti File Gambar <span>(Opsional)</span></label>
                    <input id="edit-banner-file" name="banner_image_file" type="file" accept="image/*">
                </div>

                <div class="admin-form-field">
                    <label for="edit-banner-path">Atau Path Gambar</label>
                    <input id="edit-banner-path" name="banner_image_path" type="text" placeholder="images/banner-1.jpg">
                </div>

                <div class="admin-form-field">
                    <label for="edit-banner-title">Judul Banner</label>
                    <input id="edit-banner-title" name="title" type="text" placeholder="Judul utama banner">
                </div>

                <div class="admin-form-field">
                    <label for="edit-banner-eyebrow">Eyebrow / Subjudul</label>
                    <input id="edit-banner-eyebrow" name="eyebrow" type="text" placeholder="Teks kecil di atas judul">
                </div>

                <div class="admin-form-field">
                    <label for="edit-banner-cta-text">Teks Tombol CTA</label>
                    <input id="edit-banner-cta-text" name="cta_text" type="text" placeholder="Contoh: Booking Sekarang">
                </div>

                <div class="admin-form-field">
                    <label for="edit-banner-cta-url">Link Tujuan Tombol</label>
                    <input id="edit-banner-cta-url" name="cta_url" type="text" placeholder="Contoh: /catalog">
                </div>

                <div class="admin-form-field">
                    <label for="edit-banner-order">Urutan Tampil <span>(Angka)</span></label>
                    <input id="edit-banner-order" name="order" type="number" min="0">
                </div>

                <div class="admin-form-field" style="display: flex; align-items: center;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13.5px; font-weight: 600;">
                        <input id="edit-banner-active" type="checkbox" name="is_active" value="1">
                        <span>Aktifkan banner ini di slider</span>
                    </label>
                </div>

                {{-- Pengaturan Hitung Mundur Edit Banner --}}
                <div class="admin-form-field admin-form-field--wide" style="grid-column: 1 / -1; background: #eef7f4; padding: 14px 16px; border-radius: 12px; border: 1px solid #cce3dc;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 700; color: #11332b;">
                        <input id="edit-banner-countdown" type="checkbox" name="is_countdown" value="1" onchange="toggleCountdownInput('edit')">
                        <span>Aktifkan Hitung Mundur (Countdown Timer) pada Banner Ini</span>
                    </label>

                    <div id="edit-countdown-box" style="display: none; margin-top: 12px; padding-top: 12px; border-top: 1px dashed #b5d5cb;">
                        <label for="edit-banner-countdown-end" style="font-size: 13px; font-weight: 600; color: #11332b; margin-bottom: 5px; display: block;">
                            Batas Waktu Countdown Berakhir (Tanggal &amp; Jam)
                        </label>
                        <input id="edit-banner-countdown-end" name="countdown_end_at" type="datetime-local" style="max-width: 340px; width: 100%; padding: 8px 12px; border: 1px solid #cbd5d1; border-radius: 8px; font-size: 13.5px; background: #ffffff;">
                        <small style="display: block; color: #526660; margin-top: 5px; font-size: 12px;">
                            💡 Jika dikosongkan, countdown otomatis mengikuti waktu penutupan Pre-Order aktif.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Modal (Sticky / Always in View at Bottom) --}}
        <div style="padding: 16px 24px; border-top: 1px solid #e5e7eb; background: #fafaf9; display: flex; gap: 12px; justify-content: flex-end; flex-shrink: 0;">
            <button type="button" class="admin-button admin-button--secondary" onclick="closeEditBannerModal()" style="padding: 9px 18px; font-size: 13.5px;">Batal</button>
            <button type="submit" class="admin-button admin-button--primary" style="padding: 9px 22px; font-size: 13.5px; font-weight: 700; box-shadow: 0 2px 6px rgba(35, 110, 89, 0.25);">
                Simpan Perubahan
            </button>
        </div>
    </form>
</dialog>

{{-- =========================================================================
     MODAL KONFIRMASI HAPUS MODERN
     ========================================================================= --}}
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
    #admin-confirm-dialog::backdrop,
    #edit-banner-dialog::backdrop {
        background: rgba(17, 24, 39, 0.45);
        backdrop-filter: blur(4px);
    }
    #admin-confirm-dialog,
    #edit-banner-dialog {
        animation: agrModalFadeIn 0.18s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes agrModalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.96);
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
    function toggleAddBannerForm() {
        const panel = document.getElementById('add-banner-panel');
        if (!panel) return;
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        if (panel.style.display === 'block') {
            panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    function toggleCountdownInput(type) {
        if (type === 'new') {
            const isChecked = document.getElementById('new-banner-is-countdown').checked;
            document.getElementById('new-countdown-box').style.display = isChecked ? 'block' : 'none';
        } else if (type === 'edit') {
            const isChecked = document.getElementById('edit-banner-countdown').checked;
            document.getElementById('edit-countdown-box').style.display = isChecked ? 'block' : 'none';
        }
    }

    function openEditBannerModal(banner) {
        const dialog = document.getElementById('edit-banner-dialog');
        const form = document.getElementById('edit-banner-form');
        
        form.action = `/admin/banners/${banner.id}`;
        document.getElementById('edit-banner-title').value = banner.title || '';
        document.getElementById('edit-banner-eyebrow').value = banner.eyebrow || '';
        document.getElementById('edit-banner-path').value = banner.image_path || '';
        document.getElementById('edit-banner-cta-text').value = banner.cta_text || '';
        document.getElementById('edit-banner-cta-url').value = banner.cta_url || '';
        document.getElementById('edit-banner-order').value = banner.order || 0;
        
        const countdownCheckbox = document.getElementById('edit-banner-countdown');
        countdownCheckbox.checked = Boolean(banner.is_countdown);
        
        const countdownInput = document.getElementById('edit-banner-countdown-end');
        if (banner.countdown_end_at) {
            // Ubah format date ke YYYY-MM-DDTHH:mm untuk input datetime-local
            const dt = new Date(banner.countdown_end_at);
            const year = dt.getFullYear();
            const month = String(dt.getMonth() + 1).padStart(2, '0');
            const day = String(dt.getDate()).padStart(2, '0');
            const hours = String(dt.getHours()).padStart(2, '0');
            const minutes = String(dt.getMinutes()).padStart(2, '0');
            countdownInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
        } else {
            countdownInput.value = '';
        }

        toggleCountdownInput('edit');
        document.getElementById('edit-banner-active').checked = Boolean(banner.is_active);

        if (typeof dialog.showModal === 'function') {
            dialog.showModal();
        } else {
            dialog.setAttribute('open', '');
        }
    }

    function closeEditBannerModal() {
        const dialog = document.getElementById('edit-banner-dialog');
        if (typeof dialog.close === 'function') {
            dialog.close();
        } else {
            dialog.removeAttribute('open');
        }
    }

    // Modal Konfirmasi Hapus
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

    document.getElementById('admin-confirm-dialog').addEventListener('click', function(event) {
        if (event.target === this) {
            this.close();
            pendingFormToSubmit = null;
        }
    });

    document.getElementById('edit-banner-dialog').addEventListener('click', function(event) {
        if (event.target === this) {
            closeEditBannerModal();
        }
    });

    document.getElementById('admin-confirm-submit').addEventListener('click', function() {
        if (pendingFormToSubmit) {
            pendingFormToSubmit.submit();
        }
    });
</script>
@endpush

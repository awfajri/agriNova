@extends('layouts.admin')

@section('title', 'Banner Katalog & Pre-order | Agrinova')
@section('heading', 'Banner Katalog & Countdown Pre-order')

@section('content')
{{-- Panel Custom Banner Hero & Countdown --}}
<section class="admin-form-panel" style="margin-bottom: 24px;">
    <h2 style="margin-top: 0; margin-bottom: 6px; font-size: 18px;">Pengaturan Hero Banner &amp; Countdown Katalog</h2>
    <p style="margin-top: 0; margin-bottom: 18px; font-size: 13.5px; color: #526660;">
        Atur gambar banner utama di halaman katalog, teks tombol, serta waktu hitung mundur penutupan booking pre-order.
    </p>

    <form method="POST" action="{{ route('admin.preorders.banner') }}" enctype="multipart/form-data">
        @csrf
        <div class="admin-form-grid">
            <div class="admin-form-field">
                <label for="banner-file">Upload Gambar Banner Baru <span>(JPG/PNG/WEBP, maks 4MB)</span></label>
                <input id="banner-file" name="banner_image_file" type="file" accept="image/*">
            </div>

            <div class="admin-form-field">
                <label for="banner-path">Atau Path Gambar Banner</label>
                <input id="banner-path" name="banner_image_path" type="text" value="{{ old('banner_image_path', $bannerSettings['image']) }}" placeholder="images/banner-1.jpg">
            </div>

            <div class="admin-form-field">
                <label for="cta-text">Teks Tombol Banner</label>
                <input id="cta-text" name="cta_text" type="text" value="{{ old('cta_text', $bannerSettings['cta_text']) }}" required>
            </div>

            <div class="admin-form-field">
                <label for="cta-url">Link Tujuan Tombol <span>(Kosongkan untuk filter Pre-order)</span></label>
                <input id="cta-url" name="cta_url" type="text" value="{{ old('cta_url', $bannerSettings['cta_url']) }}" placeholder="Contoh: /products/melon-premium">
            </div>

            @if ($activePreOrder)
                <input type="hidden" name="active_preorder_id" value="{{ $activePreOrder->id }}">
                <div class="admin-form-field">
                    <label for="batch-title">Judul Batch Aktif (Eyebrow Banner)</label>
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
            @endif
        </div>

        <div style="margin-top: 14px; display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
            <img src="{{ asset($bannerSettings['image']) }}" alt="Preview Banner" style="width: 180px; height: 96px; object-fit: cover; border-radius: 12px; border: 1px solid #d5e0dc;">
            <button type="submit" class="admin-button">Simpan Banner &amp; Countdown</button>
        </div>
    </form>
</section>

{{-- Daftar Batch Pre-Order --}}
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
    <h2 style="margin: 0; font-size: 18px;">Daftar Batch Pre-order</h2>
    <a href="{{ route('admin.preorders.create') }}" class="admin-button">+ Tambah Batch Pre-order</a>
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
                    <td>{{ $preorder->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                    <td style="display: flex; gap: 6px;">
                        <a href="{{ route('admin.preorders.edit', $preorder) }}" class="admin-button admin-button--secondary">Edit</a>
                        <form method="POST" action="{{ route('admin.preorders.destroy', $preorder) }}" onsubmit="return confirm('Hapus batch pre-order ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-button admin-button--danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Belum ada batch pre-order. Klik <strong>+ Tambah Batch Pre-order</strong> untuk membuat batch pertama agar countdown tampil di katalog.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($preorders->hasPages())
    <div style="margin-top: 16px;">{{ $preorders->links() }}</div>
@endif
@endsection
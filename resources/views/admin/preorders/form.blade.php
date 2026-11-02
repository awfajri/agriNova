@extends('layouts.admin')

@section('title', ($preorder->exists ? 'Edit Pre-order' : 'Tambah Pre-order') . ' | Agrinova')
@section('heading', $preorder->exists ? 'Edit pre-order' : 'Tambah pre-order')

@section('content')
<form method="POST" action="{{ $formAction }}" class="admin-form-panel">
    @csrf
    @if ($formMethod !== 'POST') @method($formMethod) @endif
    <div class="admin-form-grid">
        <div class="admin-form-field admin-form-field--wide">
            <label for="preorder-title">Judul pre-order</label>
            <input id="preorder-title" name="title" value="{{ old('title', $preorder->title) }}" required maxlength="180">
        </div>
        <div class="admin-form-field">
            <label for="preorder-product">Produk</label>
            <select id="preorder-product" name="product_id" required>
                <option value="">Pilih produk</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" @selected((int) old('product_id', $preorder->product_id) === $product->id)>{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="admin-form-field">
            <label for="preorder-slug">Slug <span>(opsional)</span></label>
            <input id="preorder-slug" name="slug" value="{{ old('slug', $preorder->slug) }}" maxlength="200">
        </div>
        <div class="admin-form-field">
            <label for="harvest-date">Tanggal panen</label>
            <input id="harvest-date" name="harvest_date" type="date" value="{{ old('harvest_date', $preorder->harvest_date?->format('Y-m-d')) }}">
        </div>
        <div class="admin-form-field">
            <label for="booking-open">Booking dibuka</label>
            <input id="booking-open" name="booking_open_at" type="datetime-local" value="{{ old('booking_open_at', $preorder->booking_open_at?->format('Y-m-d\TH:i')) }}">
        </div>
        <div class="admin-form-field">
            <label for="booking-close">Booking ditutup</label>
            <input id="booking-close" name="booking_close_at" type="datetime-local" value="{{ old('booking_close_at', $preorder->booking_close_at?->format('Y-m-d\TH:i')) }}">
        </div>
        <div class="admin-form-field">
            <label for="slot-available">Slot tersedia</label>
            <input id="slot-available" name="slot_available" type="number" min="0" value="{{ old('slot_available', $preorder->slot_available) }}" required>
        </div>
        <div class="admin-form-field">
            <label for="slot-booked">Slot sudah dipesan</label>
            <input id="slot-booked" name="slot_booked" type="number" min="0" value="{{ old('slot_booked', $preorder->slot_booked) }}" required>
        </div>
        <div class="admin-form-field admin-form-field--wide">
            <label for="preorder-description">Deskripsi</label>
            <textarea id="preorder-description" name="description" rows="6" maxlength="5000">{{ old('description', $preorder->description) }}</textarea>
        </div>
    </div>
    <div class="admin-check-grid">
        <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $preorder->exists ? $preorder->is_active : true))> Pre-order aktif</label>
    </div>
    <div class="admin-form-actions">
        <a href="{{ route('admin.preorders.index') }}" class="admin-button admin-button--secondary">Batal</a>
        <button type="submit" class="admin-button">Simpan pre-order</button>
    </div>
</form>
@endsection

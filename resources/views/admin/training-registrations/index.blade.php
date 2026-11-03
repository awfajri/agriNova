@extends('layouts.admin')

@section('title', 'Pendaftar Pelatihan | Agrinova')
@section('heading', 'Pendaftar pelatihan')

@section('content')
<div class="admin-toolbar">
    <p class="admin-toolbar-note">Tinjau pendaftar dan konfirmasi kelas melalui WhatsApp.</p>
    <form method="GET" class="admin-filter-form">
        <select name="status" aria-label="Filter status" onchange="this.form.submit()">
            <option value="">Semua status</option>
            <option value="pending" @selected(request('status') === 'pending')>Menunggu</option>
            <option value="confirmed" @selected(request('status') === 'confirmed')>Dikonfirmasi</option>
            <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
        </select>
    </form>
</div>
<section class="admin-panel admin-table-panel">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Pendaftar</th><th>Kelas</th><th>Kontak</th><th>Peserta</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse ($registrations as $registration)
                <tr>
                    <td><strong>{{ $registration->name }}</strong><small>{{ $registration->institution ?: 'Umum' }}</small></td>
                    <td>{{ $registration->class_title }}</td>
                    <td>{{ $registration->whatsapp }}<small>{{ $registration->email }}</small></td>
                    <td>{{ $registration->participants }}</td>
                    <td><span class="admin-status admin-status--{{ $registration->status === 'confirmed' ? 'success' : ($registration->status === 'rejected' ? 'danger' : 'info') }}">{{ $registration->statusLabel() }}</span></td>
                    <td>
                        <form method="POST" action="{{ route('admin.training-registrations.update', $registration) }}" class="admin-inline-form">
                            @csrf @method('PATCH')
                            <select name="status" aria-label="Status {{ $registration->name }}" onchange="this.form.submit()">
                                <option value="pending" @selected($registration->status === 'pending')>Menunggu</option>
                                <option value="confirmed" @selected($registration->status === 'confirmed')>Konfirmasi</option>
                                <option value="rejected" @selected($registration->status === 'rejected')>Tolak</option>
                            </select>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="admin-empty">Belum ada pendaftar pelatihan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $registrations->links() }}
</section>
@endsection

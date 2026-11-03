@extends('layouts.admin')

@section('title', 'Reservasi Eduwisata | Agrinova')
@section('heading', 'Reservasi eduwisata')

@section('content')
<div class="admin-toolbar">
    <p class="admin-toolbar-note">Kelola permintaan kunjungan dan konfirmasi kapasitas kebun.</p>
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
            <thead><tr><th>Rombongan</th><th>Paket</th><th>Jadwal</th><th>Kontak</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse ($reservations as $reservation)
                <tr>
                    <td><strong>{{ $reservation->group_name }}</strong><small>{{ $reservation->contact_name }} · {{ $reservation->participants }} peserta</small></td>
                    <td>{{ $reservation->package_name }}</td>
                    <td>{{ $reservation->visit_date->format('d M Y') }}<small>{{ $reservation->session }}</small></td>
                    <td>{{ $reservation->whatsapp }}</td>
                    <td><span class="admin-status admin-status--{{ $reservation->status === 'confirmed' ? 'success' : ($reservation->status === 'rejected' ? 'danger' : 'info') }}">{{ $reservation->statusLabel() }}</span></td>
                    <td>
                        <form method="POST" action="{{ route('admin.eduwisata.update', $reservation) }}" class="admin-inline-form">
                            @csrf @method('PATCH')
                            <select name="status" aria-label="Status {{ $reservation->group_name }}" onchange="this.form.submit()">
                                <option value="pending" @selected($reservation->status === 'pending')>Menunggu</option>
                                <option value="confirmed" @selected($reservation->status === 'confirmed')>Konfirmasi</option>
                                <option value="rejected" @selected($reservation->status === 'rejected')>Tolak</option>
                            </select>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="admin-empty">Belum ada reservasi eduwisata.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $reservations->links() }}
</section>
@endsection

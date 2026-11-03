@extends('layouts.admin')

@section('title', 'Kelola Pelatihan | Agrinova')
@section('heading', 'Kelola pelatihan')

@section('content')
<div class="admin-toolbar">
    <p class="admin-toolbar-note">Atur kelas, jadwal, kuota, harga, dan materi pelatihan.</p>
    <a href="{{ route('admin.trainings.create') }}" class="admin-button">Tambah kelas</a>
</div>
<section class="admin-panel admin-table-panel">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Kelas</th><th>Jadwal</th><th>Harga</th><th>Pendaftar</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse ($classes as $class)
                <tr>
                    <td><strong>{{ $class->title }}</strong><small>{{ $class->category }}</small></td>
                    <td>{{ $class->schedule }}<small>{{ $class->capacity }} kursi tersisa</small></td>
                    <td>Rp {{ number_format((float) $class->price, 0, ',', '.') }}</td>
                    <td>{{ $class->registrations_count }}</td>
                    <td><span class="admin-status admin-status--{{ $class->is_available ? 'success' : 'muted' }}">{{ $class->is_available ? $class->status : 'Nonaktif' }}</span></td>
                    <td class="admin-table-actions">
                        <a href="{{ route('admin.trainings.edit', $class) }}" class="admin-text-link">Edit</a>
                        @if ($class->is_available)
                            <form method="POST" action="{{ route('admin.trainings.destroy', $class) }}" onsubmit="return confirm('Nonaktifkan kelas ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="admin-danger-link">Nonaktifkan</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="admin-empty">Belum ada kelas pelatihan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $classes->links() }}
</section>
@endsection

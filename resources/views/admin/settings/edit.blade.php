@extends('layouts.admin')

@section('title', 'Pengaturan Website | Agrinova')
@section('heading', 'Pengaturan website')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" class="admin-form-panel">
    @csrf @method('PUT')
    <div class="admin-form-grid">
        @foreach ($fields as $key => $label)
            <div class="admin-form-field {{ in_array($key, ['address', 'seo_description'], true) ? 'admin-form-field--wide' : '' }}">
                <label for="setting-{{ $key }}">{{ $label }}</label>
                @if (in_array($key, ['address', 'seo_description'], true))
                    <textarea id="setting-{{ $key }}" name="{{ $key }}" rows="4" maxlength="{{ $key === 'seo_description' ? 320 : 500 }}">{{ old($key, $settings[$key] ?? '') }}</textarea>
                @else
                    <input id="setting-{{ $key }}" name="{{ $key }}" value="{{ old($key, $settings[$key] ?? '') }}" maxlength="{{ $key === 'seo_title' ? 180 : 1000 }}" {{ $key === 'maps_url' ? 'type=url' : '' }}>
                @endif
            </div>
        @endforeach
    </div>
    <div class="admin-form-actions">
        <button type="submit" class="admin-button">Simpan pengaturan</button>
    </div>
</form>
@endsection

@php
    if (request()->routeIs('cart')) {
        $backUrl = route('catalog');
        $backLabel = 'Belanja';
    } elseif (request()->routeIs('checkout.*')) {
        $backUrl = route('cart');
        $backLabel = 'Keranjang';
    } else {
        $backUrl = route('catalog');
        $backLabel = 'Batal';
    }
@endphp

<header class="minimal-enclosed-header">
    <div class="container minimal-enclosed-header-inner">
        <a href="{{ $backUrl }}" class="minimal-enclosed-back">
            <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
            <span>{{ $backLabel }}</span>
        </a>
        <a href="{{ route('home') }}#hero" class="minimal-enclosed-brand">Agrinova</a>
    </div>
</header>

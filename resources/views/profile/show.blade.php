@extends('layouts.app')

@section('title', 'Akun Saya — Agrinova Farm')
@section('body_class', 'profile-page')

@section('content')
<main class="profile-main">
    <div class="profile-dashboard">
        @if (session('success'))
            <div class="profile-alert" role="status">{{ session('success') }}</div>
        @endif

        <section class="profile-welcome-card">
            <div class="profile-identity">
                <div class="profile-avatar-lg" aria-hidden="true">
                    @if ($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="" referrerpolicy="no-referrer">
                    @else
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="8" r="4.5"/><path d="M3.5 21c0-4.4 3.8-7 8.5-7s8.5 2.6 8.5 7z"/></svg>
                    @endif
                </div>
                <div>
                    <span class="eyebrow">Akun Agrinova</span>
                    <h1>Halo, {{ $user->name }}</h1>
                    <p class="profile-email">{{ $user->email }}</p>
                </div>
            </div>
            <div class="profile-welcome-actions">
                <a href="{{ route('profile.edit') }}" class="profile-button profile-button--primary">Edit profil</a>
            </div>
        </section>

        <section class="profile-stats" aria-label="Ringkasan akun">
            <div class="profile-stat-card">
                <span>Total pesanan</span>
                <strong>{{ $totalOrders }}</strong>
                <small>Semua pesanan kamu</small>
            </div>
            <div class="profile-stat-card">
                <span>Sedang berjalan</span>
                <strong>{{ $activeOrders }}</strong>
                <small>Perlu dipantau</small>
            </div>
            <div class="profile-stat-card">
                <span>Sudah diambil</span>
                <strong>{{ $completedOrders }}</strong>
                <small>Pesanan selesai</small>
            </div>
        </section>

        <div class="profile-tabs" role="tablist" aria-label="Aktivitas akun">
            <button type="button" class="profile-tab {{ $activeOrder ? 'is-active' : '' }}" role="tab" aria-selected="{{ $activeOrder ? 'true' : 'false' }}" aria-controls="profile-tracking" data-profile-tab="profile-tracking">Tracking</button>
            <button type="button" class="profile-tab {{ $activeOrder ? '' : 'is-active' }}" role="tab" aria-selected="{{ $activeOrder ? 'false' : 'true' }}" aria-controls="profile-orders" data-profile-tab="profile-orders">Riwayat pesanan</button>
        </div>

        <div class="profile-tab-panels">
            <div class="profile-tab-panel {{ $activeOrder ? 'is-active' : '' }}" id="profile-tracking" role="tabpanel" @if (! $activeOrder) hidden @endif>
        @if ($activeOrder)
            <section class="profile-section profile-active-order" aria-labelledby="tracking-title">
                <div class="profile-section-heading">
                    <div>
                        <span class="eyebrow">Tracking pesanan</span>
                        <h2 id="tracking-title">Pesanan yang sedang berjalan</h2>
                    </div>
                    <a href="{{ route('orders.tracking', $activeOrder->order_number) }}" class="profile-text-link">Buka tracking</a>
                </div>

                <div class="profile-order-summary">
                    <div>
                        <strong>{{ $activeOrder->order_number }}</strong>
                        <span>{{ $activeOrder->created_at->format('d M Y, H:i') }} · Rp {{ number_format((float) $activeOrder->total, 0, ',', '.') }}</span>
                    </div>
                    <span class="profile-status profile-status--active">{{ $activeOrder->statusLabel() }}</span>
                </div>

                <div class="profile-progress">
                    @foreach ($activeOrder->trackingSteps() as $step)
                        <div class="profile-progress-step {{ $step['active'] ? 'is-active' : '' }} {{ ($step['current'] ?? false) ? 'is-current' : '' }}">
                            <span></span>
                            <small>{{ $step['label'] }}</small>
                        </div>
                    @endforeach
                </div>
                <p class="profile-pickup-hint">Pesanan diambil langsung di lokasi Agrinova. Kami akan memperbarui status saat pesanan siap diambil.</p>
            </section>
        @else
            <section class="profile-section profile-empty-state profile-empty-state--panel">
                <strong>Belum ada pesanan aktif</strong>
                <p>Status pickup pesanan yang sedang diproses akan tampil di sini.</p>
            </section>
        @endif
            </div>

            <div class="profile-tab-panel {{ $activeOrder ? '' : 'is-active' }}" id="profile-orders" role="tabpanel" @if ($activeOrder) hidden @endif>
        <section class="profile-section" aria-labelledby="orders-title">
            <div class="profile-section-heading">
                <div>
                    <span class="eyebrow">Riwayat aktivitas</span>
                    <h2 id="orders-title">Riwayat pesanan</h2>
                </div>
                <a href="{{ route('catalog') }}" class="profile-text-link">Belanja lagi</a>
            </div>

            <div class="profile-order-list">
                @forelse ($orders as $order)
                    <article class="profile-order-row">
                        <div class="profile-order-main">
                            <div>
                                <strong>{{ $order->order_number }}</strong>
                                <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <span class="profile-status {{ $order->status === 'canceled' ? 'profile-status--danger' : (in_array($order->status, \App\Models\Order::activeStatuses(), true) ? 'profile-status--active' : 'profile-status--muted') }}">{{ $order->statusLabel() }}</span>
                        </div>
                        <div class="profile-order-meta">
                            <span>{{ $order->items->sum('quantity') }} item · {{ $order->payment_status === 'paid' ? 'Pembayaran lunas' : 'Pembayaran '.ucfirst($order->payment_status) }}</span>
                            <strong>Rp {{ number_format((float) $order->total, 0, ',', '.') }}</strong>
                        </div>
                        <a href="{{ route('orders.tracking', $order->order_number) }}" class="profile-order-link">Lihat detail &amp; tracking</a>
                    </article>
                @empty
                    <div class="profile-empty-state">
                        <strong>Belum ada pesanan</strong>
                        <p>Pesanan produk Agrinova akan muncul di sini setelah checkout.</p>
                        <a href="{{ route('catalog') }}" class="profile-button profile-button--primary">Lihat katalog</a>
                    </div>
                @endforelse
            </div>

            @if ($orders->hasPages())
                <nav class="profile-pagination" aria-label="Navigasi riwayat pesanan">
                    @if ($orders->onFirstPage())
                        <span class="is-disabled">Sebelumnya</span>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}">Sebelumnya</a>
                    @endif
                    <span>Halaman {{ $orders->currentPage() }} dari {{ $orders->lastPage() }}</span>
                    @if ($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}">Berikutnya</a>
                    @else
                        <span class="is-disabled">Berikutnya</span>
                    @endif
                </nav>
            @endif
        </section>
            </div>
        </div>

        <details class="profile-settings">
            <summary class="profile-settings-trigger">
                <span class="profile-setting-icon" aria-hidden="true">⚙</span>
                <span><strong>Pengaturan akun</strong><small>Edit profil, ketentuan, dan logout</small></span>
                <svg class="profile-settings-chevron" aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m6 9 6 6 6-6"/>
                </svg>
            </summary>
            <div class="profile-settings-grid">
                <a href="{{ route('profile.edit') }}" class="profile-setting-card">
                    <span class="profile-setting-icon" aria-hidden="true">✎</span>
                    <span><strong>Edit profil</strong><small>Perbarui nama dan nomor WhatsApp.</small></span>
                    <span aria-hidden="true">→</span>
                </a>
                <a href="{{ route('terms') }}" class="profile-setting-card">
                    <span class="profile-setting-icon" aria-hidden="true">i</span>
                    <span><strong>Ketentuan penggunaan</strong><small>Pelajari aturan pemesanan dan pickup.</small></span>
                    <span aria-hidden="true">→</span>
                </a>
                <div class="profile-setting-card">
                    <span class="profile-setting-icon profile-setting-icon--danger" aria-hidden="true">↪</span>
                    <span><strong>Keluar dari akun</strong><small>Logout dari akun Google Agrinova.</small></span>
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="profile-logout-button">Keluar</button>
                    </form>
                </div>
            </div>
        </details>
    </div>
</main>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-profile-tab]').forEach(function (tab) {
            tab.addEventListener('click', function () {
                document.querySelectorAll('[data-profile-tab]').forEach(function (item) {
                    item.classList.toggle('is-active', item === tab);
                    item.setAttribute('aria-selected', item === tab ? 'true' : 'false');
                });

                document.querySelectorAll('.profile-tab-panel').forEach(function (panel) {
                    const active = panel.id === tab.dataset.profileTab;
                    panel.classList.toggle('is-active', active);
                    panel.hidden = !active;
                });
            });
        });
    </script>
@endpush

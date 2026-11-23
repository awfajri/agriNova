@extends('layouts.app')

@section('title', 'Detail Pemesanan - Agrinova')
@section('meta_description', 'Lengkapi data pemesan untuk menyelesaikan pesanan produk Agrinova.')
@section('body_class', 'page-checkout-flow')

@section('content')
@php
    $formattedSubtotal = 'Rp' . number_format((float) $subtotal, 0, ',', '.');
@endphp

<div class="agr-flow-page">
    <div class="agr-flow-container">
        {{-- Mobile Top Bar (Hanya tampil di < 768px) --}}
        <header class="agr-flow-mobile-topbar">
            <a href="{{ route('cart') }}" class="agr-flow-back" aria-label="Kembali ke keranjang">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </a>
            <h1 class="agr-flow-title">Detail pemesanan</h1>
        </header>

        {{-- Stepper (1: Active, 2: Pending, 3: Pending) --}}
        <nav class="agr-flow-stepper" aria-label="Tahapan pemesanan">
            <div class="agr-flow-step">
                <div class="agr-flow-step-circle is-active">1</div>
                <span class="agr-flow-step-label is-active">Detail</span>
            </div>

            <div class="agr-flow-step-line"></div>

            <div class="agr-flow-step">
                <div class="agr-flow-step-circle is-pending">2</div>
                <span class="agr-flow-step-label is-pending">Pembayaran</span>
            </div>

            <div class="agr-flow-step-line"></div>

            <div class="agr-flow-step">
                <div class="agr-flow-step-circle is-pending">3</div>
                <span class="agr-flow-step-label is-pending">Konfirmasi</span>
            </div>
        </nav>

        @if ($errors->any())
            <div class="agr-flow-alert" role="alert">
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Ringkasan Produk di Atas --}}
        <section class="agr-flow-items-card">
            @foreach ($items as $item)
                @php
                    $product = $item['product'];
                    $imageUrl = $product->image_path ? asset('storage/' . $product->image_path) : null;
                @endphp
                <div class="agr-flow-product-row">
                    @if ($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="agr-flow-product-thumb">
                    @else
                        <div class="agr-flow-product-thumb is-placeholder">AGR</div>
                    @endif
                    <div class="agr-flow-product-info">
                        <h2>{{ $product->name }}</h2>
                        <p>{{ $product->is_preorder ? 'Pre-order' : 'Siap panen' }} &middot; {{ $item['quantity'] }} {{ $product->unit ?? 'kg' }}</p>
                        @if ($product->is_preorder && $product->harvest_estimate)
                            <p class="agr-flow-harvest-date">Panen {{ \Carbon\Carbon::parse($product->harvest_estimate)->translatedFormat('d M Y') }}</p>
                        @endif
                        <strong>Rp{{ number_format((float) $item['line_total'], 0, ',', '.') }}</strong>
                    </div>
                </div>
            @endforeach
        </section>

        {{-- Form Data Pemesan --}}
        <form id="checkout-create-form" method="POST" action="{{ route('checkout.store') }}" class="agr-flow-form">
            @csrf
            <h2 class="agr-flow-section-heading">Data pemesan</h2>

            <div class="agr-flow-field">
                <label for="customer-name">Nama pemesan <span class="is-required">*</span></label>
                <input id="customer-name"
                       name="customer_name"
                       type="text"
                       value="{{ old('customer_name', $user->name ?? '') }}"
                       autocomplete="name"
                       required
                       maxlength="120">
                <small>Otomatis dari akun Google-mu.</small>
            </div>

            <div class="agr-flow-field">
                <label for="customer-whatsapp">Nomor WhatsApp <span class="is-required">*</span></label>
                <input id="customer-whatsapp"
                       name="customer_whatsapp"
                       type="tel"
                       value="{{ old('customer_whatsapp', $user->phone ?? '') }}"
                       placeholder="Contoh: 081234567890"
                       autocomplete="tel"
                       required
                       maxlength="24">
                <small>Wajib aktif WhatsApp untuk pengiriman jadwal panen &amp; bukti bayar.</small>
            </div>

            <div class="agr-flow-field agr-pickup-field">
                @php
                    $pickupSlots = array_keys(\App\Models\Order::pickupTimeOptions());
                    $pickupNow = \App\Models\Order::pickupNow();
                    $defaultPickupDate = old('pickup_date', $pickupNow->copy()->addDay()->toDateString());
                    $defaultPickupSlots = \App\Models\Order::availablePickupTimes($defaultPickupDate);
                    $selectedPickup = old('pickup_time', $defaultPickupSlots[0] ?? $pickupSlots[0]);
                    $selectedDate = old('pickup_date');
                    $maxPickupDays = \App\Models\Order::pickupDateMaxDays();
                @endphp

                <label class="agr-pickup-label" id="agr-pickup-label" for="agr-pickup-trigger">
                    Tanggal dan jam ambil <span class="is-required">*</span>
                </label>

                <button type="button"
                        class="agr-pickup-trigger"
                        id="agr-pickup-trigger"
                        aria-haspopup="dialog"
                        aria-labelledby="agr-pickup-label agr-pickup-trigger-text">
                    <span class="agr-pickup-trigger-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
                    </span>
                    <span class="agr-pickup-trigger-text" id="agr-pickup-trigger-text">Pilih tanggal dan jam pengambilan</span>
                    <svg class="agr-pickup-trigger-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <p class="agr-pickup-hint">Jam operasional kebun 08.00 - 16.00 WIB.</p>

                <input type="hidden" name="pickup_date" id="pickup-date-value" value="{{ $selectedDate ?: $defaultPickupDate }}">

                <dialog class="agr-pickup-dialog" id="agr-pickup-dialog" aria-labelledby="agr-pickup-dialog-title">
                    <div class="agr-pickup-sheet">
                        <div class="agr-pickup-sheet-head">
                            <div class="agr-pickup-sheet-grow">
                                <h2 id="agr-pickup-dialog-title">Kapan Anda datang ke kebun?</h2>
                            </div>
                            <button type="button" class="agr-pickup-close" id="agr-pickup-close" aria-label="Tutup">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="agr-pickup-sheet-body">
                            <div class="agr-pickup-picker">
                                <div class="agr-pickup-cols">
                                    <div class="agr-pickup-calendar-col">
                                        <div class="agr-cal">
                                            <div class="agr-cal-head">
                                                <button type="button" class="agr-cal-nav" id="agr-cal-prev" aria-label="Bulan sebelumnya">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                                                </button>
                                                <span class="agr-cal-title" id="agr-cal-title" aria-live="polite"></span>
                                                <button type="button" class="agr-cal-nav" id="agr-cal-next" aria-label="Bulan berikutnya">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                                                </button>
                                            </div>
                                            <div class="agr-cal-weekdays">
                                                <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
                                            </div>
                                            <div class="agr-cal-days" id="agr-cal-days"></div>
                                        </div>
                                    </div>

                                    <div class="agr-pickup-times">
                                        <div class="agr-pickup-times-scroll" role="radiogroup" aria-label="Rencana waktu ambil">
                                            <p class="agr-pickup-group-head">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 3v2M12 19v2M3 12h2M19 12h2M5.6 5.6 7 7M17 17l1.4 1.4M5.6 18.4 7 17M17 7l1.4-1.4"/></svg>
                                                <span>Jam tersedia</span>
                                            </p>
                                            @foreach ($pickupSlots as $value)
                                                <label class="agr-pickup-slot" data-slot="{{ $value }}">
                                                    <input type="radio"
                                                           name="pickup_time"
                                                           value="{{ $value }}"
                                                           data-time="{{ $value }}"
                                                           @checked($selectedPickup === $value)
                                                           required>
                                                    <span class="agr-pickup-row">
                                                        <span class="agr-pickup-radio" aria-hidden="true"></span>
                                                        <span>{{ substr($value, 0, 5) }}</span><span class="agr-pickup-wib">&nbsp;WIB</span>
                                                    </span>
                                                </label>
                                            @endforeach
                                            <p class="agr-pickup-empty" id="agr-pickup-empty" hidden>Tanggal ini sudah tidak punya jam tersedia. Pilih tanggal lain.</p>
                                        </div>
                                    </div>
                                </div>

                                <p class="agr-pickup-summary" aria-live="polite">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
                                    <span>Dipilih: <strong class="agr-pickup-summary-value" id="pickup-selected-value">&mdash;</strong></span>
                                </p>
                            </div>
                        </div>

                        <div class="agr-pickup-sheet-foot">
                            <button type="button" class="agr-pickup-btn" id="agr-pickup-cancel">Batal</button>
                            <button type="button" class="agr-pickup-btn is-primary" id="agr-pickup-apply">Terapkan</button>
                        </div>
                    </div>
                </dialog>
            </div>

            <div class="agr-flow-field">
                <label for="order-notes">Catatan tambahan <span>(opsional)</span></label>
                <textarea id="order-notes"
                          name="notes"
                          rows="3"
                          maxlength="1000"
                          placeholder="Misal: ambil bersama rombongan, bawa kendaraan pikap">{{ old('notes') }}</textarea>
            </div>

            {{-- Ringkasan Pembayaran --}}
            <div class="agr-flow-summary-box">
                <h3 class="agr-flow-section-heading">Ringkasan pesanan</h3>
                <div class="agr-flow-summary-row">
                    <span>Subtotal ({{ count($items) }} produk)</span>
                    <strong>{{ $formattedSubtotal }}</strong>
                </div>
                <div class="agr-flow-summary-row">
                    <span>Metode pengambilan</span>
                    <strong class="is-free">Ambil di kebun (Gratis)</strong>
                </div>
                <p class="agr-flow-summary-note">
                    Jam operasional kebun: 08.00 – 16.00 WIB (jadwal pasti dikonfirmasi admin via WhatsApp).
                </p>
            </div>

            {{-- Bottom Sticky Bar --}}
            <div class="agr-flow-bottom-bar">
                <div class="agr-flow-bottom-inner">
                    <div class="agr-flow-bottom-total">
                        <span>Total pembayaran</span>
                        <strong>{{ $formattedSubtotal }}</strong>
                    </div>
                    <button type="submit" class="agr-flow-primary-btn">Lanjut ke pembayaran</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (() => {
        const dialog = document.getElementById('agr-pickup-dialog');
        const daysEl = document.getElementById('agr-cal-days');
        if (!dialog || !daysEl) return;

        const MAX_DAYS = {{ $maxPickupDays }};
        const DEFAULT_OFFSET = 1;

        const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const HARI = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

        const trigger = document.getElementById('agr-pickup-trigger');
        const triggerText = document.getElementById('agr-pickup-trigger-text');
        const titleEl = document.getElementById('agr-cal-title');
        const prevBtn = document.getElementById('agr-cal-prev');
        const nextBtn = document.getElementById('agr-cal-next');
        const dateInput = document.getElementById('pickup-date-value');
        const summaryEl = document.getElementById('pickup-selected-value');
        const closeBtn = document.getElementById('agr-pickup-close');
        const cancelBtn = document.getElementById('agr-pickup-cancel');
        const applyBtn = document.getElementById('agr-pickup-apply');
        const emptyEl = document.getElementById('agr-pickup-empty');
        const radios = Array.from(document.querySelectorAll('input[name="pickup_time"]'));

        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Slot ambil memakai WIB, sedangkan jam browser bisa beda zona waktu.
        const WIB_OFFSET_MINUTES = 420;
        const toWib = (d) => new Date(d.getTime() + (d.getTimezoneOffset() + WIB_OFFSET_MINUTES) * 60000);
        const wibNow = toWib(new Date());
        const nowMinutes = (wibNow.getHours() * 60) + wibNow.getMinutes();

        const today = new Date(wibNow.getFullYear(), wibNow.getMonth(), wibNow.getDate());
        const max = new Date(today);
        max.setDate(max.getDate() + MAX_DAYS);

        const sameDay = (a, b) => a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate();

        const slotMinutes = (value) => {
            const [h, m] = value.split('.');
            return (Number(h) * 60) + Number(m || 0);
        };
        const availableTimes = (date) => {
            const all = radios.map((r) => r.dataset.time || r.value);
            if (!sameDay(date, today)) return all;
            return all.filter((t) => slotMinutes(t) >= nowMinutes);
        };

        const pad = (n) => (n < 10 ? '0' + n : '' + n);
        const toValue = (d) => d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
        const formatDate = (d) => HARI[d.getDay()] + ', ' + d.getDate() + ' ' + BULAN[d.getMonth()].slice(0, 3) + ' ' + d.getFullYear();
        const currentTime = () => {
            const checked = radios.find((r) => r.checked);
            return checked ? checked.value : '';
        };
        const setTime = (value) => {
            radios.forEach((r) => { r.checked = (r.value === value); });
        };

        const defaultDate = () => {
            for (let offset = DEFAULT_OFFSET; offset <= MAX_DAYS; offset++) {
                const d = new Date(today);
                d.setDate(d.getDate() + offset);
                if (d > max) break;
                if (availableTimes(d).length) return d;
            }
            return new Date(today);
        };

        const parseInitial = () => {
            const match = /^(\d{4})-(\d{2})-(\d{2})$/.exec((dateInput && dateInput.value) || '');
            if (match) {
                const parsed = new Date(Number(match[1]), Number(match[2]) - 1, Number(match[3]));
                if (parsed >= today && parsed <= max && availableTimes(parsed).length) return parsed;
            }
            return defaultDate();
        };

        let applied = null;
        let selected = parseInitial();
        let view = new Date(selected.getFullYear(), selected.getMonth(), 1);

        const updateSummary = () => {
            if (summaryEl) {
                summaryEl.textContent = formatDate(selected) + (currentTime() ? ' \u00b7 ' + currentTime() : '');
            }
        };

        const syncSlots = () => {
            const available = availableTimes(selected);
            radios.forEach((radio) => {
                const enabled = available.includes(radio.dataset.time || radio.value);
                radio.disabled = !enabled;
                const label = radio.closest('.agr-pickup-slot');
                if (label) label.classList.toggle('is-unavailable', !enabled);
            });

            if (emptyEl) emptyEl.hidden = available.length > 0;

            const checkedUsable = radios.some((r) => r.checked && !r.disabled);
            if (!checkedUsable) {
                setTime(available[0] || '');
            }

            if (applyBtn) applyBtn.disabled = available.length === 0;
        };

        const renderCalendar = () => {
            titleEl.textContent = BULAN[view.getMonth()] + ' ' + view.getFullYear();
            daysEl.innerHTML = '';

            const lead = (view.getDay() + 6) % 7;
            for (let i = 0; i < lead; i++) {
                daysEl.appendChild(document.createElement('span'));
            }

            const todayEmpty = availableTimes(today).length === 0;
            const total = new Date(view.getFullYear(), view.getMonth() + 1, 0).getDate();
            for (let d = 1; d <= total; d++) {
                const date = new Date(view.getFullYear(), view.getMonth(), d);
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = d;
                btn.className = 'agr-cal-day' + (sameDay(date, today) ? ' is-today' : '');
                btn.setAttribute('aria-pressed', sameDay(date, selected) ? 'true' : 'false');
                btn.setAttribute('aria-label', d + ' ' + BULAN[date.getMonth()] + ' ' + date.getFullYear());

                if (date < today || date > max || (sameDay(date, today) && todayEmpty)) {
                    btn.disabled = true;
                } else {
                    btn.addEventListener('click', () => {
                        selected = date;
                        syncSlots();
                        renderCalendar();
                        updateSummary();
                    });
                }

                daysEl.appendChild(btn);
            }

            prevBtn.disabled = view <= new Date(today.getFullYear(), today.getMonth(), 1);
            nextBtn.disabled = new Date(view.getFullYear(), view.getMonth() + 1, 1) > max;
        };

        prevBtn.addEventListener('click', () => {
            view = new Date(view.getFullYear(), view.getMonth() - 1, 1);
            renderCalendar();
        });

        nextBtn.addEventListener('click', () => {
            view = new Date(view.getFullYear(), view.getMonth() + 1, 1);
            renderCalendar();
        });

        radios.forEach((radio) => {
            radio.addEventListener('change', updateSummary);
        });

        const syncFromApplied = () => {
            if (applied) {
                selected = new Date(applied.date);
                setTime(applied.time);
            } else {
                selected = parseInitial();
            }
            view = new Date(selected.getFullYear(), selected.getMonth(), 1);
            syncSlots();
            renderCalendar();
            updateSummary();
        };

        const openPicker = () => {
            syncFromApplied();
            document.documentElement.classList.add('agr-lock');
            dialog.showModal();
        };

        const closePicker = () => {
            if (!dialog.open || dialog.classList.contains('is-closing')) return;

            const finish = () => {
                dialog.classList.remove('is-closing');
                dialog.close();
                document.documentElement.classList.remove('agr-lock');
            };

            if (reduceMotion) { finish(); return; }

            dialog.classList.add('is-closing');
            window.setTimeout(finish, 170);
        };

        const applyPicker = () => {
            if (availableTimes(selected).length === 0 || !currentTime()) return;

            applied = { date: new Date(selected), time: currentTime() };

            if (triggerText) {
                triggerText.textContent = formatDate(applied.date) + (applied.time ? ' \u00b7 ' + applied.time : '');
            }
            if (trigger) trigger.classList.add('is-filled');
            if (dateInput) dateInput.value = toValue(applied.date);

            closePicker();
        };

        trigger.addEventListener('click', openPicker);
        closeBtn.addEventListener('click', closePicker);
        cancelBtn.addEventListener('click', closePicker);
        applyBtn.addEventListener('click', applyPicker);

        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) closePicker();
        });

        dialog.addEventListener('cancel', (event) => {
            event.preventDefault();
            closePicker();
        });

        // Tanggal default langsung diisi agar form tetap valid
        // walau pengguna tidak membuka popup.
        if (dateInput && !dateInput.value) {
            dateInput.value = toValue(selected);
        }

        renderCalendar();
        syncSlots();
        updateSummary();
    })();
</script>
@endpush

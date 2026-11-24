@extends('layouts.app')

@section('title', 'Pendaftaran Pelatihan - Agrinova')
@section('body_class', 'page-mobile-subpage')

@section('content')
@php
    $defaultParticipants = (int) old('jumlah_peserta', 1);
    $initialTotal = $kelas['harga'] * $defaultParticipants;
@endphp

<style>
    .agr-datepicker {
        position: relative;
    }
    .agr-datepicker-trigger {
        width: 100%;
        min-height: 48px;
        padding: 12px 16px;
        border: 1.5px solid #dce5df;
        border-radius: 14px;
        background-color: #ffffff;
        color: #14261c;
        font-family: inherit;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        cursor: pointer;
        text-align: left;
        transition: border-color 0.18s ease, box-shadow 0.18s ease;
    }
    .agr-datepicker-trigger:focus,
    .agr-datepicker.is-open .agr-datepicker-trigger {
        outline: none;
        border-color: #163a28;
        box-shadow: 0 0 0 3px rgba(22, 58, 40, 0.1);
    }
    .agr-datepicker-placeholder {
        color: #8a9b91;
    }
    .agr-datepicker-popover {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        width: 100%;
        max-width: 340px;
        background: #ffffff;
        border: 1.5px solid #dce5df;
        border-radius: 18px;
        box-shadow: 0 14px 32px rgba(22, 58, 40, 0.14);
        padding: 16px;
        z-index: 50;
        display: none;
    }
    .agr-datepicker.is-open .agr-datepicker-popover {
        display: block;
    }
    .agr-cal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .agr-cal-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: #14261c;
    }
    .agr-cal-nav {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        border: 1px solid #dce5df;
        background: #f7faf8;
        color: #163a28;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .agr-cal-nav:hover:not(:disabled) {
        background: #e6f0ea;
    }
    .agr-cal-nav:disabled {
        opacity: 0.35;
        cursor: not-allowed;
    }
    .agr-cal-weekdays,
    .agr-cal-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        gap: 4px;
    }
    .agr-cal-weekdays span {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7f73;
        padding-bottom: 6px;
    }
    .agr-cal-day {
        aspect-ratio: 1;
        border: none;
        border-radius: 10px;
        background: transparent;
        color: #14261c;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    .agr-cal-day:hover:not(:disabled):not(.is-selected) {
        background: #e9f2ec;
        color: #163a28;
    }
    .agr-cal-day.is-today:not(.is-selected) {
        border: 1.5px solid #163a28;
        font-weight: 700;
    }
    .agr-cal-day.is-selected {
        background: #163a28;
        color: #ffffff;
        font-weight: 700;
    }
    .agr-cal-day:disabled {
        color: #c2cec7;
        cursor: not-allowed;
    }
</style>

<div class="agr-edu-page has-sticky-bottom">
    <div class="agr-edu-container">
        <header class="agr-edu-topbar">
            <a href="{{ route('pelatihan.show', $kelas['slug']) }}" class="agr-edu-back" aria-label="Kembali ke detail pelatihan">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" width="20" height="20" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </a>
            <h1 class="agr-edu-topbar-title">Detail pendaftaran</h1>
        </header>

        {{-- Stepper --}}
        <nav class="agr-flow-stepper" aria-label="Tahapan pendaftaran">
            <div class="agr-flow-step">
                <div class="agr-flow-step-circle is-active">1</div>
                <span class="agr-flow-step-label is-active">Detail</span>
            </div>
            <div class="agr-flow-step-line"></div>
            <div class="agr-flow-step">
                <div class="agr-flow-step-circle is-pending">2</div>
                <span class="agr-flow-step-label is-pending">Konfirmasi</span>
            </div>
            <div class="agr-flow-step-line"></div>
            <div class="agr-flow-step">
                <div class="agr-flow-step-circle is-pending">3</div>
                <span class="agr-flow-step-label is-pending">Selesai</span>
            </div>
        </nav>

        {{-- Kartu Ringkasan Pelatihan Terpilih --}}
        <div class="agr-flow-items-card">
            <div class="agr-flow-product-row">
                <img src="{{ asset($kelas['image']) }}" alt="{{ $kelas['judul'] }}" class="agr-flow-product-thumb">
                <div class="agr-flow-product-info">
                    <h2>{{ $kelas['judul'] }}</h2>
                    @if (filled($kelas['durasi']))
                        <p>Durasi {{ $kelas['durasi'] }} &middot; Jadwal fleksibel</p>
                    @endif
                    <strong>Rp{{ number_format($kelas['harga'], 0, ',', '.') }} <small style="font-weight:500;">/ Peserta</small></strong>
                </div>
            </div>
        </div>

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

        <form method="POST" action="{{ route('pelatihan.register', $kelas['slug']) }}" class="agr-flow-form">
            @csrf
            <h2 class="agr-flow-section-heading">Data peserta</h2>

            <div class="agr-flow-field">
                <label for="nama">Nama lengkap <span class="is-required">*</span></label>
                <input id="nama" name="nama" type="text" value="{{ old('nama', auth()->user()->name ?? '') }}" placeholder="Nama lengkap peserta" required>
            </div>

            <div class="agr-flow-field">
                <label for="whatsapp">Nomor WhatsApp <span class="is-required">*</span></label>
                <input id="whatsapp" name="whatsapp" type="tel" value="{{ old('whatsapp', auth()->user()->phone ?? '') }}" placeholder="Contoh: 081234567890" required>
                <small>Gunakan nomor aktif agar admin bisa mengirim jadwal &amp; konfirmasi.</small>
            </div>

            <div class="agr-flow-field">
                <label for="email">Alamat Email <span>(Opsional)</span></label>
                <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="nama@email.com">
            </div>

            <div class="agr-flow-field">
                <label for="instansi">Instansi / Komunitas <span>(Opsional)</span></label>
                <input id="instansi" name="instansi" type="text" value="{{ old('instansi') }}" placeholder="Kosongkan jika mendaftar pribadi">
            </div>

            <div class="agr-flow-field">
                <label for="jumlah_peserta">Jumlah Peserta <span class="is-required">*</span></label>
                <input id="jumlah_peserta"
                    name="jumlah_peserta"
                    type="number"
                    min="1"
                    max="50"
                    value="{{ $defaultParticipants }}"
                    data-unit-price="{{ $kelas['harga'] }}"
                    required>
            </div>

            {{-- Custom Calendar Picker Agrinova --}}
            <div class="agr-flow-field">
                <label for="agr-date-btn">Tanggal Mulai Pelatihan <span class="is-required">*</span></label>
                <div class="agr-datepicker" id="agr-datepicker">
                    <input type="hidden" id="tanggal_pelatihan" name="tanggal_pelatihan" value="{{ old('tanggal_pelatihan') }}" required>
                    <button type="button" id="agr-date-btn" class="agr-datepicker-trigger" aria-haspopup="dialog" aria-expanded="false">
                        <span id="agr-date-label" class="agr-datepicker-placeholder">Pilih tanggal pelatihan</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="#163a28" stroke-width="2" width="18" height="18" aria-hidden="true">
                            <rect x="3" y="4" width="18" height="18" rx="3"/>
                            <path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                    </button>

                    <div class="agr-datepicker-popover" id="agr-date-popover" role="dialog" aria-label="Pilih tanggal pelatihan">
                        <div class="agr-cal-header">
                            <button type="button" class="agr-cal-nav" id="agr-cal-prev" aria-label="Bulan sebelumnya">&lsaquo;</button>
                            <span class="agr-cal-title" id="agr-cal-month-year"></span>
                            <button type="button" class="agr-cal-nav" id="agr-cal-next" aria-label="Bulan berikutnya">&rsaquo;</button>
                        </div>
                        <div class="agr-cal-weekdays">
                            <span>Min</span><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span>
                        </div>
                        <div class="agr-cal-days" id="agr-cal-days"></div>
                    </div>
                </div>
                <small>Pilih tanggal mulai pelaksanaan pelatihan yang kamu inginkan.</small>
            </div>

            <div class="agr-flow-field">
                <label for="catatan">Catatan <span>(Opsional)</span></label>
                <textarea id="catatan" name="catatan" rows="3" placeholder="Pertanyaan atau kebutuhan khusus...">{{ old('catatan') }}</textarea>
            </div>

            {{-- Sticky Bottom Bar --}}
            <div class="agr-flow-bottom-bar">
                <div class="agr-flow-bottom-inner">
                    <div class="agr-flow-bottom-total">
                        <span>Estimasi biaya</span>
                        <strong id="edu-total-display">Rp{{ number_format($initialTotal, 0, ',', '.') }}</strong>
                    </div>
                    <button type="submit" class="agr-flow-primary-btn">Lanjut ke Konfirmasi</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const picker = document.getElementById('agr-datepicker');
    const trigger = document.getElementById('agr-date-btn');
    const hiddenInput = document.getElementById('tanggal_pelatihan');
    const labelEl = document.getElementById('agr-date-label');
    const monthYearEl = document.getElementById('agr-cal-month-year');
    const daysEl = document.getElementById('agr-cal-days');
    const prevBtn = document.getElementById('agr-cal-prev');
    const nextBtn = document.getElementById('agr-cal-next');

    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const daysName = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    let selectedDate = null;
    if (hiddenInput.value) {
        const parts = hiddenInput.value.split('-');
        if (parts.length === 3) {
            selectedDate = new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
        }
    }

    let viewYear = (selectedDate || today).getFullYear();
    let viewMonth = (selectedDate || today).getMonth();

    function formatDisplay(date) {
        return `${daysName[date.getDay()]}, ${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
    }

    function formatValue(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    if (selectedDate) {
        labelEl.textContent = formatDisplay(selectedDate);
        labelEl.classList.remove('agr-datepicker-placeholder');
    }

    function renderCalendar() {
        monthYearEl.textContent = `${months[viewMonth]} ${viewYear}`;
        prevBtn.disabled = (viewYear === today.getFullYear() && viewMonth <= today.getMonth());
        daysEl.innerHTML = '';

        const firstDay = new Date(viewYear, viewMonth, 1).getDay();
        const totalDays = new Date(viewYear, viewMonth + 1, 0).getDate();

        for (let i = 0; i < firstDay; i++) {
            const empty = document.createElement('span');
            daysEl.appendChild(empty);
        }

        for (let day = 1; day <= totalDays; day++) {
            const cellDate = new Date(viewYear, viewMonth, day);
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'agr-cal-day';
            btn.textContent = day;

            if (cellDate < today) {
                btn.disabled = true;
            }
            if (cellDate.getTime() === today.getTime()) {
                btn.classList.add('is-today');
            }
            if (selectedDate && cellDate.getTime() === selectedDate.getTime()) {
                btn.classList.add('is-selected');
            }

            btn.addEventListener('click', function () {
                selectedDate = cellDate;
                hiddenInput.value = formatValue(cellDate);
                labelEl.textContent = formatDisplay(cellDate);
                labelEl.classList.remove('agr-datepicker-placeholder');
                picker.classList.remove('is-open');
                trigger.setAttribute('aria-expanded', 'false');
                renderCalendar();
            });

            daysEl.appendChild(btn);
        }
    }

    trigger.addEventListener('click', function () {
        const isOpen = picker.classList.toggle('is-open');
        trigger.setAttribute('aria-expanded', String(isOpen));
        if (isOpen) renderCalendar();
    });

    prevBtn.addEventListener('click', function () {
        if (viewMonth === 0) {
            viewMonth = 11;
            viewYear--;
        } else {
            viewMonth--;
        }
        renderCalendar();
    });

    nextBtn.addEventListener('click', function () {
        if (viewMonth === 11) {
            viewMonth = 0;
            viewYear++;
        } else {
            viewMonth++;
        }
        renderCalendar();
    });

    document.addEventListener('click', function (e) {
        if (!picker.contains(e.target)) {
            picker.classList.remove('is-open');
            trigger.setAttribute('aria-expanded', 'false');
        }
    });

    // Validasi ringan jika user klik submit sebelum memilih tanggal
    picker.closest('form').addEventListener('submit', function (e) {
        if (!hiddenInput.value) {
            e.preventDefault();
            picker.classList.add('is-open');
            trigger.setAttribute('aria-expanded', 'true');
            renderCalendar();
            trigger.focus();
        }
    });
});
</script>
@endsection
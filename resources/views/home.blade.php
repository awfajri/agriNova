@extends('layouts.app')

@section('title', 'Kebun Melon & Sayur Segar Bekasi | Agrinova Farm')
@section('meta_description', 'Agrinova Farm: kebun melon, sayur segar, pelatihan budidaya, dan eduwisata agribisnis.')
@section('body_class', 'has-hero')

@section('content')

{{-- ===== HERO ===== --}}
@php
    $farmerLabel = 'Komunitas Petani';
@endphp

<section class="hero" id="hero">
    <div class="container hero-inner">
        <span class="hero-badge">Kebun Melon Agrinova</span>

        <h1 class="hero-title">
            Kebun melon &amp; sayur segar,<br>
            langsung dari kebun kami
        </h1>

        <p class="hero-desc">
            Belanja produk segar, pre-order panen melon, ikuti pelatihan budidaya,
            dan kunjungi eduwisata kami dalam satu tempat.
        </p>

        <div class="hero-actions">
            <a href="#" class="btn-pill btn-pill--solid">
                Belanja Sekarang
                <span class="btn-pill-icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </span>
            </a>
            <a href="#" class="btn-pill btn-pill--glass">
                Jelajahi Produk
                <span class="btn-pill-icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                </span>
            </a>
        </div>
    </div>

    <div class="hero-bottom">
        <a href="#pilar" class="scroll-hint">
            Scroll
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
        </a>

        <div class="farmer-pill">
            <div class="avatars" aria-hidden="true">
                <span>A</span><span>B</span><span>C</span>
            </div>
            {{ $farmerLabel }}
        </div>
    </div>
</section>

{{-- ===== MARQUEE ===== --}}
@php
    $marqueeItems = [
        'Melon Premium',
        'Sayur Segar Bekasi',
        'Pre-Order Panen',
        'Pelatihan Budidaya',
        'Pembekalan Pensiun',
        'Eduwisata Agribisnis',
        'Perlengkapan Kebun',
        'Pupuk',
    ];
@endphp

<section class="marquee" aria-label="Produk dan layanan Agrinova">
    <div class="marquee-track">
        @for ($i = 0; $i < 4; $i++)
            <div class="marquee-group" @if($i > 0) aria-hidden="true" @endif>
                @foreach ($marqueeItems as $item)
                    <span class="marquee-item">{{ $item }}</span>
                @endforeach
            </div>
        @endfor
    </div>
</section>

{{-- ===== TIGA PILAR (kartu menumpuk saat scroll) ===== --}}
@php
    $pillars = [
        [
            'no'    => '01',
            'tag'   => 'Produk Segar',
            'title' => 'Buah & Sayur Segar',
            'desc'  => 'Melon, buah, dan sayur dipanen langsung dari kebun Agrinova. Bisa dibeli langsung atau di-pre-order sebelum panen.',
            'cta'   => 'Lihat Produk',
            'href'  => '#',
            'theme' => 'deep',
        ],
        [
            'no'    => '02',
            'tag'   => 'Pelatihan',
            'title' => 'Pelatihan Budidaya & Pembekalan Pensiun',
            'desc'  => 'Kelas budidaya melon, anggur, dan tanaman lainnya, serta pembekalan usaha tani bagi yang memasuki masa pensiun.',
            'cta'   => 'Lihat Kelas',
            'href'  => '#',
            'theme' => 'green',
        ],
        [
            'no'    => '03',
            'tag'   => 'Eduwisata',
            'title' => 'Eduwisata Kebun',
            'desc'  => 'Kunjungan edukatif ke kebun untuk sekolah, komunitas, dan keluarga. Reservasi mudah lewat formulir online.',
            'cta'   => 'Reservasi Kunjungan',
            'href'  => '#',
            'theme' => 'sage',
        ],
    ];
@endphp

<section class="section-pilar" id="pilar">
    <div class="container">
        <div class="section-head">
            <h2>Tiga Pilar Agrinova</h2>
            <p>Produk segar, pelatihan, dan eduwisata dalam satu platform.</p>
        </div>

        <div class="pilar-stack">
            @foreach ($pillars as $i => $p)
                <article class="pilar-item" style="--i: {{ $i }}">
                    <div class="pilar-card pilar-card--{{ $p['theme'] }}">
                        <div class="pilar-text">
                            <span class="pilar-no">{{ $p['no'] }}</span>
                            <span class="pilar-tag">{{ $p['tag'] }}</span>
                            <h3>{{ $p['title'] }}</h3>
                            <p>{{ $p['desc'] }}</p>
                            <a href="{{ $p['href'] }}" class="pilar-link">
                                {{ $p['cta'] }}
                                <span aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
                                </span>
                            </a>
                        </div>
                        <div class="pilar-visual">
                            <span>Foto menyusul</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
{{-- ===== TENTANG KAMI ===== --}}
@php
    $aboutParts = [
        ['text' => 'Agrinova menghadirkan hasil kebun segar dan ilmu budidaya,', 'muted' => false],
        ['text' => 'ditambah wisata edukatif dalam satu tempat.', 'muted' => true],
    ];
    $w = 0;
@endphp

<section class="section-about" id="tentang">
    <div class="container">
        <div class="text-center">
            <span class="eyebrow">Tentang Kami</span>
        </div>

        <h2 class="scroll-text" data-scroll-text>
            @foreach ($aboutParts as $part)
                @foreach (explode(' ', $part['text']) as $word)
                    <span class="word {{ $part['muted'] ? 'word--muted' : '' }}" style="--w: {{ $w }}">{{ $word }}</span>
                    @php $w++; @endphp
                @endforeach
            @endforeach
        </h2>

        <div class="bento">
            <div class="bento-card bento-a">
                <span class="bento-photo">Foto menyusul</span>
                <div class="bento-a-box">
                    <div class="bento-number"><span class="counter" data-target="3">3</span></div>
                    <p>Pilar bisnis dalam satu platform: produk segar, pelatihan, dan eduwisata.</p>
                </div>
            </div>

            <div class="bento-card bento-b">
                <span class="bento-label">Pre-order panen melon</span>
                <div class="bento-number">H-<span class="counter" data-target="14">14</span></div>
                <p>Pesan sebelum panen, stok lebih tertata.</p>
            </div>

            <div class="bento-card bento-c">
                <span class="bento-label">Kategori produk</span>
                <div class="bento-number"><span class="counter" data-target="3">3</span></div>
                <p>Buah &amp; Sayur, Perlengkapan Kebun, dan Pupuk.</p>
            </div>

            <div class="bento-card bento-d">
                <div>
                    <span class="bento-label">Pesan online</span>
                    <p class="mb-0">Tidak perlu menunggu balasan chat satu per satu.</p>
                </div>
                <div class="bento-number">24/7</div>
            </div>
        </div>
    </div>
</section>
{{-- ===== PRODUK UNGGULAN ===== --}}
@php
    // DATA CONTOH: nanti diganti data dari database (modul katalog & Kelola Pre-Order)
    $harvestAt = now()->addDays(12)->setTime(7, 0)->toIso8601String();

    $products = [
        ['name' => 'Melon Premium',       'category' => 'Buah & Sayur',       'price' => 'Rp 35.000', 'unit' => '/kg',   'preorder' => true],
        ['name' => 'Sayur Segar Pilihan', 'category' => 'Buah & Sayur',       'price' => 'Rp 15.000', 'unit' => '/ikat', 'preorder' => false],
        ['name' => 'Pupuk Organik',       'category' => 'Pupuk',              'price' => 'Rp 45.000', 'unit' => '/pack', 'preorder' => false],
        ['name' => 'Paket Perlengkapan',  'category' => 'Perlengkapan Kebun', 'price' => 'Rp 90.000', 'unit' => '/set',  'preorder' => false],
    ];
@endphp

<section class="section-produk" id="produk">
    <div class="container">
        <div class="section-head">
            <h2>Produk Unggulan</h2>
            <p>Segar dari kebun, siap dipesan kapan saja.</p>
        </div>

        {{-- Banner countdown pre-order --}}
        <div class="preorder-banner" data-countdown="{{ $harvestAt }}">
            <div class="preorder-text">
                <span class="eyebrow eyebrow--light">Pre-Order Panen Melon</span>
                <h3>Panen berikutnya dimulai dalam</h3>
                <p>Pre-order dibuka H-14 sebelum panen, amankan melonmu lebih awal.</p>
                <a href="#" class="btn-pill btn-pill--solid">
                    Pre-Order Sekarang
                    <span class="btn-pill-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
                    </span>
                </a>
            </div>

            <div class="countdown" role="timer" aria-label="Hitung mundur menuju panen melon">
                <div class="cd-box"><span data-cd="days">00</span><small>Hari</small></div>
                <div class="cd-box"><span data-cd="hours">00</span><small>Jam</small></div>
                <div class="cd-box"><span data-cd="minutes">00</span><small>Menit</small></div>
                <div class="cd-box"><span data-cd="seconds">00</span><small>Detik</small></div>
            </div>
        </div>

        {{-- Kartu produk (tilt saat hover) --}}
        <div class="product-grid">
            @foreach ($products as $p)
                <article class="product-card" data-tilt>
                    <div class="product-media">
                        @if ($p['preorder'])
                            <span class="product-badge">Pre-Order</span>
                        @endif
                        <span class="product-ph">Foto menyusul</span>
                    </div>

                    <div class="product-info">
                        <span class="product-cat">{{ $p['category'] }}</span>
                        <h3>{{ $p['name'] }}</h3>
                        <div class="product-row">
                            <div class="product-price">{{ $p['price'] }}<small>{{ $p['unit'] }}</small></div>
                            <button type="button" class="product-add" aria-label="Tambah {{ $p['name'] }} ke keranjang">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
{{-- ===== FASILITAS GREEN HOUSE (hotspot modal) ===== --}}
@php
    // TEKS CONTOH: ganti dengan data resmi Agrinova. x & y = posisi titik dalam persen.
    $hotspots = [
        ['title' => 'Green House',          'x' => 27, 'y' => 44, 'desc' => 'Bangunan green house tempat tanaman dibudidayakan dalam lingkungan yang lebih terkontrol.'],
        ['title' => 'Area Budidaya Melon',  'x' => 61, 'y' => 56, 'desc' => 'Area tanam melon. Dari sini panen untuk penjualan dan pre-order berasal.'],
        ['title' => 'Area Kegiatan Edukasi','x' => 44, 'y' => 74, 'desc' => 'Area untuk kegiatan edukasi dan pelatihan bagi pengunjung eduwisata.'],
        ['title' => 'Parkir Bus',           'x' => 80, 'y' => 30, 'desc' => 'Area parkir untuk rombongan sekolah dan komunitas yang datang dengan bus.'],
    ];
@endphp

<section class="section-fasilitas" id="fasilitas">
    <div class="container">
        <div class="section-head">
            <h2>Jelajahi Fasilitas Kebun</h2>
            <p>Klik titik pada foto untuk melihat info tiap fasilitas.</p>
        </div>

        {{-- Kalau foto sudah ada, tambahkan di tag di bawah:
             style="--stage-img: url('{{ asset('images/greenhouse.webp') }}')"
             lalu hapus <span class="hotspot-ph"> --}}
        <div class="hotspot-stage">
            <span class="hotspot-ph">Foto green house menyusul</span>

            @foreach ($hotspots as $i => $h)
                <button type="button" class="hotspot"
                        style="--x: {{ $h['x'] }}%; --y: {{ $h['y'] }}%"
                        data-hotspot data-index="{{ $i }}"
                        data-title="{{ $h['title'] }}" data-desc="{{ $h['desc'] }}"
                        aria-label="Lihat info {{ $h['title'] }}">
                    <span class="hotspot-dot"></span>
                    <span class="hotspot-tip">{{ $h['title'] }}</span>
                </button>
            @endforeach
        </div>

        <div class="hotspot-list">
            @foreach ($hotspots as $i => $h)
                <button type="button" class="hotspot-chip" data-hotspot data-index="{{ $i }}">
                    <span class="hotspot-chip-no">{{ $i + 1 }}</span>
                    {{ $h['title'] }}
                </button>
            @endforeach
        </div>
    </div>
</section>

{{-- Modal fasilitas (satu modal dipakai semua titik) --}}
<div class="modal fade" id="facilityModal" tabindex="-1" aria-labelledby="facilityTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content facility-modal">
            <button type="button" class="facility-close" data-bs-dismiss="modal" aria-label="Tutup">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>

            <div class="facility-media"><span>Foto menyusul</span></div>

            <div class="facility-body">
                <span class="pilar-tag">Fasilitas</span>
                <h3 id="facilityTitle">Judul fasilitas</h3>
                <p id="facilityDesc">Deskripsi fasilitas.</p>

                <div class="facility-nav">
                    <span class="facility-count" id="facilityCount">1 / 4</span>
                    <div class="facility-nav-btns">
                        <button type="button" class="facility-btn" id="facilityPrev" aria-label="Fasilitas sebelumnya">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                        </button>
                        <button type="button" class="facility-btn" id="facilityNext" aria-label="Fasilitas berikutnya">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- ===== TEASER GALERI (linear modal) ===== --}}
@php
    // TEKS CONTOH: ganti dengan keterangan asli. Kalau foto sudah ada, isi 'img' => 'images/galeri/nama-file.webp'
    $gallery = [
        ['cat' => 'Panen',     'title' => 'Panen Melon',        'desc' => 'Suasana panen melon di kebun Agrinova.'],
        ['cat' => 'Eduwisata', 'title' => 'Kunjungan Sekolah',  'desc' => 'Rombongan sekolah belajar langsung di kebun.'],
        ['cat' => 'Fasilitas', 'title' => 'Green House',        'desc' => 'Green house tempat tanaman dibudidayakan.'],
        ['cat' => 'Pelatihan', 'title' => 'Kelas Budidaya',     'desc' => 'Peserta pelatihan praktik budidaya tanaman.'],
        ['cat' => 'Panen',     'title' => 'Sayur Segar',        'desc' => 'Sayur segar yang dipetik langsung dari kebun.'],
        ['cat' => 'Eduwisata', 'title' => 'Kegiatan Edukasi',   'desc' => 'Kegiatan edukasi bagi komunitas dan keluarga.'],
        ['cat' => 'Fasilitas', 'title' => 'Area Kebun',         'desc' => 'Hamparan area kebun Agrinova.'],
    ];
@endphp

<section class="section-galeri" id="galeri">
    <div class="container">
        <div class="section-head">
            <h2>Galeri Kebun &amp; Kegiatan</h2>
            <p>Intip suasana panen, eduwisata, dan fasilitas Agrinova.</p>
        </div>

        <div class="gallery-grid">
            @foreach ($gallery as $i => $g)
                @php $img = isset($g['img']) ? asset($g['img']) : null; @endphp
                <button type="button"
                        class="gallery-card g-{{ $i }} gv-{{ $i % 5 }} {{ $img ? 'has-img' : '' }}"
                        @if($img) style="--img: url('{{ $img }}')" data-img="{{ $img }}" @endif
                        data-gallery-card
                        data-title="{{ $g['title'] }}" data-cat="{{ $g['cat'] }}" data-desc="{{ $g['desc'] }}"
                        aria-label="Buka foto {{ $g['title'] }}">
                    @unless($img)<span class="gallery-ph">Foto menyusul</span>@endunless
                    <span class="gallery-cap">
                        <span class="gallery-cat">{{ $g['cat'] }}</span>
                        <strong>{{ $g['title'] }}</strong>
                    </span>
                    <span class="gallery-plus" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                    </span>
                </button>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="#" class="btn-pill btn-pill--solid">
                Lihat Galeri Lengkap
                <span class="btn-pill-icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
                </span>
            </a>
        </div>
    </div>
</section>

{{-- Lightbox galeri --}}
<div class="lightbox" id="lightbox" hidden>
    <div class="lightbox-backdrop"></div>

    <div class="lightbox-panel" role="dialog" aria-modal="true" aria-labelledby="lbTitle">
        <button type="button" class="lightbox-close" aria-label="Tutup">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>

        <div class="lightbox-media gv-0"><span>Foto menyusul</span></div>

        <div class="lightbox-body">
            <span class="pilar-tag" id="lbTag">Kategori</span>
            <h3 id="lbTitle">Judul</h3>
            <p id="lbDesc">Deskripsi.</p>

            <div class="facility-nav">
                <span class="facility-count" id="lbCount">1 / 7</span>
                <div class="facility-nav-btns">
                    <button type="button" class="facility-btn" id="lbPrev" aria-label="Foto sebelumnya">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button type="button" class="facility-btn" id="lbNext" aria-label="Foto berikutnya">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- ===== FAQ (accordion) ===== --}}
@php
    // TEKS CONTOH berdasarkan dokumen System Requirement: ganti/lengkapi dengan data resmi Agrinova
    $faqs = [
        ['q' => 'Bagaimana cara pre-order melon?',
         'a' => 'Pre-order aktif H-14 sebelum panen. Pilih produk yang bertanda Pre-Order, lalu selesaikan checkout. Instruksi selanjutnya tampil di halaman sukses order.'],
        ['q' => 'Apakah harus membeli lewat marketplace?',
         'a' => 'Tidak. Checkout dilakukan langsung di website ini, tanpa dialihkan ke marketplace.'],
        ['q' => 'Bagaimana cara pembayarannya?',
         'a' => 'Pada tahap awal, pembayaran dikonfirmasi secara manual oleh admin. Petunjuk pembayaran ada di halaman sukses order.'],
        ['q' => 'Bagaimana cara mendaftar pelatihan?',
         'a' => 'Pilih kelas pelatihan (pembekalan pensiun atau budidaya), lalu isi formulir pendaftaran online. Status pendaftaranmu dikelola oleh admin.'],
        ['q' => 'Bagaimana cara reservasi eduwisata?',
         'a' => 'Isi formulir reservasi kunjungan. Admin akan mengonfirmasi atau menolak reservasi sesuai kapasitas kebun.'],
        ['q' => 'Produk apa saja yang tersedia?',
         'a' => 'Tersedia tiga kategori: Buah & Sayur, Perlengkapan Kebun, dan Pupuk.'],
    ];
    $faqCols = array_chunk($faqs, 3);
@endphp

<section class="section-faq" id="faq">
    <div class="container">
        <div class="section-head">
            <h2>Pertanyaan yang Sering Diajukan</h2>
            <p>Jawaban singkat untuk hal yang paling sering ditanyakan.</p>
        </div>

        <div class="faq-cols">
            @foreach ($faqCols as $c => $col)
                <div class="accordion faq-acc" id="faqAcc{{ $c }}">
                    @foreach ($col as $j => $f)
                        @php $id = "faq-{$c}-{$j}"; @endphp
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button {{ $j === 0 ? '' : 'collapsed' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#{{ $id }}"
                                        aria-expanded="{{ $j === 0 ? 'true' : 'false' }}" aria-controls="{{ $id }}">
                                    {{ $f['q'] }}
                                </button>
                            </h3>
                            <div id="{{ $id }}" class="accordion-collapse collapse {{ $j === 0 ? 'show' : '' }}"
                                 data-bs-parent="#faqAcc{{ $c }}">
                                <div class="accordion-body">{{ $f['a'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA WHATSAPP ===== --}}
<section class="section-cta">
    <div class="container">
        <div class="cta-card">
            <div>
                <span class="eyebrow eyebrow--light">Hubungi Kami</span>
                <h2>Masih ada pertanyaan seputar Agrinova?</h2>
                <p>Tim kami siap membantu soal produk, pre-order, pelatihan, dan kunjungan eduwisata.</p>
            </div>

            <a href="{{ config('agrinova.whatsapp_url') }}" class="btn-pill btn-pill--light"
               @if (config('agrinova.whatsapp')) target="_blank" rel="noopener" @endif>
                Chat WhatsApp
                <span class="btn-pill-icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                </span>
            </a>
        </div>
    </div>
</section>
@endsection
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Lengkapi Pendaftaran – Agrinova</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --forest: #12544f;
    --forest-deep: #0d3f3b;
    --leaf: #4c9a64;
    --leaf-soft: rgba(76, 154, 100, .18);
    --mint: #f1f7f4;
    --sage: #e3efe8;
    --ink: #10302c;
    --muted: #5e7a74;
    --line: #d3e2db;
    --field: #fbfdfc;
    --white: #ffffff;
    --error: #b3372b;
    --error-soft: rgba(179, 55, 43, .14);
  }

  *, *::before, *::after { box-sizing: border-box; }
  html { -webkit-text-size-adjust: 100%; }

  body {
    margin: 0;
    min-height: 100vh;
    background: var(--mint);
    color: var(--ink);
    font-family: "Plus Jakarta Sans", "Segoe UI", system-ui, -apple-system, Roboto, Arial, sans-serif;
    font-size: 16px;
    line-height: 1.5;
  }

  .visually-hidden {
    position: absolute;
    width: 1px;
    height: 1px;
    margin: -1px;
    padding: 0;
    overflow: hidden;
    clip: rect(0 0 0 0);
    white-space: nowrap;
    border: 0;
  }

  main { padding: 48px 20px 40px; }

  .card {
    width: 100%;
    max-width: 480px;
    margin: 0 auto;
    padding: 36px 40px 40px;
    background: var(--white);
    border: 1px solid var(--line);
    border-radius: 28px;
    box-shadow: 0 24px 48px -28px rgba(18, 84, 79, .35);
  }

  .brand {
    display: block;
    color: var(--forest);
    font-size: 24px;
    font-weight: 800;
    letter-spacing: -.02em;
    text-decoration: none;
  }

  /* Penanda langkah */
  .progress { display: flex; gap: 8px; margin-top: 22px; }
  .progress span {
    flex: 1;
    height: 5px;
    background: var(--sage);
    border-radius: 999px;
    transition: background-color .2s ease;
  }
  .progress span.done { background: var(--forest); }

  h1 {
    margin: 24px 0 0;
    font-size: 30px;
    font-weight: 800;
    letter-spacing: -.025em;
    line-height: 1.15;
  }
  h1:focus { outline: none; }
  .lead { margin: 8px 0 0; color: var(--muted); font-size: 15px; }

  .step[hidden] { display: none; }

  .noscript {
    margin: 0 0 20px;
    padding: 12px 14px;
    background: var(--error-soft);
    color: var(--error);
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
  }

  /* ---------- Field ---------- */
  .fields { margin-top: 28px; }
  .field + .field { margin-top: 22px; }

  label.title {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 600;
  }

  .control { position: relative; }
  .control svg {
    position: absolute;
    left: 16px;
    top: 15px;
    color: var(--muted);
    pointer-events: none;
    transition: color .15s ease;
  }
  .control:focus-within svg { color: var(--leaf); }

  input[type="text"], input[type="tel"] {
    display: block;
    width: 100%;
    height: 52px;
    padding: 0 16px 0 48px;
    background: var(--field);
    color: var(--ink);
    border: 1.5px solid var(--line);
    border-radius: 14px;
    font: inherit;
    font-size: 16px;
    transition: border-color .15s ease, box-shadow .15s ease, background-color .15s ease;
  }
  input::placeholder { color: #8da6a0; }
  input:hover { border-color: #b6cec5; }
  input:focus {
    outline: none;
    background: var(--white);
    border-color: var(--leaf);
    box-shadow: 0 0 0 4px var(--leaf-soft);
  }
  input[aria-invalid="true"] { border-color: var(--error); }
  input[aria-invalid="true"]:focus { box-shadow: 0 0 0 4px var(--error-soft); }
  .control:has(input[aria-invalid="true"]) svg { color: var(--error); }

  .hint { margin: 8px 0 0; color: var(--muted); font-size: 13px; }
  .error { margin: 8px 0 0; color: var(--error); font-size: 13px; font-weight: 600; }
  .error[hidden] { display: none; }
  /* ---------- Checkbox ---------- */
  .optin {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-top: 26px;
    cursor: pointer;
  }
  .optin input {
    flex: none;
    width: 22px;
    height: 22px;
    margin: 1px 0 0;
    accent-color: var(--forest);
    cursor: pointer;
  }
  .optin input:focus-visible { outline: 3px solid var(--leaf); outline-offset: 2px; }
  .optin .main { display: block; font-size: 15px; font-weight: 600; line-height: 1.35; }
  .optin .sub { display: block; margin-top: 2px; color: var(--muted); font-size: 13px; }

  /* ---------- Kotak ketentuan ---------- */
  .terms {
    height: 320px;
    margin-top: 26px;
    padding: 18px 20px;
    overflow-y: auto;
    background: var(--field);
    border: 1.5px solid var(--line);
    border-radius: 14px;
    scrollbar-width: thin;
    scrollbar-color: var(--leaf) var(--sage);
  }
  .terms:focus-visible { outline: 3px solid var(--leaf); outline-offset: 2px; }
  .terms h2 { margin: 0 0 6px; font-size: 15px; font-weight: 700; }
  .terms h2:not(:first-child) { margin-top: 20px; }
  .terms p { margin: 0 0 8px; color: var(--ink); font-size: 14px; line-height: 1.6; }
  .terms ul { margin: 8px 0 0; padding-left: 20px; }
  .terms li { margin-bottom: 6px; font-size: 14px; line-height: 1.55; }
  .terms li::marker { color: var(--leaf); }
  .agree-note { margin: 14px 0 0; color: var(--muted); font-size: 13px; }

  /* ---------- Tombol ---------- */
  .actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-top: 32px;
  }

  .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 52px;
    padding: 0 28px;
    border: 0;
    border-radius: 14px;
    font: inherit;
    font-size: 16px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: background-color .15s ease, transform .1s ease;
  }
  .btn:focus-visible { outline: 3px solid var(--leaf); outline-offset: 3px; }
  .btn:active { transform: translateY(1px); }

  .btn-primary { min-width: 148px; background: var(--forest); color: var(--white); }
  .btn-primary:hover { background: var(--forest-deep); }
  .btn-primary[disabled] { opacity: .65; cursor: progress; }

  .btn-ghost { padding: 0 16px; margin-left: -16px; background: transparent; color: var(--ink); }
  .btn-ghost:hover { background: var(--sage); }

  .support { margin: 24px 0 0; text-align: center; font-size: 14px; }
  .support-line { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 4px 10px; }
  .support .dot { color: var(--muted); }
  .support-link {
    color: var(--forest);
    font-weight: 600;
    text-underline-offset: 4px;
  }
  .support-link:focus-visible { outline: 3px solid var(--leaf); outline-offset: 4px; border-radius: 4px; }
  .logout-button {
    padding: 0;
    background: none;
    border: 0;
    color: var(--forest);
    font: inherit;
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 4px;
    cursor: pointer;
  }
  .logout-button:focus-visible { outline: 3px solid var(--leaf); outline-offset: 4px; border-radius: 4px; }

  @media (max-width: 520px) {
    main { padding: 20px 12px 32px; }
    .card { padding: 28px 22px 32px; border-radius: 24px; }
    h1 { font-size: 27px; }
    .terms { height: 300px; }
  }

  @media (prefers-reduced-motion: reduce) {
    * { transition: none !important; }
  }
</style>
</head>
<body>

<main>
  <form class="card" id="daftar" method="post" action="{{ route('registration.complete.store') }}" novalidate>
    @csrf
    <input type="hidden" name="step" id="stepField" value="{{ (int) $startStep }}">
    {{-- Persetujuan dianggap diberikan saat tombol Saya Setuju ditekan. --}}
    <input type="hidden" name="setuju" value="1">

    <a class="brand" href="{{ route('home') }}">Agrinova</a>

    <noscript><p class="noscript">Aktifkan JavaScript di peramban Anda untuk melanjutkan pendaftaran.</p></noscript>    <div class="progress" aria-hidden="true">
      <span class="done" id="bar1"></span>
      <span id="bar2" @if ($startStep === 2) class="done" @endif></span>
    </div>
    <p class="visually-hidden" id="stepInfo" aria-live="polite">Langkah {{ $startStep }} dari 2</p>

    <!-- Langkah 1: Lengkapi pendaftaran -->
    <section class="step" id="step1" aria-labelledby="judul1" @if ($startStep === 2) hidden @endif>
      <h1 id="judul1" tabindex="-1">Lengkapi pendaftaran</h1>
      <p class="lead">Data ini dipakai untuk memproses pesanan Anda.</p>

      <div class="fields">
        <div class="field">
          <label class="title" for="nama">Nama Lengkap</label>
          <div class="control">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/></svg>
            <input type="text" id="nama" name="nama" value="{{ old('nama', $user->name) }}" autocomplete="name" aria-describedby="namaInfo namaError" @if ($errors->has('nama')) aria-invalid="true" @endif required>
          </div>
          <p class="hint" id="namaInfo">Nama ini tampil di profil Anda.</p>
          <p class="error" id="namaError" @if (! $errors->has('nama')) hidden @endif>{{ $errors->first('nama') ?: 'Nama lengkap wajib diisi.' }}</p>
        </div>

        <div class="field">
          <label class="title" for="whatsapp">Nomor WhatsApp</label>
          <div class="control">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 16.5v3a1.8 1.8 0 0 1-2 1.8A17.5 17.5 0 0 1 2.7 5a1.8 1.8 0 0 1 1.8-2h3a1.8 1.8 0 0 1 1.8 1.5c.1 1 .4 1.9.7 2.8a1.8 1.8 0 0 1-.4 1.9L8.3 10.5a14 14 0 0 0 5.2 5.2l1.3-1.3a1.8 1.8 0 0 1 1.9-.4c.9.3 1.8.6 2.8.7a1.8 1.8 0 0 1 1.5 1.8z"/></svg>
            <input type="tel" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $user->phone) }}" placeholder="081234567890" inputmode="tel" autocomplete="tel" aria-describedby="waInfo waError" @if ($errors->has('whatsapp')) aria-invalid="true" @endif required>
          </div>
          <p class="hint" id="waInfo">Gunakan nomor aktif untuk konfirmasi pesanan.</p>
          <p class="error" id="waError" @if (! $errors->has('whatsapp')) hidden @endif>{{ $errors->first('whatsapp') ?: 'Masukkan nomor WhatsApp yang valid, contoh 081234567890.' }}</p>
        </div>
      </div>

      <label class="optin" for="promo">
        <input type="checkbox" id="promo" name="promo" value="1" @checked(old('promo', $user->promo_opt_in))>
        <span>
          <span class="main">Kirimi saya info dan promo Agrinova</span>
          <span class="sub">Bisa berhenti kapan saja.</span>
        </span>
      </label>

      <div class="actions">
        <a class="btn btn-ghost" href="{{ route('home') }}">Batal</a>
        <button type="button" class="btn btn-primary" id="lanjut">Lanjut</button>
      </div>
    </section>

    <!-- Langkah 2: Privasi dan ketentuan -->
    <section class="step" id="step2" aria-labelledby="judul2" @if ($startStep !== 2) hidden @endif>
      <h1 id="judul2" tabindex="-1">Privasi dan Ketentuan</h1>
      <p class="lead">Baca sebentar sebelum akun Anda dibuat.</p>

      <div class="terms" tabindex="0" role="region" aria-label="Isi privasi dan ketentuan">
        <h2>Data yang kami kumpulkan</h2>
        <p>Saat Anda mendaftar, kami menyimpan nama lengkap, alamat email akun ({{ $user->email }}), dan nomor WhatsApp Anda. Kami juga mencatat data penggunaan, seperti halaman yang dibuka dan produk yang dilihat.</p>

        <h2>Mengapa kami memproses data ini</h2>
        <p>Kami memproses data ini untuk tujuan yang dijelaskan dalam Kebijakan Privasi, termasuk untuk:</p>
        <ul>
          <li>Menjalankan layanan, seperti memproses pesanan dan mengatur pengiriman produk yang Anda beli</li>
          <li>Meningkatkan keamanan dengan melindungi akun dari penipuan dan penyalahgunaan</li>
          <li>Mengirim pesan terkait pesanan lewat WhatsApp, serta info dan promo jika Anda memilihnya</li>
          <li>Melakukan analisis untuk memahami bagaimana layanan digunakan</li>
        </ul>

        <h2>Hak Anda</h2>
        <p>Anda dapat mengubah nomor WhatsApp dan alamat pengiriman kapan saja di halaman Profil Saya. Anda juga dapat berhenti menerima info dan promo, atau meminta penghapusan akun, melalui halaman Bantuan.</p>
      </div>

      <p class="error" id="setujuError" @if (! $errors->has('setuju')) hidden @endif>{{ $errors->first('setuju') }}</p>
      <p class="agree-note">Dengan menekan Saya Setuju, Anda menyetujui <a class="support-link" href="{{ route('terms') }}" target="_blank" rel="noopener">Ketentuan Layanan dan Kebijakan Privasi Agrinova</a>.</p>

      <div class="actions">
        <button type="button" class="btn btn-ghost" id="kembali">Kembali</button>
        <button type="submit" class="btn btn-primary" id="setuju">Saya Setuju</button>
      </div>
    </section>
  </form>

  <div class="support">
    <p class="support-line">
      @if ($supportUrl)
        <a class="support-link" href="{{ $supportUrl }}" target="_blank" rel="noopener">Hubungi Kami / Bantuan</a>
        <span class="dot" aria-hidden="true">·</span>
      @endif
      <form method="post" action="{{ route('auth.logout') }}" style="display:inline">
        @csrf
        <button type="submit" class="logout-button">Keluar akun</button>
      </form>
    </p>
  </div>
</main>

<script>
  // Alur dua langkah: Lengkapi pendaftaran -> Privasi dan Ketentuan.
  (function () {
    var form = document.getElementById('daftar');
    var step1 = document.getElementById('step1');
    var step2 = document.getElementById('step2');
    var bar2 = document.getElementById('bar2');
    var info = document.getElementById('stepInfo');
    var stepField = document.getElementById('stepField');
    var nama = document.getElementById('nama');
    var wa = document.getElementById('whatsapp');
    var promo = document.getElementById('promo');
    var namaError = document.getElementById('namaError');
    var waError = document.getElementById('waError');
    var setujuError = document.getElementById('setujuError');
    var btnLanjut = document.getElementById('lanjut');
    var btnSetuju = document.getElementById('setuju');
    var current = stepField.value === '2' ? 2 : 1;

    // 08xx..., 628xx..., atau +628xx... (9-13 digit setelah awalan)
    var WA_PATTERN = /^(\+62|62|0)8[1-9][0-9]{6,11}$/;
    // Simpan nilai input agar tetap utuh setelah pindah langkah.
    var draft = { nama: nama.value, whatsapp: wa.value, promo: promo.checked };

    function setError(input, errorEl, show) {
      input.setAttribute('aria-invalid', show ? 'true' : 'false');
      errorEl.hidden = !show;
    }

    function validate() {
      var okNama = nama.value.trim().length > 0;
      var okWa = WA_PATTERN.test(wa.value.replace(/[\s\-().]/g, ''));
      setError(nama, namaError, !okNama);
      setError(wa, waError, !okWa);
      if (!okNama) { nama.focus(); return false; }
      if (!okWa) { wa.focus(); return false; }
      return true;
    }

    function show(n) {
      current = n;
      stepField.value = String(n);
      step1.hidden = n !== 1;
      step2.hidden = n !== 2;
      bar2.className = n === 2 ? 'done' : '';
      info.textContent = 'Langkah ' + n + ' dari 2';
      var heading = document.getElementById(n === 1 ? 'judul1' : 'judul2');
      heading.focus();
      window.scrollTo(0, 0);
    }

    btnLanjut.addEventListener('click', function () {
      if (validate()) show(2);
    });

    document.getElementById('kembali').addEventListener('click', function () {
      setujuError.hidden = true;
      show(1);
    });

    // Lalu lintas asli baru dikirim saat pengguna menekan "Saya Setuju".
    form.addEventListener('submit', function (e) {
      if (current !== 2) {
        e.preventDefault();
        if (validate()) show(2);
        return;
      }

      if (!validate()) {
        e.preventDefault();
        show(1);
        return;
      }

      btnSetuju.disabled = true;
      btnSetuju.textContent = 'Menyimpan...';
    });

    nama.addEventListener('input', function () {
      draft.nama = nama.value;
      setError(nama, namaError, false);
    });

    wa.addEventListener('input', function () {
      draft.whatsapp = wa.value;
      setError(wa, waError, false);
    });

    promo.addEventListener('change', function () {
      draft.promo = promo.checked;
    });

    // Kembalikan isian saat halaman dimuat ulang oleh peramban.
    window.addEventListener('pageshow', function () {
      if (!nama.value) { nama.value = draft.nama; }
      if (!wa.value) { wa.value = draft.whatsapp; }
      stepField.value = String(current);
      btnSetuju.disabled = false;
      btnSetuju.textContent = 'Saya Setuju';
    });
  })();
</script>
</body>
</html>

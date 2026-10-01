<footer class="site-footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <a class="footer-brand font-heading" href="{{ route('home') }}">Agrinova</a>
                <p class="footer-desc">Kebun melon, sayur segar, pelatihan budidaya, dan eduwisata agribisnis.</p>
            </div>

            <div class="col-6 col-lg-3">
                <h6>Menu</h6>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="#">Profil</a></li>
                    <li><a href="{{ route('home') }}#produk">Produk</a></li>
                    <li><a href="#">Pelatihan</a></li>
                    <li><a href="#">Eduwisata</a></li>
                    <li><a href="{{ route('home') }}#galeri">Galeri</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-4">
                <h6>Kontak</h6>
                <ul class="footer-links">
                    <li>{{ config('agrinova.address') ?: 'Alamat: (menunggu dari Agrinova)' }}</li>
                    <li>
                        @if (config('agrinova.whatsapp'))
                            <a href="{{ config('agrinova.whatsapp_url') }}" target="_blank" rel="noopener">WhatsApp: +{{ config('agrinova.whatsapp') }}</a>
                        @else
                            WhatsApp: (menunggu dari Agrinova)
                        @endif
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} PT Agrinova. Seluruh hak cipta dilindungi.</span>
        </div>
    </div>
</footer>
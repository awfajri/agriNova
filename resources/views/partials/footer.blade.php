<footer class="site-footer">
    <div class="container">
        <div class="footer-topline">
            <span>AGRINOVA FARM</span>
            <span>Kebun Melon Zona Hijau Indonesia</span>
        </div>

        <div class="footer-grid">
            <div class="footer-intro">
                <a class="footer-brand font-heading" href="{{ route('home') }}">Agrinova</a>
                <p class="footer-desc">Hasil kebun segar, pengetahuan budidaya, dan pengalaman eduwisata dari satu tempat.</p>
                <a href="{{ route('catalog') }}" class="footer-shop-link">Belanja produk <span aria-hidden="true">↗</span></a>
            </div>

            <div class="footer-column">
                <h6>Jelajahi</h6>
                <a href="{{ route('home') }}#tentang">Profil</a>
                <a href="{{ route('catalog') }}">Produk</a>
                <a href="{{ route('pelatihan.index') }}">Pelatihan</a>
                <a href="{{ route('eduwisata.index') }}">Eduwisata</a>
            </div>

            <div class="footer-column footer-contact">
                <h6>Hubungi Agrinova</h6>
                <span>{{ config('agrinova.address') ?: 'Alamat menunggu konfirmasi Agrinova' }}</span>
                @if (config('agrinova.whatsapp'))
                    <a href="{{ config('agrinova.whatsapp_url') }}" target="_blank" rel="noopener">WhatsApp +{{ config('agrinova.whatsapp') }}</a>
                @else
                    <span>WhatsApp menunggu konfirmasi Agrinova</span>
                @endif
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} PT Agrinova</span>
            <span>Segar dari kebun, dekat denganmu.</span>
        </div>
    </div>
</footer>
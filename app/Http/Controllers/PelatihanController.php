<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    private function getDaftarKelas(): array
    {
        return [
            'budidaya-melon-hidroponik' => [
                'slug' => 'budidaya-melon-hidroponik',
                'kategori' => 'Budidaya Tanaman',
                'judul' => 'Budidaya Melon Green House',
                'harga' => 450000,
                'durasi' => '2 Hari Praktik',
                'jadwal' => '14–15 Nov 2026',
                'kuota' => 'Sisa 8 Kursi',
                'status' => 'Tersedia',
                'is_available' => true,
                'highlight' => ['Modul & Sertifikat', 'Bibit & AB Mix', 'Praktik Kebun'],
                'ringkasan' => 'Praktik semai, racik nutrisi AB Mix, hingga panen melon premium.',
                'deskripsi' => 'Kuasa standar budidaya melon eksklusif di dalam Green House Agrinova. Peserta belajar langsung manajemen iklim mikro, polinasi buatan, hingga seleksi buah kualitas supermarket.',
                'materi' => [
                    'Pengenalan varietas melon & instalasi Green House',
                    'Manajemen nutrisi fertigasi (AB Mix) & kontrol EC/pH',
                    'Teknik polinasi, seleksi buah, dan pemangkasan cabang',
                    'Analisis usaha tani & kemitraan pasar hasil panen',
                ],
            ],
            'pembekalan-pensiun-agribisnis' => [
                'slug' => 'pembekalan-pensiun-agribisnis',
                'kategori' => 'Pembekalan Pensiun',
                'judul' => 'Wirausaha Tani Masa Pensiun',
                'harga' => 1250000,
                'durasi' => '3 Hari Intensif',
                'jadwal' => '20–22 Nov 2026',
                'kuota' => 'Sisa 5 Kursi',
                'status' => 'Dibuka',
                'is_available' => true,
                'highlight' => ['Perencanaan Bisnis', 'Tur Kebun Mitra', 'Konsultasi Privat'],
                'ringkasan' => 'Rancang bisnis kebun produktif & terukur jelang masa purnabakti.',
                'deskripsi' => 'Program khusus bagi karyawan menjelang masa purnabakti yang ingin membangun agribisnis mandiri. Mencakup hitung RAB Green House, pemilihan komoditas, hingga manajemen risiko.',
                'materi' => [
                    'Mindset wirausaha & manajemen risiko di masa pensiun',
                    'Perancangan RAB Green House skala rumahan & komersial',
                    'Praktik budidaya melon, anggur, dan sayur hidroponik',
                    'Simulasi finansial & studi kasus kebun mitra Agrinova',
                ],
            ],
            'budidaya-anggur-dan-sayur' => [
                'slug' => 'budidaya-anggur-dan-sayur',
                'kategori' => 'Budidaya Tanaman',
                'judul' => 'Anggur & Sayur Hidroponik',
                'harga' => 350000,
                'durasi' => '1 Hari (6 Jam)',
                'jadwal' => '28 Nov 2026',
                'kuota' => 'Kuota Penuh',
                'status' => 'Penuh',
                'is_available' => false,
                'highlight' => ['Teknik Pruning', 'Sistem NFT/DFT', 'Starter Kit'],
                'ringkasan' => 'Teknik pembuahan anggur meja & instalasi sayur di lahan sempit.',
                'deskripsi' => 'Pelajari cara membuahkan anggur secara terjadwal dan memproduksi sayuran daun bebas pestisida menggunakan sistem hidroponik modern.',
                'materi' => [
                    'Pemilihan bibit anggur impor & media tanam poros',
                    'Teknik pruning (pemangkasan) pembuahan bertingkat',
                    'Instalasi hidroponik sayur bebas pestisida',
                    'Penanganan pascapanen dan pengemasan ritel',
                ],
            ],
        ];
    }

    public function index(Request $request)
    {
        $kategori =$request->query('kategori');
        $daftarKelas =$this->getDaftarKelas();

        $kelas = collect($daftarKelas)->when($kategori, function ($collection,$kategori) {
            return $collection->where('kategori',$kategori);
        });

        return view('pelatihan.index', [
            'kelasList' => $kelas,
            'kategoriAktif' => $kategori,
        ]);
    }

    public function show(string $slug)
    {
        $daftarKelas =$this->getDaftarKelas();
        abort_unless(isset($daftarKelas[$slug]), 404);

        return view('pelatihan.show', [
            'kelas' => $daftarKelas[$slug],
        ]);
    }

    public function register(Request $request, string$slug)
    {
        $daftarKelas =$this->getDaftarKelas();
        abort_unless(isset($daftarKelas[$slug]), 404);

        $validated =$request->validate([
            'nama' => 'required|string|max:120',
            'whatsapp' => 'required|string|max:30',
            'email' => 'required|email|max:120',
            'instansi' => 'nullable|string|max:120',
            'jumlah_peserta' => 'required|integer|min:1|max:50',
            'catatan' => 'nullable|string|max:500',
        ]);

        return back()->with(
            'success_pelatihan',
            "Pendaftaran atas nama {$validated['nama']} untuk kelas {$daftarKelas[$slug]['judul']} berhasil dikirim! Admin kami akan menghubungi via WhatsApp."
        );
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\EduwisataReservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class EduwisataController extends Controller
{
    /**
     * Page 1: Daftar Paket Eduwisata (dengan Segmented Tab & Pagination)
     */
    public function index(Request $request): View
    {
        $allPackages = collect($this->packages());
        $perPage = 2;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $paginatedPackages = new LengthAwarePaginator(
            $allPackages->slice(($currentPage - 1) * $perPage, $perPage)->values(),
            $allPackages->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('eduwisata.index', [
            'packages' => $paginatedPackages,
        ]);
    }

    /**
     * Page 2: Detail Paket Eduwisata ("Ada apa di kebun?")
     */
    public function show(string $slug): View
    {
        $package = collect($this->packages())->firstWhere('slug', $slug);
        abort_if(! $package, 404);

        return view('eduwisata.show', compact('package'));
    }

    /**
     * Page 3: Form Detail Reservasi (Stepper 1 -> 2 -> 3)
     */
    public function create(string $slug): View
    {
        $package = collect($this->packages())->firstWhere('slug', $slug);
        abort_if(! $package, 404);

        return view('eduwisata.reserve', compact('package'));
    }

    /**
     * Simpan Reservasi ke Database
     */
    public function reserve(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'package_slug' => ['required', 'string'],
            'nama_pic' => ['required', 'string', 'max:120'],
            'asal_rombongan' => ['nullable', 'string', 'max:150'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'tanggal_kunjungan' => ['required', 'date'],
            'sesi_waktu' => ['required', 'string'],
            'jumlah_peserta' => ['required', 'integer', 'min:5', 'max:200'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $package = collect($this->packages())->firstWhere('slug', $validated['package_slug']);
        abort_if(! $package, 404);

        $reservation = EduwisataReservation::query()->create([
            'contact_name' => $validated['nama_pic'],
            'group_name' => $validated['asal_rombongan'] ?? '-',
            'package_name' => $package['title'],
            'whatsapp' => preg_replace('/\D+/', '', $validated['whatsapp']) ?: $validated['whatsapp'],
            'visit_date' => $validated['tanggal_kunjungan'],
            'session' => $validated['sesi_waktu'],
            'participants' => $validated['jumlah_peserta'],
            'notes' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('registrations.show', ['eduwisata', $reservation->reference_number]);
    }

    /**
     * Data 6 Paket Resmi sesuai Brosur Agrinova Farm Cikarang
     */
    private function packages(): array
    {
        return [
            [
                'slug' => 'paket-a-basic',
                'title' => 'Paket A (Basic)',
                'short_title' => 'Paket A',
                'tag' => 'Sekolah & Komunitas',
                'price' => 35000,
                'quota' => '100–120 peserta/sesi',
                'summary' => 'Tur kebun seru mengenal tanaman sayur, buah, dan hidroponik lengkap dengan pengalaman panen langsung.',
                'subtitle' => 'Tur kebun & panen langsung',
                'image' => 'images/edukasi2.webp',
                'highlights' => [
                    'Keliling kebun edukasi tanaman sayur, buah, & hidroponik',
                    'Pengalaman memanen langsung sayur mayur atau jagung',
                    'Didampingi fasilitator kebun & sound system',
                    'Akses fasilitas umum (mushola, kamar mandi, titik kumpul, parkir)',
                    'Gratis sertifikat kunjungan untuk sekolah',
                ],
            ],
            [
                'slug' => 'paket-b-berkebun',
                'title' => 'Paket B',
                'short_title' => 'Paket B',
                'tag' => 'Sekolah & Komunitas',
                'price' => 65000,
                'quota' => '100–120 peserta/sesi',
                'summary' => 'Paket lengkap eksplorasi kebun ditambah praktik membuat media tanam dan menanam bibit sendiri.',
                'subtitle' => 'Semua fasilitas Paket A + praktik menanam bibit',
                'image' => 'images/eduwisata.webp',
                'highlights' => [
                    'Semua fasilitas dalam Paket A (Basic)',
                    'Praktik membuat media tanam dan menanam bibit',
                    'Souvenir hasil menanam bibit dibawa pulang',
                    'Air mineral untuk setiap peserta',
                    'Sertifikat tiap anak dan sekolah',
                ],
            ],
            [
                'slug' => 'paket-c-panen-melon',
                'title' => 'Paket C',
                'short_title' => 'Paket C',
                'tag' => 'Sekolah & Keluarga',
                'price' => 80000,
                'quota' => '100–120 peserta/sesi',
                'summary' => 'Wisata edukasi kebun dengan pengalaman eksklusif memanen dan membawa pulang hasil panen buah melon.',
                'subtitle' => 'Semua fasilitas Paket A + panen buah melon',
                'image' => 'images/panenmelon.webp',
                'highlights' => [
                    'Semua fasilitas dalam Paket A (Basic)',
                    'Edukasi & proses memanen langsung buah melon di Green House',
                    'Hasil panen buah melon dibawa pulang',
                    'Gratis sertifikat untuk sekolah',
                ],
            ],
            [
                'slug' => 'paket-d-kreasi',
                'title' => 'Paket D',
                'short_title' => 'Paket D',
                'tag' => 'PAUD & TK / SD',
                'price' => 60000,
                'quota' => '100–120 peserta/sesi',
                'summary' => 'Kombinasi tur edukasi kebun dengan aktivitas mewarnai, kreasi keterampilan, dan spanduk penyambutan.',
                'subtitle' => 'Semua fasilitas Paket A + mewarnai & keterampilan',
                'image' => 'images/edukasi2.webp',
                'highlights' => [
                    'Semua fasilitas dalam Paket A (Basic)',
                    'Sesi mewarnai / kreasi keterampilan anak',
                    'Gratis spanduk welcome rombongan',
                    'Sertifikat per anak dan sekolah',
                ],
            ],
            [
                'slug' => 'paket-e-lengkap',
                'title' => 'Paket E',
                'short_title' => 'Paket E',
                'tag' => 'Full Day Rombongan',
                'price' => 170000,
                'quota' => '100–120 peserta/sesi',
                'summary' => 'Paket all-in terkomplit: berkebun, mewarnai, snack, makan siang, serta dokumentasi kegiatan tiap anak.',
                'subtitle' => 'Paket lengkap + makan siang & dokumentasi',
                'image' => 'images/greenhouse2.webp',
                'highlights' => [
                    'Semua fasilitas dalam Paket B (berkebun & bibit)',
                    'Aktivitas mewarnai & spanduk welcome rombongan',
                    'Sudah termasuk snack dan makan siang peserta',
                    'Dokumentasi kegiatan per anak',
                    'Sertifikat per anak dan sekolah',
                ],
            ],
            [
                'slug' => 'paket-f-satwa',
                'title' => 'Paket F',
                'short_title' => 'Paket F',
                'tag' => 'Edukasi Anak',
                'price' => 55000,
                'quota' => '100–120 peserta/sesi',
                'summary' => 'Tur kebun sayur dan hidroponik yang dipadukan dengan sesi pengenalan dan edukasi hewan melata.',
                'subtitle' => 'Semua fasilitas Paket A + edukasi hewan melata',
                'image' => 'images/eduwisata.webp',
                'highlights' => [
                    'Semua fasilitas dalam Paket A (Basic)',
                    'Sesi interaksi & edukasi hewan melata bersama pemandu',
                    'Gratis sertifikat untuk sekolah',
                ],
            ],
        ];
    }
}
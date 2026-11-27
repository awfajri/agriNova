<?php

namespace App\Http\Controllers;

use App\Models\TrainingClass;
use App\Models\TrainingRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PelatihanController extends Controller
{
    /**
     * Page 1: Daftar Program Pelatihan Budidaya (dengan Segmented Tab & Pagination)
     */
    public function index(Request $request): View
    {
        $classes = TrainingClass::query()
            ->where('status', '!=', 'Nonaktif')
            ->when($request->filled('kategori'), fn ($query) => $query->where('category', $request->string('kategori')))
            ->orderBy('sort_order')
            ->orderBy('title')
            ->paginate(2)
            ->withQueryString();

        return view('pelatihan.index', [
            'classes' => $classes,
            'kategoriAktif' => $request->query('kategori'),
        ]);
    }

    /**
     * Page 2: Detail Pelatihan ("Materi pelatihan" + Sticky CTA)
     */
    public function show(string $slug): View
    {
        $class = TrainingClass::query()
            ->where('slug', $slug)
            ->where('status', '!=', 'Nonaktif')
            ->firstOrFail();

        return view('pelatihan.show', [
            'kelas' => $class->toPublicArray(),
        ]);
    }

    /**
     * Page 3: Form Pendaftaran Pelatihan (Stepper 1 -> 2 -> 3)
     */
    public function create(string $slug): View
    {
        $class = TrainingClass::query()
            ->where('slug', $slug)
            ->where('status', '!=', 'Nonaktif')
            ->firstOrFail();

        return view('pelatihan.register', [
            'kelas' => $class->toPublicArray(),
        ]);
    }

    /**
     * Simpan Pendaftaran Pelatihan & Redirect ke Halaman Konfirmasi
     */
    public function register(Request $request, string $slug): RedirectResponse
    {
        $class = TrainingClass::query()
            ->where('slug', $slug)
            ->where('status', '!=', 'Nonaktif')
            ->firstOrFail();

        abort_unless($class->is_available, 422, 'Pendaftaran kelas ini sedang ditutup.');

        $kelasData = $class->toPublicArray();
        $allowedSessions = [
            'Sesi Pagi (09.00 - 12.00 WIB)',
            'Sesi Siang (13.00 - 16.00 WIB)',
        ];

        if (filled($kelasData['jadwal'])) {
            array_unshift($allowedSessions, "Sesuai Jadwal Kelas ({$kelasData['jadwal']})");
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:120'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:120'],
            'instansi' => ['nullable', 'string', 'max:120'],
            'jumlah_peserta' => ['required', 'integer', 'min:1', 'max:50'],
            'tanggal_pelatihan' => ['required', 'date', 'after_or_equal:today'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $registration = TrainingRegistration::query()->create([
            'training_class_id' => $class->id,
            'class_title' => $class->title,
            'name' => $validated['nama'],
            'whatsapp' => $this->normalizeWhatsApp($validated['whatsapp']),
            'email' => $validated['email'] ?? ($request->user()?->email ?? '-'),
            'institution' => $validated['instansi'] ?? null,
            'participants' => $validated['jumlah_peserta'],
            'preferred_date' => $validated['tanggal_pelatihan'],
            'notes' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('registrations.show', ['pelatihan', $registration->reference_number]);
    }

    private function normalizeWhatsApp(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?: '';

        if (str_starts_with($digits, '08')) {
            return '62' . substr($digits, 1);
        }

        if (str_starts_with($digits, '8') && strlen($digits) >= 10) {
            return '62' . $digits;
        }

        return $digits;
    }
}
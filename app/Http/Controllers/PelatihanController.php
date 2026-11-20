<?php

namespace App\Http\Controllers;

use App\Models\TrainingClass;
use App\Models\TrainingRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $class = TrainingClass::query()->where('slug', $slug)->firstOrFail();
        abort_unless($class->is_available, 422, 'Pendaftaran kelas ini sedang ditutup.');

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:120'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:120'],
            'instansi' => ['nullable', 'string', 'max:120'],
            'jumlah_peserta' => ['required', 'integer', 'min:1', 'max:50'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $registration = TrainingRegistration::query()->create([
            'training_class_id' => $class->id,
            'class_title' => $class->title,
            'name' => $validated['nama'],
            'whatsapp' => preg_replace('/\D+/', '', $validated['whatsapp']) ?: $validated['whatsapp'],
            'email' => $validated['email'] ?? ($request->user()?->email ?? '-'),
            'institution' => $validated['instansi'] ?? null,
            'participants' => $validated['jumlah_peserta'],
            'notes' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('registrations.show', ['pelatihan', $registration->reference_number]);
    }
}
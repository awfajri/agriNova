<?php

namespace App\Http\Controllers;

use App\Models\EduwisataReservation;
use App\Models\SiteSetting;
use App\Models\TrainingRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationTrackingController extends Controller
{
    public function index(): View
    {
        return view('registrations.track');
    }

    public function lookup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'reference_number' => ['required', 'string', 'max:24'],
            'whatsapp' => ['required', 'string', 'max:30'],
        ]);

        $reference = strtoupper(trim($validated['reference_number']));
        $phone = $this->normalizePhone($validated['whatsapp']);
        $type = str_starts_with($reference, 'TRN-') ? 'pelatihan' : (str_starts_with($reference, 'EDU-') ? 'eduwisata' : null);

        $record = match ($type) {
            'pelatihan' => TrainingRegistration::query()->where('reference_number', $reference)->where('whatsapp', $phone)->first(),
            'eduwisata' => EduwisataReservation::query()->where('reference_number', $reference)->where('whatsapp', $phone)->first(),
            default => null,
        };

        if (! $record) {
            return back()
                ->withInput()
                ->withErrors(['reference_number' => 'Data tidak ditemukan. Pastikan kode referensi dan nomor WhatsApp sudah benar.']);
        }

        return redirect()->route('registrations.show', [$type, $record->reference_number]);
    }

    public function show(string $type, string $reference): View
    {
        abort_unless(in_array($type, ['pelatihan', 'eduwisata'], true), 404);

        $registration = $type === 'pelatihan'
            ? TrainingRegistration::query()->with('trainingClass')->where('reference_number', strtoupper($reference))->firstOrFail()
            : EduwisataReservation::query()->where('reference_number', strtoupper($reference))->firstOrFail();

        $adminWhatsapp = SiteSetting::get('whatsapp', config('agrinova.whatsapp'));
        $whatsappUrl = $adminWhatsapp
            ? 'https://wa.me/' . preg_replace('/\D+/', '', $adminWhatsapp) . '?text=' . rawurlencode("Halo Admin Agrinova, saya ingin menanyakan pendaftaran {$registration->reference_number}.")
            : null;

        return view('registrations.show', compact('registration', 'type', 'whatsappUrl'));
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?: '';
    }
}

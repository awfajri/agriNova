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
        $type = str_starts_with($reference, 'TRN-')
            ? 'pelatihan'
            : (str_starts_with($reference, 'EDU-') ? 'eduwisata' : null);

        $record = match ($type) {
            'pelatihan' => TrainingRegistration::query()
                ->where('reference_number', $reference)
                ->get()
                ->first(fn (TrainingRegistration $item) => $this->normalizePhone($item->whatsapp) === $phone),
            'eduwisata' => EduwisataReservation::query()
                ->where('reference_number', $reference)
                ->get()
                ->first(fn (EduwisataReservation $item) => $this->normalizePhone($item->whatsapp) === $phone),
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
            ? TrainingRegistration::query()
                ->with('trainingClass')
                ->where('reference_number', strtoupper($reference))
                ->firstOrFail()
            : EduwisataReservation::query()
                ->where('reference_number', strtoupper($reference))
                ->firstOrFail();

        $whatsappUrl = $this->buildWhatsAppUrl($type, $registration);

        return view('registrations.show', compact('registration', 'type', 'whatsappUrl'));
    }

    private function buildWhatsAppUrl(string $type, EduwisataReservation|TrainingRegistration $registration): string
    {
        $settingWa = SiteSetting::get('whatsapp');
        $rawPhone = filled($settingWa)
            ? $settingWa
            : config('agrinova.whatsapp_number', '6285156287360');

        $cleanPhone = $this->normalizePhone((string) $rawPhone) ?: '6285156287360';

        if ($type === 'eduwisata') {
            $visitDate = $registration->visit_date?->translatedFormat('d M Y') ?? '-';
            $message = "Halo Admin Agrinova, saya ingin konfirmasi *Reservasi Eduwisata*:\n\n"
                . "Kode Referensi: *#{$registration->reference_number}*\n"
                . "Nama PIC: {$registration->contact_name}\n"
                . (filled($registration->group_name) && $registration->group_name !== '-' ? "Rombongan: {$registration->group_name}\n" : '')
                . "Paket: {$registration->package_name}\n"
                . "Tanggal Kunjungan: {$visitDate}\n"
                . "Sesi: {$registration->session}\n"
                . "Jumlah Peserta: {$registration->participants} orang\n\n"
                . "Mohon konfirmasi ketersediaan jadwal dan instruksi pembayaran DP-nya. Terima kasih!";
        } else {
            $classTitle = $registration->trainingClass->title ?? $registration->class_title ?? 'Pelatihan Budidaya';
            $trainingDate = $registration->preferred_date?->translatedFormat('d M Y') ?? '-';
            $message = "Halo Admin Agrinova, saya ingin konfirmasi *Pendaftaran Pelatihan*:\n\n"
                . "Kode Referensi: *#{$registration->reference_number}*\n"
                . "Nama Peserta: {$registration->name}\n"
                . "Program: {$classTitle}\n"
                . "Tanggal Pelatihan: {$trainingDate}\n"
                . "Jumlah Peserta: {$registration->participants} orang\n\n"
                . "Mohon informasi jadwal dan langkah selanjutnya. Terima kasih!";
        }

        return 'https://wa.me/' . $cleanPhone . '?text=' . rawurlencode($message);
    }

    private function normalizePhone(string $phone): string
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
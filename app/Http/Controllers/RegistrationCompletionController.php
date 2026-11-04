<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationCompletionController extends Controller
{
    /**
     * Halaman dua langkah: "Lengkapi pendaftaran" lalu "Privasi dan Ketentuan".
     */
    public function show(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasCompletedRegistration()) {
            return redirect()->intended(route('profile.show'));
        }

        return view('auth.complete-registration', [
            'user' => $user,
            'startStep' => $this->startStep($request),
            'supportUrl' => $this->supportUrl(),
        ]);
    }

    /**
     * Simpan data pendaftaran setelah pengguna menyetujui ketentuan.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'whatsapp' => preg_replace('/[\s\-().]/', '', (string) $request->input('whatsapp')),
        ]);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:120'],
            'whatsapp' => ['required', 'string', 'max:24', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,11}$/'],
            'promo' => ['nullable', 'boolean'],
            'setuju' => ['accepted'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.max' => 'Nama lengkap maksimal 120 karakter.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.regex' => 'Masukkan nomor WhatsApp yang valid, contoh 081234567890.',
            'setuju.accepted' => 'Anda perlu menyetujui Ketentuan Layanan dan Kebijakan Privasi Agrinova.',
        ]);

        $request->user()->update([
            'name' => $validated['nama'],
            'phone' => $validated['whatsapp'],
            'promo_opt_in' => $request->boolean('promo'),
            'terms_accepted_at' => now(),
            'profile_completed_at' => now(),
        ]);

        return redirect()
            ->intended(route('profile.show'))
            ->with('success', 'Pendaftaran selesai. Data Anda sudah kami simpan.');
    }

    /**
     * Setelah validasi gagal, buka lagi langkah yang relevan.
     */
    private function startStep(Request $request): int
    {
        $errors = $request->session()->get('errors');

        if ($errors && ! $errors->hasAny(['nama', 'whatsapp'])) {
            return 2;
        }

        return 1;
    }

    private function supportUrl(): ?string
    {
        $whatsapp = SiteSetting::get('whatsapp', config('agrinova.whatsapp'));

        return $whatsapp ? 'https://wa.me/' . preg_replace('/\D+/', '', $whatsapp) : null;
    }
}
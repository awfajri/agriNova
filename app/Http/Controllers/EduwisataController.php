<?php

namespace App\Http\Controllers;

use App\Models\EduwisataReservation;
use Illuminate\Http\Request;

class EduwisataController extends Controller
{
    public function index()
    {
        return view('eduwisata.index');
    }

    public function reserve(Request $request)
    {
        $validated = $request->validate([
            'nama_pic' => 'required|string|max:120',
            'asal_rombongan' => 'required|string|max:150',
            'kategori_kunjungan' => 'required|string',
            'whatsapp' => 'required|string|max:30',
            'tanggal_kunjungan' => 'required|date',
            'sesi_waktu' => 'required|string',
            'jumlah_peserta' => 'required|integer|min:5|max:200',
            'catatan' => 'nullable|string|max:500',
        ]);

        $reservation = EduwisataReservation::query()->create([
            'contact_name' => $validated['nama_pic'],
            'group_name' => $validated['asal_rombongan'],
            'package_name' => $validated['kategori_kunjungan'],
            'whatsapp' => preg_replace('/\D+/', '', $validated['whatsapp']) ?: $validated['whatsapp'],
            'visit_date' => $validated['tanggal_kunjungan'],
            'session' => $validated['sesi_waktu'],
            'participants' => $validated['jumlah_peserta'],
            'notes' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('registrations.show', ['eduwisata', $reservation->reference_number]);
    }
}
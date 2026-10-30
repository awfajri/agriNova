<?php

namespace App\Http\Controllers;

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

        return redirect()->to(route('eduwisata.index') . '#form-reservasi')
            ->with(
                'success_eduwisata',
                "Permohonan reservasi kunjungan dari {$validated['asal_rombongan']} ({$validated['jumlah_peserta']} orang) pada tanggal {$validated['tanggal_kunjungan']} berhasil tercatat! Admin akan segera mengonfirmasi ketersediaan kuota kebun."
            );
    }
}
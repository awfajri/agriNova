<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EduwisataReservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminEduwisataReservationController extends Controller
{
    public function index(Request $request): View
    {
        $reservations = EduwisataReservation::query()
            ->when($request->input('status'), fn ($query, $status) => $query->where('status', $status))
            ->orderByDesc('visit_date')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.eduwisata.index', compact('reservations'));
    }

    public function update(Request $request, EduwisataReservation $reservation): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'completed', 'rejected'])],
        ]);
        $reservation->update($validated);

        return back()->with('success', 'Status reservasi eduwisata berhasil diperbarui.');
    }
}

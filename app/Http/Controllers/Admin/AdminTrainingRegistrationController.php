<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminTrainingRegistrationController extends Controller
{
    public function index(Request $request): View
    {
        $registrations = TrainingRegistration::query()
            ->when($request->input('status'), fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.training-registrations.index', compact('registrations'));
    }

    public function update(Request $request, TrainingRegistration $registration): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'completed', 'rejected'])],
        ]);
        $registration->update($validated);

        return back()->with('success', 'Status pendaftar pelatihan berhasil diperbarui.');
    }
}

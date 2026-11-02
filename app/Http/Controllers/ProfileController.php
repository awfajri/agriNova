<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        return view('profile.show', [
            'user' => $user,
            'orders' => $user->orders()
                ->with('items')
                ->latest()
                ->paginate(6, ['*'], 'riwayat_page'),
            'activeOrder' => $user->orders()
                ->with('items')
                ->whereIn('status', Order::activeStatuses())
                ->latest()
                ->first(),
            'totalOrders' => $user->orders()->count(),
            'activeOrders' => $user->orders()->whereIn('status', Order::activeStatuses())->count(),
            'completedOrders' => $user->orders()->whereIn('status', Order::completedStatuses())->count(),
        ]);
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:24', 'regex:/^[0-9+()\s-]{8,24}$/'],
            'address' => ['nullable', 'string', 'max:255'],
        ], [
            'phone.regex' => 'Format nomor WhatsApp tidak valid. Gunakan angka, spasi, atau tanda - (contoh: 081234567890).',
        ]);

        $request->user()->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        return redirect()
            ->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}

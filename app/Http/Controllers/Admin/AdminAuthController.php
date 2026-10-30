<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (auth()->check() && auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login', [
            'configured' => filled(config('admin.email')) && filled(config('admin.password_hash')),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        if (
            ! filled(config('admin.email'))
            || ! filled(config('admin.password_hash'))
            || ! hash_equals(strtolower((string) config('admin.email')), strtolower($validated['email']))
            || ! Hash::check($validated['password'], (string) config('admin.password_hash'))
        ) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password admin tidak sesuai.']);
        }

        $admin = User::query()->firstOrCreate(
            ['email' => strtolower($validated['email'])],
            [
                'name' => 'Admin Agrinova',
                'password' => Str::random(64),
                'email_verified_at' => now(),
            ],
        );
        $admin->forceFill(['is_admin' => true])->save();

        Auth::login($admin, remember: false);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

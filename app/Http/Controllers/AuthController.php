<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class AuthController extends Controller
{
    public function login(Request $request): View
    {
        $intended = $this->safeIntended($request->input('redirect'));

        return view('auth.login', [
            'intended' => $intended,
            'googleConfigured' => filled(config('services.google.client_id')) && filled(config('services.google.client_secret')),
        ]);
    }

    public function redirectToGoogle(Request $request): RedirectResponse
    {
        $intended = $this->safeIntended($request->input('redirect'));
        session()->put('auth.intended', $intended);

        if (! filled(config('services.google.client_id')) || ! filled(config('services.google.client_secret'))) {
            return redirect()
                ->route('auth.login', ['redirect' => $intended])
                ->with('error', 'Login Google belum dikonfigurasi. Tambahkan kredensial Google OAuth di file .env.');
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('auth.login')
                ->with('error', 'Login Google gagal. Silakan coba lagi.');
        }

        $user = User::query()
            ->where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (! $user) {
            $user = new User();
            $user->password = Str::random(40);
        }

        $user->fill([
            'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Pelanggan Agrinova',
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'avatar_url' => $googleUser->getAvatar(),
            'email_verified_at' => now(),
        ]);
        $user->save();

        Auth::login($user, remember: true);
        $intended = session()->pull('auth.intended', route('checkout.create'));

        return redirect()->to($this->safeIntended($intended));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function safeIntended(?string $url, ?string $default = null): string
    {
        $fallback = $default ?? route('home');

        if (! $url) {
            return $fallback;
        }

        $parsed = parse_url($url);

        if (isset($parsed['host']) || isset($parsed['scheme'])) {
            return $fallback;
        }

        return Str::startsWith($url, '/') ? $url : $fallback;
    }
}

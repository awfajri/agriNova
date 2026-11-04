<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRegistrationCompleted
{
    /**
     * Pengguna yang sudah login tetapi belum menyelesaikan pendaftaran
     * diarahkan ke halaman "Lengkapi pendaftaran" lebih dulu.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->hasCompletedRegistration()) {
            return $next($request);
        }

        if ($request->isMethod('GET')) {
            $request->session()->put('url.intended', $request->fullUrl());
        }

        return redirect()->route('registration.complete');
    }
}
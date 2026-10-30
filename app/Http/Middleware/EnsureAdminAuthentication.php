<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAuthentication
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()
                ->route('admin.login')
                ->with('error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        if (! $request->user()->is_admin) {
            abort(403, 'Akses panel admin tidak diizinkan.');
        }

        return $next($request);
    }
}

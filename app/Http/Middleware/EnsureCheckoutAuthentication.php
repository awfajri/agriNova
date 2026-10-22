<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCheckoutAuthentication
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            return $next($request);
        }

        return redirect()
            ->route('catalog')
            ->with('auth_required', true)
            ->with('auth_intended', route('checkout.create'));
    }
}

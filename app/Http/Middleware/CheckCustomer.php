<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCustomer
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->guard('customer')->check()) {
            session(['url.intended' => $request->url()]);
            return redirect()->route('customer.auth.login.index')->with('error', 'Anda harus login terlebih dahulu');
        }

        return $next($request);
    }
}

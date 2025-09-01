<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
{
    if (!Auth::check()) {
        // Hapus line ini, biar Laravel yang handle intended url
        // session(['url.intended' => $request->url()]);
        return redirect()->route('admin.login')->with('error', 'Anda harus login terlebih dahulu');
    }

    $user = Auth::user();

    if (!in_array($user->role, $roles)) {
        Auth::logout();
        // Hapus juga line ini
        // session(['url.intended' => $request->url()]);
        return redirect()->route('admin.login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
    }

    return $next($request);
}
}

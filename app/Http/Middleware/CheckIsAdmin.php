<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN user adalah admin
        if (Auth::check() && Auth::user()->is_admin) {
            // Jika ya, izinkan lanjut
            return $next($request);
        }

        // Jika tidak, tendang kembali ke halaman utama
        return redirect()->route('home')->with('error', 'Anda tidak punya hak akses Admin.');
    }
}
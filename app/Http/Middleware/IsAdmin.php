<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN rolenya adalah admin
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request); // Silakan masuk
        }

        // Jika bukan admin, tendang kembali ke halaman utama dengan error 403
        abort(403, 'Akses Ditolak. Anda bukan Admin.');
    }
}

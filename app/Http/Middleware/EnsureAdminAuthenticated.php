<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAuthenticated
{
    /**
     * Menangani permintaan yang masuk (Memastikan Admin Sudah Mengisi Sesi Login).
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika tidak ada token sesi admin_authenticated, blokir dan lempar ke login
        if (!session()->has('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Akses diblokir. Silakan login ke dalam sistem terlebih dahulu.');
        }

        return $next($request);
    }
}

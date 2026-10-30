<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        // 2. Jika role user sesuai dengan yang diminta route, silakan lewat
        if ($userRole === $role) {
            return $next($request);
        }

        // 3. Jika role TIDAK sesuai, arahkan ke dashboard masing-masing (Bukan ke satu route general)
        // Ini untuk mencegah infinite redirect loop
        return $this->redirectBasedOnRole($userRole);
    }

    /**
     * Mengarahkan user ke rumahnya masing-masing jika tersesat
     */
    protected function redirectBasedOnRole($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'advokat':
                return redirect()->route('advokat.dashboard');
            default:
                // Jika role tidak dikenal, paksa logout atau lempar ke halaman login
                Auth::logout();
                return redirect()->route('login')->with('error', 'Akses ditolak: Role tidak dikenal.');
        }
    }
}

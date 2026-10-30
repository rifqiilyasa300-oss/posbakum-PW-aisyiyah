<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
public function store(Request $request): RedirectResponse
{
    $identity = $request->login_identity;
    $password = $request->password;

    // 1. Normalisasi: Jika input adalah nomor HP 08, ubah ke 62
    $phone = $identity;
    if (str_starts_with($phone, '08')) {
        $phone = '62' . substr($phone, 1);
    }

    // 2. Cek apakah input adalah email yang valid
    $isEmail = filter_var($identity, FILTER_VALIDATE_EMAIL);

    // 3. Tentukan field apa yang mau dicocokkan di database
    // Jika email, pakai 'email'. Jika bukan, pakai 'phone' (dengan nomor yang sudah dinormalisasi)
    $credentials = $isEmail ? ['email' => $identity, 'password' => $password] : ['phone' => $phone, 'password' => $password];

    // 4. Lakukan Attempt Login
    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();

        $user = Auth::user();
        if ($user->role === 'admin') return redirect()->intended(route('admin.dashboard'));
        if ($user->role === 'advokat') return redirect()->intended(route('advokat.dashboard'));

        return redirect()->intended(route('dashboard'));
    }

    // 5. Jika gagal
    return back()->withErrors([
        'login_identity' => 'Email/Nomor HP atau password salah.',
    ])->onlyInput('login_identity');
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http; // WAJIB TAMBAHKAN INI
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'login_identity' => [
                'required',
                'string',
                'max:255',
                'unique:users,email',
                'unique:users,phone',
            // --- TAMBAHKAN INI ---
        function ($attribute, $value, $fail) {
            // Jika bukan email (artinya diasumsikan nomor HP)
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                // Pastikan diawali 62
                if (!str_starts_with($value, '62')) {
                    $fail('Nomor ponsel wajib menggunakan format 62 (Contoh: 628123456789).');
                }
            }
        },
    ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $isEmail = filter_var($request->login_identity, FILTER_VALIDATE_EMAIL);

        $userData = [
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => 'klien',
            'otp_code' => rand(100000, 999999),
            'otp_expires_at' => now()->addMinutes(5),

        ];

        if ($isEmail) {
            $userData['email'] = $request->login_identity;
        } else {
            $userData['phone'] = $request->login_identity;
        }

        $user = User::create($userData);

        $otp = $user->otp_code;

        // --- REVISI: KIRIM OTP NYATA ---
        if ($isEmail) {
            // MENGIRIM EMAIL NYATA KE MAILTRAP
            Mail::raw("Halo, kode verifikasi OTP Anda adalah: {$otp}", function ($message) use ($user) {
                $message->to($user->email)->subject('Kode Verifikasi OTP Anda');
            });
} else {
    // 1. Bersihkan nomor (hapus karakter selain angka)
    $phone = preg_replace('/[^0-9]/', '', $user->phone);

    // 2. Ubah format 08 menjadi 62
    if (str_starts_with($phone, '08')) {
        $phone = '62' . substr($phone, 1);
    }

    // 3. KIRIM WA
    $response = Http::withHeaders([
        'Authorization' => env('FONNTE_TOKEN'),
    ])->asForm()->post(env('FONNTE_URL'), [
        'target' => $phone,
        'message' => "Halo, kode verifikasi OTP Anda adalah: {$otp}",
    ]);

    // ... sisa kode logging ...
}

        event(new Registered($user));

        session(['verifying_user_id' => $user->id]);

        return redirect()->route('verify.otp.form');
    }
}

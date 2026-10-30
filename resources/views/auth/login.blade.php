<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-extrabold text-green-900 tracking-tight">Selamat Datang</h2>
        <p class="text-sm text-gray-500 mt-1">Silakan login untuk memantau progres perkara Anda</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="login_identity" value="Email atau Nomor HP" class="text-green-900 font-bold" />
            <x-text-input id="login_identity" class="block mt-1 w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm"
                          type="text"
                          name="login_identity"
                          :value="old('login_identity')"
                          required autofocus />
            <x-input-error :messages="$errors->get('login_identity')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Password" class="text-green-900 font-bold" />
            <div class="relative mt-1 flex items-center">
                <x-text-input id="password" class="block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm pr-12"
                              type="password"
                              name="password"
                              required autocomplete="current-password" />

                <button type="button" onclick="toggleLoginPassword()"
                    class="absolute right-0 flex items-center h-full px-4 text-gray-400 hover:text-green-600 transition-all focus:outline-none">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
            </label>
        </div>

        <div class="flex flex-col gap-4 mt-6">
            <x-primary-button class="w-full justify-center py-3 bg-green-700 hover:bg-green-600 active:bg-green-800 focus:ring-green-500 transition-all duration-300 rounded-xl font-bold tracking-widest shadow-lg hover:shadow-green-900/20">
                {{ __('MASUK') }}
            </x-primary-button>

            <div class="flex items-center justify-between mt-2">
                @if (Route::has('password.request'))
                    <a class="text-sm text-green-700 hover:text-green-900 font-medium no-underline" href="{{ route('password.request') }}">
                        {{ __('Lupa password?') }}
                    </a>
                @endif
                <a class="text-sm text-gray-600 hover:text-green-700 font-medium no-underline" href="{{ route('register') }}">
                    {{ __('Belum punya akun?') }}
                </a>
            </div>
        </div>
    </form>
    <script>
    function toggleLoginPassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            // Opsional: Ubah ikon mata menjadi "terbuka" (hanya untuk visual)
            eyeIcon.classList.add('text-green-600');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('text-green-600');
        }
    }
</script>

    </x-guest-layout>

<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Daftar Akun</h2>
        <p class="text-sm text-gray-500 mt-2 font-medium">Lengkapi data diri untuk mulai pendaftaran</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" value="Nama Lengkap" class="text-gray-700 font-bold ml-1" />
            <x-text-input id="name" class="block mt-1 w-full border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm bg-gray-50/50" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="login_identity" value="Nomor Ponsel atau Email" class="text-gray-700 font-bold ml-1" />
            <x-text-input id="login_identity" class="block mt-1 w-full border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm bg-gray-50/50"
                type="text" name="login_identity" :value="old('login_identity')" placeholder="Masukkan email atau nomor ponsel" required />
            <x-input-error :messages="$errors->get('login_identity')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Password" class="text-gray-700 font-bold ml-1" />
            <div class="relative mt-1 flex items-center">
                <x-text-input id="password"
                    class="block w-full border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm bg-gray-50/50 pr-12"
                    type="password"
                    name="password"
                    required autocomplete="new-password" />

                <button type="button" onclick="toggleVisibility('password', 'eye-pw')"
                    class="absolute right-0 flex items-center h-full px-4 text-gray-400 hover:text-green-600 transition-all focus:outline-none">
                    <svg id="eye-pw" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Password" class="text-gray-700 font-bold ml-1" />
            <div class="relative mt-1 flex items-center">
                <x-text-input id="password_confirmation"
                    class="block w-full border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm bg-gray-50/50 pr-12"
                    type="password"
                    name="password_confirmation" required autocomplete="new-password" />

                <button type="button" onclick="toggleVisibility('password_confirmation', 'eye-confirm')"
                    class="absolute right-0 flex items-center h-full px-4 text-gray-400 hover:text-green-600 transition-all focus:outline-none">
                    <svg id="eye-confirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-8 space-y-4">
            <x-primary-button class="w-full justify-center py-4 bg-green-700 hover:bg-green-600 active:bg-green-800 focus:ring-green-500 transition-all duration-300 rounded-xl font-bold tracking-widest shadow-lg hover:shadow-green-900/20 text-sm">
                {{ __('DAFTAR SEKARANG') }}
            </x-primary-button>

            <div class="text-center mt-4">
                <p class="text-xs text-gray-500">
                    Sudah punya akun?
                    <a class="text-green-700 hover:text-green-900 font-bold no-underline" href="{{ route('login') }}">
                        Masuk di sini
                    </a>
                </p>
            </div>
        </div>
    </form>

    <script>
    function toggleVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            // Ubah warna ikon jadi hijau saat aktif (opsional)
            icon.classList.add('text-green-600');
        } else {
            input.type = 'password';
            icon.classList.remove('text-green-600');
        }
    }
</script>
</x-guest-layout>

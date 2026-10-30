<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Perbarui Kata Sandi') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        {{-- Kolom Kata Sandi Saat Ini --}}
        <div x-data="{ show: false }">
            <x-input-label for="update_password_current_password" :value="__('Kata Sandi Saat Ini')" />
            <div class="relative mt-1">
                <input
                    :type="show ? 'text' : 'password'"
                    id="update_password_current_password"
                    name="current_password"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-12"
                    autocomplete="current-password"
                />
                <button
                    type="button"
                    @click="show = !show"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700"
                >
                    <i x-show="!show" class="fas fa-eye"></i>
                    <i x-show="show" class="fas fa-eye-slash"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        {{-- Kolom Kata Sandi Baru --}}
        <div x-data="{ show: false }">
            <x-input-label for="update_password_password" :value="__('Kata Sandi Baru')" />
            <div class="relative mt-1">
                <input
                    :type="show ? 'text' : 'password'"
                    id="update_password_password"
                    name="password"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-12"
                    autocomplete="new-password"
                />
                <button
                    type="button"
                    @click="show = !show"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700"
                >
                    <i x-show="!show" class="fas fa-eye"></i>
                    <i x-show="show" class="fas fa-eye-slash"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        {{-- Kolom Konfirmasi Kata Sandi Baru --}}
        <div x-data="{ show: false }">
            <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" />
            <div class="relative mt-1">
                <input
                    :type="show ? 'text' : 'password'"
                    id="update_password_password_confirmation"
                    name="password_confirmation"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-12"
                    autocomplete="new-password"
                />
                <button
                    type="button"
                    @click="show = !show"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700"
                >
                    <i x-show="!show" class="fas fa-eye"></i>
                    <i x-show="show" class="fas fa-eye-slash"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>

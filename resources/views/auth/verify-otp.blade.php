<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Verifikasi OTP</h2>
        <p class="text-sm text-gray-500 mt-2 font-medium">Masukkan kode verifikasi Anda</p>
    </div>

    <form method="POST" action="{{ route('verify.otp.post') }}">
        @csrf
        <div class="mt-4">
            <x-text-input name="otp" type="text" class="block w-full text-center text-2xl" placeholder="000000" maxlength="6" required />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>
        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-4 bg-green-700">
                VERIFIKASI
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

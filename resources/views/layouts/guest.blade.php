<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .bg-pattern {
                background-color: #064e3b; /* Emerald 900 */
                background-image: url("https://www.transparenttextures.com/patterns/cubes.png");
            }
        </style>
    </head>
    <body class="antialiased text-gray-900">
        <div class="min-h-screen flex flex-col justify-center items-center p-4 bg-pattern">

            <div class="w-full sm:max-w-md">
                <div class="bg-white shadow-[0_20px_50px_rgba(0,0,0,0.3)] rounded-[2.5rem] overflow-hidden p-8 md:p-10 border border-white/20">

                    <div class="text-center mb-8">
                        <div class="inline-block p-3 rounded-full bg-green-50 mb-3 border border-green-100 shadow-sm">
                            <img src="{{ asset('images/logo-aisyiyah.png') }}" alt="Logo" class="w-16 h-16 object-contain mx-auto">
                        </div>


                        <div class="flex justify-center">
                            <div class="h-0.5 w-8 bg-yellow-400 rounded-full"></div>
                        </div>
                    </div>

                    <div class="relative z-10">
                        {{ $slot }}
                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-100 text-center">
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-[0.2em]">
                            Sistem Informasi Layanan Hukum
                        </p>
                    </div>
                </div>

                <p class="mt-8 text-center text-white/40 text-[10px] tracking-widest uppercase">
                    &copy; 2026 PW Aisyiyah DKI Jakarta
                </p>
            </div>
        </div>
    </body>
</html>

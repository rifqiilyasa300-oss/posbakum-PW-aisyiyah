<nav x-data="{ open: false }" class="bg-[#064E3B] p-4 text-white shadow-lg fixed w-full z-50">
    <div class="container mx-auto flex justify-between items-center h-12">
        {{-- Brand / Logo --}}
        <div class="flex items-center">
            <a href="{{ Auth::check() ? (Auth::user()->role == 'admin' ? route('admin.dashboard') : (Auth::user()->role == 'advokat' ? route('advokat.dashboard') : route('dashboard'))) : url('/') }}" class="flex items-center gap-3 group no-underline text-white">
                <img src="{{ asset('images/logo-aisyiyah.png') }}" alt="Logo Aisyiyah" class="h-10 w-auto transition group-hover:scale-105">
                <div class="flex flex-col justify-center">
                    <span class="text-lg md:text-xl font-bold uppercase tracking-wider group-hover:text-green-200 transition leading-tight">
                        POSBAKUM PW AISYIYAH
                    </span>
                    <span class="text-[10px] md:text-xs font-light tracking-[0.2em] opacity-80 -mt-1">
                        DKI JAKARTA
                    </span>
                </div>
            </a>
        </div>

        {{-- Desktop Menu --}}
        <div class="hidden sm:flex sm:items-center gap-8">
            <div class="flex items-center gap-6">
                @auth
                    <a href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : (Auth::user()->role == 'advokat' ? route('advokat.dashboard') : route('dashboard')) }}"
                    class="text-sm font-bold text-white no-underline hover:text-yellow-400 transition uppercase tracking-widest {{ request()->routeIs('*.dashboard') ? 'text-yellow-400 border-b-2 border-yellow-400' : '' }}">
                        Dashboard
                    </a>
                    @if(Auth::user()->role == 'admin')
                        <a href="{{ route('admin.kelola-kasus') }}" class="text-sm font-bold text-white no-underline hover:text-yellow-400 transition uppercase tracking-widest {{ request()->routeIs('admin.kelola-kasus') ? 'text-yellow-400 border-b-2 border-yellow-400' : '' }}">Kasus Aktif</a>
                        <a href="{{ route('admin.arsip') }}" class="text-sm font-bold text-white no-underline hover:text-yellow-400 transition uppercase tracking-widest {{ request()->routeIs('admin.arsip') ? 'text-yellow-400 border-b-2 border-yellow-400' : '' }}">Arsip</a>
                    @elseif(Auth::user()->role == 'advokat')
                        <a href="{{ route('advokat.riwayat') }}" class="text-sm font-bold text-white no-underline hover:text-yellow-400 transition uppercase tracking-widest {{ request()->routeIs('advokat.riwayat') ? 'text-yellow-400 border-b-2 border-yellow-400' : '' }}">Riwayat Aktif</a>
                        <a href="{{ route('advokat.arsip') }}" class="text-sm font-bold text-white no-underline hover:text-yellow-400 transition uppercase tracking-widest {{ request()->routeIs('advokat.arsip') ? 'text-yellow-400 border-b-2 border-yellow-400' : '' }}">Arsip</a>
                    @else
                        @php $urlPrefix = Request::is('/') ? '' : url('/'); @endphp
                        <a href="{{ $urlPrefix }}#layanan" class="text-sm font-bold text-white no-underline hover:text-yellow-400 transition uppercase tracking-widest">Layanan</a>
                        <a href="{{ $urlPrefix }}#profil" class="text-sm font-bold text-white no-underline hover:text-yellow-400 transition uppercase tracking-widest">Profil</a>
                        <a href="{{ $urlPrefix }}#lokasi" class="text-sm font-bold text-white no-underline hover:text-yellow-400 transition uppercase tracking-widest">Lokasi</a>
                    @endif
                @else
                    @php $urlPrefix = Request::is('/') ? '' : url('/'); @endphp
                    <a href="{{ $urlPrefix }}#layanan" class="text-sm font-bold text-white no-underline hover:text-yellow-400 transition uppercase tracking-widest">Layanan</a>
                    <a href="{{ $urlPrefix }}#profil" class="text-sm font-bold text-white no-underline hover:text-yellow-400 transition uppercase tracking-widest">Profil</a>
                    <a href="{{ $urlPrefix }}#lokasi" class="text-sm font-bold text-white no-underline hover:text-yellow-400 transition uppercase tracking-widest">Lokasi</a>
                @endauth
            </div>

            {{-- Auth Action & Lonceng --}}
            <div class="flex items-center gap-4">
                @auth
                    {{-- Ikon Lonceng dengan Alpine --}}
                    <div class="relative" x-data="{ openNotif: false }">
                        <button @click="openNotif = !openNotif" class="text-white relative focus:outline-none">
                            <i class="fas fa-bell text-lg"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute -top-1 -right-2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>
                        {{-- Dropdown Notifikasi --}}
                        <div x-show="openNotif" @click.away="openNotif = false" class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl py-2 z-[9999] text-gray-800 border" style="display: none;">
                            <div class="px-4 py-2 font-bold border-b text-sm">Notifikasi</div>
                            {{-- Ganti bagian forelse Anda dengan ini agar ada pembeda notifikasi baru dan lama --}}
                            @forelse(auth()->user()->unreadNotifications as $notification)
                                <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 text-sm hover:bg-green-50 transition border-b border-gray-100">
                                    <p class="text-xs font-bold text-gray-800">{{ $notification->data['pesan'] }}</p>
                                    <span class="text-[10px] text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                </a>
                            @empty
                                <div class="px-4 py-6 text-sm text-gray-500 text-center italic">Tidak ada notifikasi baru</div>
                            @endforelse
                        </div>
                    </div>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 border border-white/20 text-xs font-black rounded-lg text-white bg-green-800 hover:bg-green-900 focus:outline-none transition shadow-sm uppercase tracking-widest">
                                {{ Auth::user()->name }}
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Edit Profil') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600 font-bold">
                                    {{ __('Keluar') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold hover:text-yellow-400 no-underline text-white uppercase tracking-wider">LOGIN</a>
                    <a href="{{ route('register') }}" class="bg-white text-[#064E3B] px-5 py-2 rounded-xl font-bold text-sm hover:bg-gray-100 transition no-underline shadow-md uppercase">DAFTAR AKUN</a>
                @endauth
            </div>
        </div>

        {{-- Mobile Hamburger --}}
        <div class="-me-2 flex items-center sm:hidden">
            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-green-800 focus:outline-none transition">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- ✅ Mobile Menu - DITAMBAHKAN SEMUA MENU YANG HILANG --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden mt-4 pb-2 border-t border-green-600 bg-green-800 rounded-b-2xl mx-[-1rem] px-4 shadow-xl">
        <div class="space-y-1 pt-2 pb-3 text-center">
            @auth
                {{-- Dashboard --}}
                <a href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : (Auth::user()->role == 'advokat' ? route('advokat.dashboard') : route('dashboard')) }}"
                @click="open = false"
                class="block text-white font-bold py-4 no-underline uppercase text-sm tracking-widest border-b border-green-700 bg-green-900/30">
                    Dashboard
                </a>

                @if(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.kelola-kasus') }}" @click="open = false" class="block text-white font-bold py-3 no-underline uppercase text-sm border-b border-green-700">Kasus Aktif</a>
                    <a href="{{ route('admin.arsip') }}" @click="open = false" class="block text-white font-bold py-3 no-underline uppercase text-sm border-b border-green-700">Arsip</a>
                @elseif(Auth::user()->role == 'advokat')
                    <a href="{{ route('advokat.riwayat') }}" @click="open = false" class="block text-white font-bold py-3 no-underline uppercase text-sm border-b border-green-700">Riwayat Kasus</a>
                    <a href="{{ route('advokat.arsip') }}" @click="open = false" class="block text-white font-bold py-3 no-underline uppercase text-sm border-b border-green-700">Arsip</a>
                @else
                    {{-- ✅ Menu Tambahan untuk Pengguna Biasa --}}
                    <a href="{{ $urlPrefix }}#layanan" @click="open = false" class="block text-white font-bold py-3 no-underline uppercase text-sm border-b border-green-700">Layanan</a>
                    <a href="{{ $urlPrefix }}#profil" @click="open = false" class="block text-white font-bold py-3 no-underline uppercase text-sm border-b border-green-700">Profil</a>
                    <a href="{{ $urlPrefix }}#lokasi" @click="open = false" class="block text-white font-bold py-3 no-underline uppercase text-sm border-b border-green-700">Lokasi</a>
                @endif

                {{-- ✅ Lonceng Notifikasi di Menu HP --}}
                <div class="py-3 border-b border-green-700">
                    <div class="relative inline-flex items-center gap-2" x-data="{ openNotifMobile: false }">
                        <button @click="openNotifMobile = !openNotifMobile" class="text-white relative focus:outline-none font-bold uppercase text-sm">
                            <i class="fas fa-bell mr-2"></i> Notifikasi
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute -top-2 -right-6 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>
                        <div x-show="openNotifMobile" @click.away="openNotifMobile = false" class="absolute left-1/2 transform -translate-x-1/2 top-8 w-64 bg-white rounded-lg shadow-xl py-2 z-[9999] text-gray-800 border" style="display: none;">
                            <div class="px-4 py-2 font-bold border-b text-sm">Notifikasi</div>
                            @forelse(auth()->user()->unreadNotifications as $notification)
                                <a href="{{ route('notifications.read', $notification->id) }}" @click="open = false" class="block px-4 py-3 text-sm hover:bg-green-50 transition border-b border-gray-100">
                                    <p class="text-xs font-bold text-gray-800">{{ $notification->data['pesan'] }}</p>
                                    <span class="text-[10px] text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                </a>
                            @empty
                                <div class="px-4 py-6 text-sm text-gray-500 text-center italic">Tidak ada notifikasi baru</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- ✅ Info Nama Pengguna --}}
                <div class="py-3 border-b border-green-700">
                    <span class="text-white font-bold uppercase text-sm">👤 {{ Auth::user()->name }}</span>
                </div>

                <div class="pt-4 flex flex-col gap-2">
                    <a href="{{ route('profile.edit') }}" @click="open = false" class="block text-white py-2 no-underline text-xs font-bold uppercase opacity-70">Edit Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full bg-red-600 text-white py-3 rounded-xl font-bold uppercase text-xs">Keluar</button>
                    </form>
                </div>
            @else
                <a href="{{ url('/') }}#layanan" @click="open = false" class="block text-white font-bold py-3 no-underline uppercase text-sm border-b border-green-700">Layanan</a>
                <a href="{{ url('/') }}#profil" @click="open = false" class="block text-white font-bold py-3 no-underline uppercase text-sm border-b border-green-700">Profil</a>
                <a href="{{ url('/') }}#lokasi" @click="open = false" class="block text-white font-bold py-3 no-underline uppercase text-sm border-b border-green-700">Lokasi</a>
                <div class="pt-4 px-4">
                    <a href="{{ route('login') }}" @click="open = false" class="block text-white font-bold py-3 no-underline uppercase border-b border-green-700">LOGIN</a>
                    <a href="{{ route('register') }}" @click="open = false" class="block bg-yellow-500 text-green-900 text-center font-bold py-3 rounded-xl mt-2 no-underline uppercase">DAFTAR AKUN</a>
                </div>
            @endauth
        </div>
    </div>
</nav>

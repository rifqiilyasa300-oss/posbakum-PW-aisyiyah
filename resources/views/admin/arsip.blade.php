<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            {{ __('Riwayat Arsip Perkara') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#f8fafc] pt-24 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HERO SECTION --}}
            <div class="relative overflow-hidden bg-slate-900 rounded-[2.5rem] p-8 md:p-12 mb-10 shadow-2xl shadow-slate-900/20">
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="max-w-2xl">
                        <h2 class="text-4xl font-black text-white uppercase tracking-tighter italic">Arsip <span class="text-emerald-400">Perkara Selesai</span></h2>
                        <p class="text-slate-400 font-medium mt-3 leading-relaxed">
                            Penyimpanan permanen untuk seluruh berkas pendampingan hukum. Data di sini sudah dinyatakan selesai, inkracht, atau ditutup dan tidak dapat diubah lagi — hanya bisa dilihat atau dicetak.
                        </p>
                    </div>

                    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-[2rem] p-6 min-w-[240px] text-center">
                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-[0.2em] mb-1">Total Arsip Tersimpan</p>
                        <div class="text-6xl font-black text-white tracking-tighter">{{ $clients->count() }}</div>
                        <div class="mt-2 h-1 w-12 bg-emerald-500 mx-auto rounded-full"></div>
                    </div>
                </div>
            </div>

            {{-- MAIN TABLE CARD --}}
            <div class="bg-white border border-slate-300 rounded-3xl overflow-hidden shadow-sm">
                <div class="px-7 py-5 border-b border-slate-300 bg-slate-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white shadow-sm rounded-2xl flex items-center justify-center text-slate-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Basis Data Arsip Permanen</h3>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Berkas tersimpan aman & hanya dapat dilihat</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-2 text-xs font-black text-slate-400 hover:text-emerald-600 transition-colors uppercase tracking-widest">
                            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali Ke Halaman Utama
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto min-h-[400px]">
                    <table class="w-full min-w-[1000px] border-collapse">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="border border-slate-300 px-6 py-4 text-center text-[11px] font-black uppercase tracking-[0.2em] text-slate-500">Kode Registrasi</th>
                                <th class="border border-slate-300 px-6 py-4 text-center text-[11px] font-black uppercase tracking-[0.2em] text-slate-500">Nama Klien</th>
                                <th class="border border-slate-300 px-6 py-4 text-center text-[11px] font-black uppercase tracking-[0.2em] text-slate-500">Jenis Layanan</th>
                                <th class="border border-slate-300 px-6 py-4 text-center text-[11px] font-black uppercase tracking-[0.2em] text-slate-500">Status Akhir</th>
                                <th class="border border-slate-300 px-6 py-4 text-center text-[11px] font-black uppercase tracking-[0.2em] text-slate-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($clients as $item)
                                <tr class="hover:bg-slate-50 transition duration-200">
                                    {{-- Kode Registrasi --}}
                                    <td class="border border-slate-300 px-6 py-5 align-middle text-center">
                                        <span class="font-mono font-bold text-blue-600">{{ $item->reg_code }}</span>
                                    </td>

                                    {{-- Nama Klien --}}
                                    <td class="border border-slate-300 px-6 py-5 align-middle text-center">
                                        <p class="font-bold text-slate-800">{{ $item->nama }}</p>
                                        <p class="text-[10px] text-slate-500 mt-1">Tutup: {{ $item->updated_at->format('d F Y') }}</p>
                                    </td>

                                    {{-- Jenis Layanan --}}
                                    <td class="border border-slate-300 px-6 py-5 align-middle text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full {{ $item->kategori_layanan == 'PRODEO' ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700' }} border text-[11px] font-black uppercase tracking-widest">
                                            {{ $item->kategori_layanan ?? 'UMUM' }}
                                        </span>
                                    </td>

                                    {{-- Status Akhir --}}
                                    <td class="border border-slate-300 px-6 py-5 align-middle text-center">
                                        @if($item->status == 'Ditolak')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full border text-[11px] font-black uppercase tracking-widest bg-red-100 text-red-700 border-red-200">
                                                DITOLAK / DITUTUP
                                            </span>
                                        @elseif($item->status == 'Selesai' || $item->status == 'Inkracht')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full border text-[11px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700 border-emerald-200">
                                                SELESAI / INKRACHT
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full border text-[11px] font-black uppercase tracking-widest bg-slate-100 text-slate-700 border-slate-200">
                                                {{ strtoupper($item->status) }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="border border-slate-300 px-6 py-5 align-middle text-center">
                                        <a href="{{ route('admin.clients.show', $item->id) }}?from=archive" class="w-28 px-4 py-2 bg-slate-800 text-white text-[10px] font-bold rounded-xl shadow-md hover:bg-slate-900 transition inline-block">
                                            Lihat & Cetak
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="border border-slate-300 px-6 py-20 text-center text-slate-400">
                                        Belum ada berkas yang diarsipkan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- FOOTER INFO --}}
            <div class="mt-8 bg-slate-900 px-10 py-6 rounded-3xl flex flex-col md:flex-row justify-between items-center gap-4">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em]">Sistem Arsip Posbakum PW Aisyiyah DKI Jakarta &copy; {{ date('Y') }}</span>
                <div class="flex gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="text-[10px] font-black text-white uppercase tracking-widest">Penyimpanan Terjamin & Terlindungi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

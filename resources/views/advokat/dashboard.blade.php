<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            {{ __('Panel Kendali Advokat') }}
        </h2>
    </x-slot>

    {{-- Background Utama Putih Bersih sesuai Admin --}}
    <div class="min-h-screen bg-[#f8fafc] pt-24 pb-12 transition-all duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ALERT NOTIFIKASI --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-init="setTimeout(() => show = false, 4000)" class="mb-6">
                    <div class="bg-blue-600 text-white px-6 py-4 rounded-2xl shadow-lg font-bold flex justify-between items-center">
                        <span>{{ session('success') }}</span>
                        <button @click="show = false" class="hover:text-white/50">&times;</button>
                    </div>
                </div>
            @endif

            {{-- HERO SECTION --}}
            <div class="bg-[#1e293b] rounded-[2.5rem] p-10 md:p-12 mb-10 shadow-2xl shadow-blue-900/20 text-white relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="max-w-xl">
                        <span class="inline-block px-4 py-1 bg-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-full mb-6 border border-white/10">
                            Advokat Control Center
                        </span>
                        <h2 class="text-5xl font-black uppercase tracking-tighter mb-4">Selamat Datang,<br><span class="text-[#38bdf8]">{{ Auth::user()->name }}</span></h2>
                        <p class="text-slate-300 font-medium leading-relaxed">
                            Kelola data klien, monitor perkembangan kasus, dan pantau seluruh aktivitas layanan hukum Anda secara real-time.
                        </p>
                    </div>
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('advokat.riwayat') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-[#1e293b] rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all shadow-lg active:scale-95">
                            Lihat Perkara
                        </a>
                    </div>
                </div>
            </div>

{{-- STATISTIC CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
    {{-- UBAH BAGIAN INI --}}
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 flex items-center justify-between group">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Kasus Baru</p>
            <h4 class="text-5xl font-black text-slate-900 tracking-tighter">
                {{ $totalAktif }}
            </h4>
            <p class="mt-2 text-xs font-bold text-blue-600 uppercase tracking-widest"> (Perlu Tindakan)</p>
        </div>
        <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    {{-- BAGIAN TOTAL SELESAI --}}
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 flex items-center justify-between group">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Total Selesai</p>
            <h4 class="text-5xl font-black text-slate-900 tracking-tighter">
                {{ $totalSelesai }}
            </h4>
            <p class="mt-2 text-xs font-bold text-slate-400 uppercase tracking-widest">Arsip Kasus</p>
        </div>
        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        </div>
    </div>
</div>

             {{-- TABLE --}}
        <div class="bg-white border border-slate-300 rounded-3xl overflow-hidden shadow-sm">
            <div class="px-7 py-5 border-b border-slate-300 bg-slate-50">
                <h3 class="text-lg font-black text-slate-800">Daftar Pendaftaran Klien</h3>
            </div>

            <div class="overflow-x-auto min-h-[400px]">
                <table class="w-full min-w-[1000px] border-collapse">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="border border-slate-300 px-6 py-4 text-center text-[11px] font-black uppercase tracking-[0.2em] text-slate-500">Kode</th>
                            <th class="border border-slate-300 px-6 py-4 text-center text-[11px] font-black uppercase tracking-[0.2em] text-slate-500">Nama Klien</th>
                            <th class="border border-slate-300 px-6 py-4 text-center text-[11px] font-black uppercase tracking-[0.2em] text-slate-500">Layanan</th>
                            <th class="border border-slate-300 px-6 py-4 text-center text-[11px] font-black uppercase tracking-[0.2em] text-slate-500">Status</th>
                            <th class="border border-slate-300 px-6 py-4 text-center text-[11px] font-black uppercase tracking-[0.2em] text-slate-500">Dokumen</th>
                            <th class="border border-slate-300 px-6 py-4 text-center text-[11px] font-black uppercase tracking-[0.2em] text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($clients as $client)
                            <tr class="hover:bg-slate-50 transition duration-200">
                                {{-- Kode --}}
                                <td class="border border-slate-300 px-6 py-5 align-middle text-center">
                                    <span class="font-mono font-bold text-blue-600">{{ $client->reg_code }}</span>
                                </td>

                                {{-- Nama Klien --}}
                                <td class="border border-slate-300 px-6 py-5 align-middle text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 text-white flex items-center justify-center font-black text-sm uppercase shadow-md">
                                            {{ strtoupper(substr($client->nama, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $client->nama }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Layanan --}}
                                <td class="border border-slate-300 px-6 py-5 align-middle text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full {{ $client->kategori_layanan == 'PRODEO' ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700' }} border text-[11px] font-black uppercase tracking-widest">
                                        {{ $client->kategori_layanan }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="border border-slate-300 px-6 py-5 align-middle text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full border text-[11px] font-black uppercase tracking-widest
                                        {{ $client->status == 'Proses' ? 'bg-blue-100 text-blue-700 border-blue-200' : ($client->status == 'Ditolak' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-amber-100 text-amber-700 border-amber-200') }}">
                                        {{ $client->status }}
                                    </span>
                                    <p class="text-[10px] text-slate-500 mt-1">{{ Str::limit($client->tahapan, 35) }}</p>
                                </td>

                                {{-- Dokumen --}}
                                <td class="border border-slate-300 px-6 py-5 align-middle text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        @if($client->ktp_path)
                                            <a href="{{ asset('storage/ktp/' . basename($client->ktp_path)) }}" target="_blank" class="text-slate-600 font-bold text-[10px] hover:underline">📂 KTP</a>
                                        @endif
                                        @if($client->sktm_path)
                                            <a href="{{ asset('storage/sktm/' . basename($client->sktm_path)) }}" target="_blank" class="text-slate-600 font-bold text-[10px] hover:underline">📂 SKTM</a>
                                        @endif
                                        @if($client->bukti_bayar_path)
                                            <a href="{{ asset('storage/pembayaran/' . basename($client->bukti_bayar_path)) }}" target="_blank" class="text-blue-600 font-bold text-[10px] hover:underline">📂 Bukti Bayar</a>
                                        @endif
                                    </div>
                                </td>

                                {{-- Aksi --}}
                                <td class="border border-slate-300 px-6 py-5 align-middle text-center">
                                    <a href="{{ route('advokat.clients.show', $client->id) }}" class="w-24 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-[10px] font-bold rounded-xl shadow-md transition inline-block">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="border border-slate-300 px-6 py-20 text-center text-slate-400">
                                    Tidak ada tugas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</x-app-layout>

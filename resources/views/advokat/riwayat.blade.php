<x-app-layout>
    {{-- Header Slot --}}
    <x-slot name="header">
        <h2 class="font-black text-xl text-white leading-tight flex items-center gap-2 uppercase tracking-widest">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            {{ __('Riwayat Perkara Aktif') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#f8fafc] pt-24 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HERO SECTION --}}
            <div class="relative overflow-hidden bg-[#1e293b] rounded-[2.5rem] p-8 md:p-12 mb-10 shadow-2xl shadow-slate-900/20">
                {{-- Ornamen Latar Belakang --}}
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="max-w-2xl">
                        <h2 class="text-4xl font-black text-white uppercase tracking-tighter italic">Daftar Perkara <span class="text-blue-400">Dalam Proses</span></h2>
                        <p class="text-slate-400 font-medium mt-3 leading-relaxed">Daftar lengkap klien yang saat ini sedang berada dalam masa pendampingan hukum Anda secara aktif.</p>
                    </div>

                    {{-- Stats Card Terintegrasi --}}
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-[2rem] p-6 min-w-[240px] text-center">
                        <p class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em] mb-1">Tugas Berjalan</p>
                        <div class="text-6xl font-black text-white tracking-tighter">{{ $clients->count() }}</div>
                        <div class="mt-2 h-1 w-12 bg-blue-500 mx-auto rounded-full"></div>
                    </div>
                </div>
            </div>

            {{-- DATA TABLE CONTAINER --}}
            <div class="bg-white border border-slate-300 rounded-3xl overflow-hidden shadow-sm">
                {{-- Sub Header Tabel --}}
                <div class="px-7 py-5 border-b border-slate-300 bg-slate-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white shadow-sm rounded-2xl flex items-center justify-center text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Database Perkara Aktif</h3>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Update perkembangan kasus secara real-time</p>
                            </div>
                        </div>
                        <a href="{{ route('advokat.dashboard') }}" class="group flex items-center gap-2 text-xs font-black text-slate-400 hover:text-blue-600 transition-colors uppercase tracking-widest">
                            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali Ke Panel Utama
                        </a>
                    </div>
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
                                            <div class="text-left">
                                                <p class="font-bold text-slate-800 uppercase">{{ $client->nama }}</p>
                                                <p class="text-[9px] text-slate-400 mt-1">Reg: {{ $client->created_at->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Layanan --}}
                                    <td class="border border-slate-300 px-6 py-5 align-middle text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full {{ $client->kategori_layanan == 'PRODEO' ? 'bg-rose-100 text-rose-700 border-rose-200' : 'bg-blue-100 text-blue-700 border-blue-200' }} border text-[11px] font-black uppercase tracking-widest">
                                            {{ $client->kategori_layanan ?? 'UMUM' }}
                                        </span>
                                    </td>

                                    {{-- Status --}}
                                    <td class="border border-slate-300 px-6 py-5 align-middle text-center">
                                        @php
                                            $badgeClass = 'bg-blue-100 text-blue-700 border-blue-200';
                                            if(Str::contains(strtoupper($client->tahapan), 'KONFIRMASI')) $badgeClass = 'bg-purple-100 text-purple-700 border-purple-200';
                                            elseif(Str::contains(strtoupper($client->tahapan), 'MEDIASI')) $badgeClass = 'bg-orange-100 text-orange-700 border-orange-200';
                                            elseif(Str::contains(strtoupper($client->tahapan), 'PERSIDANGAN')) $badgeClass = 'bg-green-100 text-green-700 border-green-200';
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full border text-[11px] font-black uppercase tracking-widest {{ $badgeClass }}">
                                            {{ $client->status }}
                                        </span>
                                        <p class="text-[10px] text-slate-500 mt-1">{{ Str::limit($client->tahapan, 35) }}</p>
                                        <p class="text-[9px] text-slate-400 mt-0.5">Update: {{ $client->updated_at->diffForHumans() }}</p>
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
                                        <a href="{{ route('advokat.clients.show', $client->id) }}"
                                           class="w-24 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-[10px] font-bold rounded-xl shadow-md transition inline-block">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="border border-slate-300 px-6 py-20 text-center text-slate-400">
                                        Tidak Ada Perkara Aktif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer Info --}}
                <div class="bg-slate-900 px-10 py-6 flex flex-col md:flex-row justify-between items-center gap-4 mt-4 rounded-b-3xl">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em]">Management Monitoring &copy; {{ date('Y') }}</span>
                    <span class="px-4 py-1.5 bg-white border border-slate-700 rounded-full text-[10px] font-black text-slate-800 uppercase tracking-widest shadow-sm">
                        Total Aktif: {{ $clients->count() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

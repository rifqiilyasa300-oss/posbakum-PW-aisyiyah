<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-white leading-tight flex items-center gap-2 uppercase tracking-tighter">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            {{ __('Detail Kasus & Berkas Digital') }}
        </h2>
    </x-slot>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        [x-cloak] { display: none !important; }
    </style>

    <div class="pt-24 pb-20 bg-slate-100 min-h-screen relative" x-data="{ openStatus: false, openJadwal: false }">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- BARIS NAVIGASI & TOMBOL AKSI --}}
            <div class="mb-6 flex flex-wrap justify-between items-center gap-4">

                {{-- Tombol Kembali --}}
                <a href="{{ route('advokat.dashboard') }}" class="inline-flex items-center gap-2 text-slate-600 font-black text-xs uppercase tracking-widest hover:text-green-700 transition group">
                    <div class="bg-white p-2 rounded-lg shadow-sm group-hover:shadow-md transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </div>
                    Kembali
                </a>

                {{-- GRUP TOMBOL AKSI --}}
                <div class="flex items-center gap-3">

                    {{-- 1. TOMBOL CETAK --}}
                    <a href="{{ route('admin.clients.print', $client->id) }}" target="_blank"
                       class="inline-flex items-center gap-3 bg-blue-600 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl hover:bg-blue-700 transition active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Dokumen
                    </a>

                    {{-- 2. TOMBOL BUAT LAPORAN --}}
                    <a href="{{ route('advokat.consultation.create', $client->id) }}"
                       class="inline-flex items-center gap-3 bg-slate-900 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl hover:bg-black transition active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat Laporan Konsultasi
                    </a>

                    @php
                        // Cek laporan tanpa pakai relasi user() agar aman
                        $sudahAdaLaporan = $client->consultationReports()
                            ->whereIn('user_id', function($sub) {
                                $sub->select('id')->from('users')->where('role', 'advokat');
                            })
                            ->exists();
                    @endphp

                    {{-- ✅ TOMBOL ATUR JADWAL - HANYA MUNCUL JIKA BELUM ADA LAPORAN --}}
                    @if(in_array(trim($client->tahapan), [
                        'KONFIRMASI KASUS (TLP/OFFLINE)',
                        'MEDIASI (NON-LITIGASI)',
                        'PENDAFTARAN E-COURT (LITIGASI)',
                        'PROSES PERSIDANGAN',
                        'TERTUNDA'
                    ]) && !$sudahAdaLaporan)
                    <button @click="openJadwal = true"
                       class="inline-flex items-center gap-3 bg-purple-600 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl hover:bg-purple-700 transition active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Atur Jadwal
                    </button>
                    @else
                    <div class="bg-slate-200 text-slate-600 px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest">
                        {{ $sudahAdaLaporan ? 'Sudah Ada Laporan' : 'Belum Bisa Atur Jadwal' }}
                    </div>
                    @endif

                    {{-- 3. LOGIKA STATUS --}}
                    @if($client->status != 'Pendaftaran Diterima' && $client->status != 'Selesai' && $client->status != 'Ditolak')

                        <button @click="openStatus = true" class="bg-green-600 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl hover:bg-green-700 transition active:scale-95">
                            Update Progress
                        </button>

                        @if($client->status == 'Berkas Lengkap')
                            <form action="{{ route('advokat.clients.updateStatus', $client->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="Proses">
                                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl hover:bg-indigo-700 transition active:scale-95">
                                    Konfirmasi Kasus
                                </button>
                            </form>
                        @endif

                    @elseif($client->status == 'Selesai')
                        <div class="bg-slate-400 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest">
                            Kasus Selesai
                        </div>

                    @elseif($client->status == 'Pendaftaran Diterima')
                        <div class="bg-amber-100 text-amber-800 border border-amber-300 px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm">
                            Menunggu Verifikasi Admin
                        </div>
                    @endif

                </div>
            </div>

            <div class="bg-white shadow-2xl rounded-[2.5rem] overflow-hidden border border-slate-200">
                {{-- Header Card --}}
                <div class="bg-slate-900 p-8 md:p-10 text-white flex flex-col md:flex-row justify-between items-center border-b-4 border-green-500 gap-4">
                    <div>
                        <p class="text-[10px] font-black text-green-400 uppercase tracking-[0.4em] mb-2">Timestamp Pendaftaran</p>
                        <p class="text-2xl font-mono font-bold tracking-tight">{{ $client->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-2">Nomor Registrasi</p>
                        <span class="bg-green-600 text-white px-5 py-2.5 rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-green-900/50">
                            {{ $client->reg_code }}
                        </span>
                    </div>
                </div>

                <div class="p-8 md:p-14 space-y-16">

                    {{-- ✅ BAGIAN JADWAL - SUDAH DIPERBAIKI --}}
                    @if(in_array(trim($client->tahapan), [
                        'KONFIRMASI KASUS (TLP/OFFLINE)',
                        'MEDIASI (NON-LITIGASI)',
                        'PENDAFTARAN E-COURT (LITIGASI)',
                        'PROSES PERSIDANGAN',
                        'TERTUNDA'
                    ]) && !$sudahAdaLaporan)
                    <section>
                        <div class="flex items-center gap-4 mb-8">
                            <span class="h-px flex-1 bg-slate-200"></span>
                            <h3 class="text-xs font-black text-purple-700 uppercase tracking-[0.3em] flex items-center gap-2">
                                <span class="bg-purple-100 p-1.5 rounded-lg">📅</span> Jadwal Konsultasi
                            </h3>
                            <span class="h-px flex-1 bg-slate-200"></span>
                        </div>

                        <div class="bg-purple-50 border border-purple-200 rounded-[2rem] p-8">
            @php
    // Cek apakah jadwal benar-benar ada dan valid
    $jadwalValid = false;
    $tanggalJadwal = null;

    // ✅ PERBAIKAN: Hanya tampilkan jadwal jika sudah melewati tahap penunjukan advokat
    $bolehTampilkanJadwal = in_array(trim($client->tahapan), [
        'KONFIRMASI KASUS (TLP/OFFLINE)',
        'MEDIASI (NON-LITIGASI)',
        'PENDAFTARAN E-COURT (LITIGASI)',
        'PROSES PERSIDANGAN',
        'TERTUNDA'
    ]);

    if ($bolehTampilkanJadwal && !empty($client->jadwal_konsultasi) && $client->jadwal_konsultasi != '0000-00-00 00:00:00') {
        try {
            $tanggalJadwal = \Carbon\Carbon::parse($client->jadwal_konsultasi);
            if ($tanggalJadwal->year >= 2020) {
                $jadwalValid = true;
            }
        } catch (\Exception $e) {
            $jadwalValid = false;
        }
    }
@endphp

                            @if($jadwalValid && $tanggalJadwal)
                                @php
                                    $statusJadwal = $tanggalJadwal->isPast() ? 'Sudah Terlaksana' : 'Akan Datang';
                                    $warnaStatus = $tanggalJadwal->isPast() ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800';
                                @endphp

                                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                                    <div class="flex items-center gap-5">
                                        <div class="bg-purple-600 p-4 rounded-2xl text-white">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-black text-slate-800">Konsultasi Hukum</h4>
                                            <p class="text-sm text-slate-600 mt-1">📅 {{ $tanggalJadwal->translatedFormat('l, d F Y') }}</p>
                                            <p class="text-sm text-slate-600 mt-1">⏰ Pukul: {{ $tanggalJadwal->format('H:i') }} WIB</p>
                                        </div>
                                    </div>
                                    <span class="px-4 py-2 rounded-full text-xs font-black uppercase {{ $warnaStatus }}">
                                        {{ $statusJadwal }}
                                    </span>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="bg-purple-100 p-4 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-slate-600 font-medium">Belum ada jadwal konsultasi yang diatur</p>
                                    <p class="text-xs text-slate-500 mt-1">Klik tombol "Atur Jadwal" untuk menentukan waktu pertemuan</p>
                                </div>
                            @endif
                        </div>
                    </section>
                    @elseif($sudahAdaLaporan)
                    <section>
                        <div class="flex items-center gap-4 mb-8">
                            <span class="h-px flex-1 bg-slate-200"></span>
                            <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.3em] flex items-center gap-2">
                                <span class="bg-slate-100 p-1.5 rounded-lg">📅</span> Jadwal Konsultasi
                            </h3>
                            <span class="h-px flex-1 bg-slate-200"></span>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-[2rem] p rounded-[2rem] p-6 text-center">
                            <p class="text-green-700 font-medium">✅ Konsultasi telah dilaksanakan dan laporan sudah disimpan</p>
                        </div>
                    </section>
                    @else
                    <section>
                        <div class="flex items-center gap-4 mb-8">
                            <span class="h-px flex-1 bg-slate-200"></span>
                            <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.3em] flex items-center gap-2">
                                <span class="bg-slate-100 p-1.5 rounded-lg">📅</span> Jadwal Konsultasi
                            </h3>
                            <span class="h-px flex-1 bg-slate-200"></span>
                        </div>
                        <div class="bg-slate-50 border border-slate-200 rounded-[2rem] p-8 text-center">
                            <p class="text-slate-500 italic">Jadwal akan tersedia setelah kasus dikonfirmasi</p>
                        </div>
                    </section>
                    @endif

{{-- ✅ SEKSI STATUS & ASESMEK ADVOKAT --}}
<section style="margin-top: 48px !important;" class="bg-green-50 border-2 border-green-200 rounded-[2.5rem] p-8">
    <div class="flex items-center gap-3 mb-4 mt-3">
        <div class="bg-green-600 p-2 rounded-xl text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h3 class="font-black text-slate-800 uppercase tracking-tight text-sm">Status & Asesmen Advokat</h3>
    </div>
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-green-100">
        <div class="mb-4">
            <span class="text-[9px] font-black bg-purple-600 text-white px-3 py-1 rounded-full uppercase tracking-widest">
                {{ $client->tahapan ?? 'Belum dipilih' }}
            </span>
        </div>
        {{-- HANYA TAMPILKAN CATATAN DARI ADVOKAT --}}
        <div class="p-3 bg-green-50 rounded-xl border border-green-100">
            <p class="text-xs text-green-700 font-semibold uppercase tracking-wider mb-1">📝 Catatan & Asesmen dari Advokat</p>
            <p class="text-sm text-slate-700 italic">"{{ $client->legal_advice ?? 'Belum ada catatan asesmen.' }}"</p>
        </div>
    </div>
</section>

                    {{-- Section 01: Identitas Diri --}}
                    <section>
                        <div class="flex items-center gap-4 mb-8">
                            <span class="h-px flex-1 bg-slate-200"></span>
                            <h3 class="text-xs font-black text-green-700 uppercase tracking-[0.3em] flex items-center gap-2">
                                <span class="bg-green-100 p-1.5 rounded-lg">01</span> Identitas Diri
                            </h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">NAMA LENGKAP</label>
                            <p class="text-2xl font-black text-slate-800 uppercase tracking-tighter">{{ $client->nama }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">NIK</label>
                            <p class="text-lg font-bold text-slate-700 tracking-tight">{{ $client->nik ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">STATUS PERKAWINAN</label>
                            <p class="text-lg font-bold text-slate-700 uppercase">{{ $client->status_perkawinan ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">TEMPAT TANGGAL LAHIR</label>
                            <p class="text-lg font-bold text-slate-700">{{ $client->tempat_lahir . ', ' . \Carbon\Carbon::parse($client->tanggal_lahir)->format('d F Y') }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">JENIS KELAMIN</label>
                            <p class="text-lg font-bold text-slate-700 uppercase">{{ $client->jenis_kelamin }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">AGAMA</label>
                            <p class="text-lg font-bold text-slate-700 uppercase">{{ $client->agama }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">PENDIDIKAN</label>
                            <p class="text-lg font-bold text-slate-700 uppercase">{{ $client->pendidikan ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">PEKERJAAN</label>
                            <p class="text-lg font-bold text-slate-700 uppercase">{{ $client->pekerjaan ?? '-' }}</p>
                        </div>
                    </div>
                    </section>

                    {{-- Section 02: Alamat & Kontak --}}
                    <section>
                        <div class="flex items-center gap-4 mb-8">
                            <span class="h-px flex-1 bg-slate-200"></span>
                            <h3 class="text-xs font-black text-green-700 uppercase tracking-[0.3em] flex items-center gap-2">
                                <span class="bg-green-100 p-1.5 rounded-lg">02</span> Alamat & Kontak
                            </h3>
                        </div>
                        <div class="grid grid-cols-1 gap-8">
                            <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200"><label class="text-[10px] font-black text-slate-400 uppercase block mb-2">ALAMAT LENGKAP (KTP)</label><p class="text-base font-semibold text-slate-700 uppercase">{{ $client->alamat_ktp }}</p></div>
                            <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200"><label class="text-[10px] font-black text-slate-400 uppercase block mb-2">ALAMAT DOMISILI</label><p class="text-base font-semibold text-slate-700 uppercase">{{ $client->alamat_domisili }}</p></div>

                            @php
                                $waNumber = preg_replace('/[^0-9]/', '', $client->phone);
                                if(str_starts_with($waNumber, '0')) { $waNumber = '62' . substr($waNumber, 1); }
                                $waMessage = rawurlencode("Halo Bapak/Ibu " . $client->nama . ", saya Advokat dari Posbakum Aisyiyah DKI Jakarta terkait pendaftaran bantuan hukum Anda.");
                            @endphp

                            <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" target="_blank" class="inline-flex items-center gap-4 bg-green-50 p-6 rounded-3xl border-2 border-green-200 hover:bg-green-100 transition shadow-sm group">
                                <div class="bg-green-600 p-3 rounded-2xl text-white shadow-lg group-hover:scale-110 transition"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.011 20.29c-3.553 0-6.441-2.89-6.441-6.44 0-1.21.33-2.34.89-3.3l-.53-1.93 1.98.52c.93-.53 2.01-.84 3.15-.84 3.55 0 6.44 2.89 6.44 6.44s-2.89 6.44-6.44 6.44m8.12-14.06c-2.17-2.16-5.06-3.35-8.13-3.35-6.34 0-11.5 5.17-11.5 11.5 0 2.03.53 4.01 1.54 5.75L1 23l6.58-1.72c1.68.92 3.58 1.4 5.52 1.4 6.34 0 11.5-5.17 11.5-11.5 0-3.07-1.2-5.95-3.37-8.13z"/></svg></div>
                                <div><label class="text-[10px] font-black text-green-700 uppercase block">HUBUNGI KLIEN VIA WHATSAPP</label><p class="text-2xl font-black text-slate-800 tracking-tighter">{{ $client->phone }}</p></div>
                            </a>
                        </div>
                    </section>

                    {{-- Section 03: Uraian Perkara --}}
                    <section>
                        <div class="flex items-center gap-4 mb-8">
                            <span class="h-px flex-1 bg-slate-200"></span>
                            <h3 class="text-xs font-black text-green-700 uppercase tracking-[0.3em] flex items-center gap-2">
                                <span class="bg-green-100 p-1.5 rounded-lg">03</span> Uraian Perkara
                            </h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                            <div class="bg-amber-50 rounded-[2.5rem] border border-amber-200 overflow-hidden min-h-[350px]">
                                <div class="p-6 bg-amber-100/50 border-b border-amber-200 text-center"><label class="text-[10px] font-black text-amber-800 uppercase tracking-widest">URAIAN SINGKAT MASALAH</label></div>
                                <div class="p-8 custom-scrollbar overflow-y-auto max-h-[400px]"><p class="text-sm text-slate-800 italic text-justify whitespace-pre-line leading-[2]">"{{ $client->uraian_masalah ?? 'Tidak ada uraian.' }}"</p></div>
                            </div>
                            <div class="bg-green-50 rounded-[2.5rem] border border-green-200 overflow-hidden min-h-[350px]">
                                <div class="p-6 bg-green-100/50 border-b border-green-200 text-center"><label class="text-[10px] font-black text-green-800 uppercase tracking-widest">HARAPAN KLIEN</label></div>
                                <div class="p-8 flex items-center justify-center h-full"><p class="text-lg font-black text-slate-900 text-center uppercase tracking-tight">{{ $client->harapan_klien ?? 'Tidak ada harapan.' }}</p></div>
                            </div>
                        </div>
                    </section>

{{-- Riwayat Konsultasi --}}
<section>
    <div class="flex items-center gap-4 mb-8">
        <span class="h-px flex-1 bg-slate-200"></span>
        <h3 class="text-xs font-black text-green-700 uppercase tracking-[0.3em] flex items-center gap-2">
            <span class="bg-green-100 p-1.5 rounded-lg">📋</span> Riwayat Konsultasi
        </h3>
    </div>
    <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden shadow-sm overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[700px]">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">PERTEMUAN</th>
                    <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">TANGGAL</th>
                    <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">ADVOKAT</th>
                    <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">DIINPUT OLEH</th>
                    <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
               @forelse($client->consultationReports()->orderBy('pertemuan_ke', 'desc')->get() as $item)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-5">
                            <span class="bg-green-100 text-green-800 text-[10px] font-black px-3 py-1 rounded-full">
                                KE-{{ $item->pertemuan_ke ?? '-' }}
                            </span>
                        </td>
                        <td class="p-5 text-sm font-bold text-slate-700">
                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                        </td>
                        <td class="p-5 text-sm text-slate-600 font-medium uppercase tracking-tighter">
                            {{ $item->advokat_nama ?? '-' }}
                        </td>
<td class="p-5 text-sm text-slate-600 font-medium">
    @php
        $pembuat = \App\Models\User::find($item->user_id);
    @endphp
    @if($pembuat)
        @if($pembuat->role === 'advokat')
            Advokat
        @elseif($pembuat->role === 'admin')
            Admin
        @else
            {{ ucfirst($pembuat->role) }}
        @endif
        <br><small class="text-slate-500">({{ $pembuat->name }})</small>
    @elseif($item->dibuat_oleh_role)
        {{ ucfirst($item->dibuat_oleh_role) }}
    @else
        -
    @endif
</td>
                        <td class="p-5 text-right">
                            <a href="{{ route('consultation.print', $item->id) }}" target="_blank" class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-black transition shadow-md">
                                Lihat & Cetak
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-sm text-slate-400 italic">
                            Belum ada laporan konsultasi tersimpan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

                    {{-- Dokumen Final --}}
                    <section class="mt-8">
                        <div class="flex items-center gap-4 mb-6">
                            <span class="h-px flex-1 bg-slate-200"></span>
                            <h3 class="text-xs font-black text-green-700 uppercase tracking-[0.3em] flex items-center gap-3">
                                <span class="bg-green-100 p-2 rounded-xl shadow-sm">04</span> Dokumen Final Perkara
                            </h3>
                            <span class="h-px flex-1 bg-slate-200"></span>
                        </div>
                        <form action="{{ route('admin.clients.uploadPutusan', $client->id) }}" method="POST" enctype="multipart/form-data" class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                            @csrf
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h4 class="text-md font-black text-slate-800 uppercase tracking-tight">Dokumen Putusan</h4>
                                    @if($client->file_putusan)
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700">✔ Sudah Terunggah</span>
                                            <a href="{{ asset('storage/' . $client->file_putusan) }}" target="_blank" class="text-[10px] font-bold text-blue-600 hover:underline underline-offset-4">Lihat File</a>
                                        </div>
                                    @else
                                        <p class="text-[11px] text-slate-400 mt-1 italic">Belum ada dokumen yang diunggah.</p>
                                    @endif
                                </div>
                                <div class="bg-slate-100 p-3 rounded-2xl">
                                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                            </div>
                            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-4 flex items-center gap-4">
                                <input type="file" name="file_putusan" class="text-xs text-slate-500 w-full file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-slate-800 file:text-white cursor-pointer">
                            </div>
                            <button type="submit" class="w-full mt-4 bg-slate-800 hover:bg-black text-white py-3 rounded-2xl text-xs font-black uppercase tracking-[0.1em] transition">
                                {{ $client->file_putusan ? 'Perbarui Dokumen' : 'Simpan Dokumen' }}
                            </button>
                        </form>
                    </section>

                    {{-- Dokumen Proses --}}
                    <section>
                        <div class="flex items-center gap-4 mb-8">
                            <span class="h-px flex-1 bg-slate-200"></span>
                            <h3 class="text-xs font-black text-green-700 uppercase tracking-[0.3em] flex items-center gap-2">
                                <span class="bg-green-100 px-2 py-0.5 rounded-md">04</span> Dokumen Proses & Tambahan
                            </h3>
                            <span class="h-px flex-1 bg-slate-200"></span>
                        </div>
                        <div class="flex justify-center">
                            <a href="{{ route('clients.showDocuments', $client->id) }}" class="flex flex-col items-center justify-center gap-3 p-6 bg-slate-900 text-white rounded-3xl hover:bg-black transition-all shadow-2xl shadow-slate-300 active:scale-95 group w-full max-w-sm">
                                <div class="bg-white/10 p-3 rounded-2xl group-hover:bg-yellow-400 transition-colors">
                                    <svg class="w-6 h-6 text-white group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </div>
                                <div class="text-center">
                                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 leading-none mb-1">Unggah & Lihat Berkas</p>
                                    <p class="text-base font-bold uppercase tracking-tight">Buka Galeri Dokumen</p>
                                    <p class="text-[9px] text-slate-400 mt-1">Surat kuasa, gugatan, bukti, mediasi, dll</p>
                                </div>
                            </a>
                        </div>
                    </section>

                </div>
            </div>
        </div>

{{-- MODAL ATUR JADWAL --}}
@if(in_array(trim($client->tahapan), [
    'KONFIRMASI KASUS (TLP/OFFLINE)',
    'MEDIASI (NON-LITIGASI)',
    'PENDAFTARAN E-COURT (LITIGASI)',
    'PROSES PERSIDANGAN',
    'TERTUNDA'
]) && !$sudahAdaLaporan)
<div x-show="openJadwal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-jadwal" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="openJadwal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="openJadwal = false" class="fixed inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div x-show="openJadwal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-middle bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/20">
            <div class="bg-purple-700 p-8 text-white text-center">
                <h3 class="font-black uppercase tracking-tight text-xl mb-1">Atur Jadwal Konsultasi</h3>
                <p class="text-purple-200 text-[10px] font-bold uppercase tracking-[0.3em]">Waktu Pertemuan</p>
            </div>
            <form action="{{ route('advokat.clients.aturJadwal', $client->id) }}" method="POST" class="p-10 space-y-6">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Tanggal Konsultasi <span class="text-xs text-slate-400">(opsional)</span></label>
                    <input type="date" name="jadwal_tanggal" value="{{ $client->jadwal_konsultasi ? $client->jadwal_konsultasi->format('Y-m-d') : '' }}" min="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:border-purple-500 focus:ring-0 transition">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Jam / Waktu <span class="text-xs text-slate-400">(opsional)</span></label>
                    <input type="time" name="jadwal_jam" value="{{ $client->jadwal_konsultasi ? $client->jadwal_konsultasi->format('H:i') : '' }}" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:border-purple-500 focus:ring-0 transition">
                </div>
                <div class="text-xs text-slate-500 italic">* Kosongkan jika tidak ingin mengatur jadwal</div>
                <div class="flex flex-col gap-3 pt-4">
                    <button type="submit" class="w-full bg-purple-700 text-white font-black uppercase tracking-widest py-5 rounded-2xl shadow-xl hover:bg-purple-800 transition-all active:scale-95">Simpan Jadwal</button>
                    <button type="button" @click="openJadwal = false" class="w-full bg-white text-slate-400 font-black uppercase tracking-widest py-5 rounded-2xl hover:text-red-500 transition-colors">Batalkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- MODAL UPDATE STATUS --}}
<div x-show="openStatus" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="openStatus" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="openStatus = false" class="fixed inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div x-show="openStatus" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-middle bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/20">
            <div class="bg-slate-900 p-8 text-white text-center">
                <h3 class="font-black uppercase tracking-tight text-xl mb-1">Update Progress & Advice</h3>
                <p class="text-green-400 text-[10px] font-bold uppercase tracking-[0.3em]">Asesmen Advokat</p>
            </div>
            <form action="{{ route('advokat.clients.updateStatus', $client->id) }}" method="POST" class="p-10 space-y-6">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Pilih Langkah Hukum</label>
                    <select name="tahapan" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:border-green-500 focus:ring-0 transition appearance-none cursor-pointer">
                        <option value="KONFIRMASI KASUS (TLP/OFFLINE)" {{ $client->tahapan == 'KONFIRMASI KASUS (TLP/OFFLINE)' ? 'selected' : '' }}>KONFIRMASI KASUS (TLP/OFFLINE)</option>
                        <option value="MEDIASI (NON-LITIGASI)" {{ $client->tahapan == 'MEDIASI (NON-LITIGASI)' ? 'selected' : '' }}>MEDIASI (NON-LITIGASI)</option>
                        <option value="PENDAFTARAN E-COURT (LITIGASI)" {{ $client->tahapan == 'PENDAFTARAN E-COURT (LITIGASI)' ? 'selected' : '' }}>PENDAFTARAN E-COURT (LITIGASI)</option>
                        <option value="PROSES PERSIDANGAN" {{ $client->tahapan == 'PROSES PERSIDANGAN' ? 'selected' : '' }}>PROSES PERSIDANGAN</option>
                        <option value="TERTUNDA" {{ $client->tahapan == 'TERTUNDA' ? 'selected' : '' }}>TERTUNDA</option>
                        <option value="KASUS SELESAI (ARSIP)" {{ $client->tahapan == 'KASUS SELESAI (ARSIP)' ? 'selected' : '' }}>KASUS SELESAI (ARSIP)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Tulis Catatan / Legal Advice</label>
                    <textarea name="legal_advice" rows="4" required class="w-full bg-slate-50 border-2 border-green-500 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:border-green-600 focus:ring-0 transition">{{ old('legal_advice', $client->legal_advice ?? '') }}</textarea>
                </div>
                <div class="flex flex-col gap-3 pt-4">
                    <button type="submit" class="w-full bg-slate-900 text-white font-black uppercase tracking-widest py-5 rounded-2xl shadow-xl hover:bg-black transition-all active:scale-95">Simpan Perubahan</button>
                    <button type="button" @click="openStatus = false" class="w-full bg-white text-slate-400 font-black uppercase tracking-widest py-5 rounded-2xl hover:text-red-500 transition-colors">Batalkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
        {{-- TOAST NOTIFICATION --}}
        <div x-data="{ show: false, message: '' }" x-init="@if(session('success')) message = '{{ session('success') }}'; show = true; setTimeout(() => show = false, 5000); @endif" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform translate-y-2" class="fixed bottom-5 right-5 z-[10000]" style="display: none;">
            <div class="bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl border-l-4 border-green-500 flex items-center gap-4">
                <div class="bg-green-500/20 p-2 rounded-full">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 leading-none mb-1">System Message</p>
                    <p class="text-sm font-bold" x-text="message"></p>
                </div>
                <button @click="show = false" class="ml-4 text-slate-500 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

    </div>
</x-app-layout>

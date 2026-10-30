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
            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 10px;
            }
            [x-cloak] {
                display: none !important;
            }
            @keyframes bounce-in {
                0% { transform: scale(0.9); opacity: 0; }
                70% { transform: scale(1.05); }
                100% { transform: scale(1); opacity: 1; }
            }
            .animate-bounce-in {
                animation: bounce-in 0.4s cubic-bezier(0, 0, 0.2, 1);
            }
        </style>

        <div class="py-12 pt-24 pb-20 bg-slate-100 min-h-screen" x-data="{ openStatus: false, openAdvokat: false }">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

                {{-- Pesan Sukses --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-500 text-white rounded-2xl font-bold text-sm shadow-lg animate-bounce-in flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Pesan Error --}}
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-500 text-white rounded-2xl font-bold text-sm shadow-lg animate-bounce-in flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- ✅ Penanda Status Jika Sudah Diarsip / Selesai --}}
                @php
                    $isClosed = in_array($client->status, ['Selesai', 'Ditolak', 'Tidak Lanjut', 'Rujuk', 'Inkracht']);
                @endphp

                @if($isClosed)
                <div class="mb-6 p-4 border-l-4 bg-slate-50 border-slate-400 rounded-r-xl shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5z"/>
                        </svg>
                        <div>
                            <h4 class="font-black text-slate-800 uppercase">📁 BERKAS TELAH DITUTUP / DIARSIPKAN</h4>
                            <p class="text-xs text-slate-500">Status akhir: {{ strtoupper($client->status) }} • Data hanya dapat dilihat dan dicetak saja</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ✅ NAVIGASI TOMBOL KEMBALI + DEFINISI SEMUA VARIABEL DI ATAS --}}
                <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                  @php
    $isAdmin = Auth::user()->role === 'admin';
    $dari = strtolower(trim(request()->query('from', '')));

    if ($dari === 'archive') {
        $backRoute = route('admin.arsip');
        $backText  = 'Kembali ke Arsip';
    } elseif ($dari === 'active') {
        $backRoute = route('admin.kelola-kasus');
        $backText  = 'Kembali ke Kasus Aktif';
    } else {
        $backRoute = $isAdmin ? route('admin.dashboard') : route('advokat.dashboard');
        $backText  = 'Kembali';
    }

    // ✅ SEMUA VARIABEL DIDEFINISIKAN DI SINI SEKALI SAJA
    $tahapanSkrg = $client->tahapan ?? $client->status;
    $kasusTutup = in_array($tahapanSkrg, [
        'Selesai', 'Ditolak', 'Tidak Lanjut', 'Rujuk', 'Inkracht',
        'Perkara telah selesai ditangani', 'Dirujuk ke:', 'Tidak memenuhi syarat bantuan hukum'
    ]);
    $sudahVerifikasi2 = (
        str_starts_with($tahapanSkrg, 'Dalam pendampingan') ||
        in_array($tahapanSkrg, [
            'Siap ditangani oleh Advokat', 'Menunggu Kedatangan Klien',
            'MEDIASI (NON-LITIGASI)', 'PENDAFTARAN E-COURT (LITIGASI)',
            'PROSES PERSIDANGAN', 'TERTUNDA'
        ])
    );

    // 👇 INI YANG KURANG! TAMBAHKAN BARIS INI DI SINI
    $bisaTampilVerifikasi2 = !empty($client->jadwal_konsultasi)
                              && $client->jadwal_konsultasi != '0000-00-00 00:00:00'
                              && !$kasusTutup;
@endphp
                    <a href="{{ $backRoute }}" class="inline-flex items-center gap-2 text-slate-500 font-black text-xs uppercase tracking-widest hover:text-green-700 transition">
                        <div class="bg-white p-2 rounded-lg shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </div>
                        {{ $backText }}
                    </a>

                    <div class="flex flex-wrap items-center gap-2">
                        {{-- Tombol Cetak Dokumen - Selalu tampil --}}
                        <a href="{{ route('admin.clients.print', $client->id) }}" target="_blank" class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z"/>
                            </svg>
                            Cetak
                        </a>

                        {{-- Tombol Surat Rujukan - Selalu tampil jika ada statusnya --}}
                        @if($client->status === 'Rujuk')
                            <a href="{{ route('admin.surat.rujukan', $client->id) }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Lihat & Cetak Surat Rujukan
                            </a>
                        @endif

                        {{-- ✅ TOMBOL KHUSUS KASUS MASIH AKTIF SAJA --}}
                        @if(!$isClosed)

                           {{-- ✅ Tombol Verifikasi 2: Muncul selagi ada jadwal & kasus belum selesai --}}
@if($bisaTampilVerifikasi2)
    <a href="{{ route('admin.show.verifikasi', $client->id) }}" class="inline-flex items-center gap-2 bg-amber-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-700 transition shadow-lg">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Lihat Verifikasi 2
    </a>
@endif

                            {{-- Tombol Laporan Konsultasi --}}
                            @if($isAdmin || Auth::user()->role === 'advokat')
                                <a href="{{ route('admin.consultation.create', $client->id) }}" class="inline-flex items-center gap-2 bg-slate-900 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-black transition shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Laporan Konsultasi
                                </a>
                            @endif

                            {{-- Tombol Jadwal Konsultasi --}}
                            <button @click="openStatus = true" class="bg-green-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-green-700 transition shadow-lg">
                                Jadwal Konsultasi
                            </button>
                        @endif
                    </div>
                </div>

                {{-- ✅ Penunjukan Advokat - Hanya tampil jika kasus belum selesai --}}
                @if(!$isClosed)
                <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200 mb-6">
                    <h3 class="text-lg font-bold mb-4">Penunjukan Advokat</h3>

                    @if($client->status === 'Proses' && !$client->advokat_id)
                        <button @click="openAdvokat = true" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 transition shadow-lg">
                            Tunjuk Advokat
                        </button>
                    @elseif($client->advokat_id)
                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm text-green-800">✅ Sudah ditangani oleh: <strong>{{ $client->advokat->name }}</strong></p>
                        </div>
                    @else
                        <p class="text-sm text-slate-400 italic">Langkah ini tersedia setelah verifikasi tahap 2 disetujui.</p>
                    @endif

                    {{-- Modal Pilih Advokat --}}
                    <div x-show="openAdvokat"
                        x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                        @click.away="openAdvokat = false">

                        <div class="bg-white p-6 rounded-3xl shadow-2xl w-full max-w-sm animate-bounce-in">
                            <h3 class="text-sm font-black uppercase mb-4">Pilih Advokat</h3>
                            <form action="{{ route('admin.clients.tunjuk-advokat', $client->id) }}" method="POST">
                                @csrf
                                <select name="advokat_id" class="w-full mb-4 border-slate-200 rounded-xl text-sm" required>
                                    <option value="">-- Pilih Advokat --</option>
                                    @foreach($advokats as $advokat)
                                        <option value="{{ $advokat->id }}">{{ $advokat->name }} ({{ $advokat->cases_count }} kasus)</option>
                                    @endforeach
                                </select>
                                <div class="flex gap-2">
                                    <button type="button" @click="openAdvokat = false" class="flex-1 bg-slate-100 py-2 rounded-xl text-xs font-bold uppercase">Batal</button>
                                    <button type="submit" class="flex-1 bg-emerald-600 text-white py-2 rounded-xl text-xs font-bold uppercase">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                {{-- Keterangan jika sudah selesai --}}
                <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200 mb-6">
                    <h3 class="text-lg font-bold mb-4">Penunjukan Advokat</h3>
                    @if($client->advokat_id)
                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm text-green-800">✅ Ditangani oleh: <strong>{{ $client->advokat->name }}</strong></p>
                        </div>
                    @else
                        <p class="text-sm text-slate-400 italic">Proses kasus telah ditutup.</p>
                    @endif
                </div>
                @endif

                {{-- Kartu Konten Utama --}}
                <div class="bg-white shadow-2xl rounded-[2.5rem] overflow-hidden border border-slate-200">

                    {{-- Header Kartu --}}
                    <div class="bg-slate-900 p-8 md:p-10 text-white flex justify-between items-center border-b-4 border-green-500">
                        <div>
                            <p class="text-[10px] font-black text-green-400 uppercase tracking-[0.4em] mb-2">Timestamp Pendaftaran</p>
                            <p class="text-2xl font-mono font-bold tracking-tight">{{ $client->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="bg-green-600 text-white px-5 py-2.5 rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg">{{ $client->reg_code }}</span>
                            <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-widest">{{ $client->kategori_layanan }}</p>
                        </div>
                    </div>

                    <div class="p-8 md:p-14 space-y-16">

                    {{-- ✅ BAGIAN JADWAL KONSULTASI - HILANG SETELAH VERIFIKASI 2 --}}
                    @if(!$sudahVerifikasi2 && !$kasusTutup)
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
                                    $jadwalValid = false;
                                    $tanggalJadwal = null;
                                    if (!empty($client->jadwal_konsultasi) && $client->jadwal_konsultasi != '0000-00-00 00:00:00') {
                                        try {
                                            $tanggalJadwal = \Carbon\Carbon::parse($client->jadwal_konsultasi);
                                            if ($tanggalJadwal->year >= 2020) $jadwalValid = true;
                                        } catch (\Exception $e) { $jadwalValid = false; }
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
                                                @if(!empty($client->catatan_admin))
                                                    <hr class="my-2 border-purple-100">
                                                    <p class="text-sm text-slate-600 mt-1">📝 Keterangan: {{ $client->catatan_admin }}</p>
                                                @endif
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
                                        <p class="text-xs text-slate-500 mt-1">Klik tombol "Jadwal Konsultasi" untuk menentukan waktu pertemuan</p>
                                    </div>
                                @endif
                            </div>
                        </section>
                    @endif

                        {{-- Status & Progres Terkini --}}
                        <section class="bg-green-50 border-2 border-green-200 rounded-[2.5rem] p-8">
                            <div class="flex items-center gap-3 mb-6 mt-3">
                                <div class="bg-green-600 p-2 rounded-xl text-white font-bold text-[10px]">INFO</div>
                                <h3 class="font-black text-slate-800 uppercase tracking-tight text-sm">Status & Progres Terkini</h3>
                            </div>

                            {{-- Informasi Pengubah Terakhir --}}
                            <div class="mb-6 p-4 bg-white border border-slate-200 rounded-3xl shadow-sm flex items-center gap-3">
                                <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Terakhir Diubah Oleh</p>
                                    <p class="text-sm font-bold text-slate-700">
                                        {{ $client->editor ? $client->editor->name : 'Data Kosong' }}
                                        <span class="text-slate-400 font-medium">
                                            pada {{ $client->updated_at ? $client->updated_at->format('d/m/Y H:i') : 'N/A' }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                        {{-- Detail Status --}}
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-green-100">
                            <div class="mb-4 flex justify-between items-center">
                                <span class="text-[9px] font-black bg-purple-600 text-white px-3 py-1 rounded-full uppercase tracking-widest">
                                    {{ $client->tahapan ?? $client->status }}
                                </span>
                                @if($client->advokat)
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Advokat: {{ $client->advokat->name }}</span>
                                @endif
                            </div>
                            <p class="text-slate-600 text-sm leading-relaxed italic font-medium">
                                @if(!empty($client->catatan_admin))
                                    {{ $client->catatan_admin }}
                                @elseif(!empty($client->legal_advice))
                                    {{ $client->legal_advice }}
                                @else
                                    Belum ada catatan tambahan.
                                @endif
                            </p>
                        </div>
                        </section>

                    {{-- Data Identitas Klien --}}
                        <section>
                            <div class="flex items-center gap-4 mb-8">
                                <span class="h-px flex-1 bg-slate-200"></span>
                                <h3 class="text-xs font-black text-green-700 uppercase tracking-[0.3em] flex items-center gap-2">
                                    <span class="bg-green-100 p-1.5 rounded-lg">01</span> Identitas Klien
                                </h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-sm">
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">NAMA LENGKAP</label>
                                    <p class="text-2xl font-black text-slate-800 uppercase tracking-tighter">{{ $client->nama }}</p>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">NIK</label>
                                    <p class="text-lg font-bold text-slate-700">{{ $client->nik ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">ALAMAT EMAIL</label>
                                    <p class="text-lg font-bold text-slate-700">{{ $client->email ?? 'Tidak Mengisi Email' }}</p>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">STATUS PERKAWINAN</label>
                                    <p class="text-lg font-bold text-slate-700 uppercase">{{ $client->status_perkawinan ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">TEMPAT TANGGAL LAHIR</label>
                                    <p class="text-lg font-bold text-slate-700">{{ $client->tempat_tanggal_lahir }}</p>
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

                        {{-- Alamat & Kontak --}}
                        <section>
                            <div class="flex items-center gap-4 mb-8">
                                <span class="h-px flex-1 bg-slate-200"></span>
                                <h3 class="text-xs font-black text-green-700 uppercase tracking-[0.3em] flex items-center gap-2">
                                    <span class="bg-green-100 p-1.5 rounded-lg">02</span> Alamat & Kontak
                                </h3>
                            </div>
                            <div class="grid grid-cols-1 gap-8">
                                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200">
                                    <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">ALAMAT LENGKAP (KTP)</label>
                                    <p class="text-base font-semibold text-slate-700 uppercase">{{ $client->alamat_ktp }}</p>
                                </div>
                                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200">
                                    <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">ALAMAT DOMISILI</label>
                                    <p class="text-base font-semibold text-slate-700 uppercase">{{ $client->alamat_domisili }}</p>
                                </div>

                                @php
                                    $waNumber = preg_replace('/[^0-9]/', '', $client->phone);
                                    if(str_starts_with($waNumber, '0')) {
                                        $waNumber = '62' . substr($waNumber, 1);
                                    }
                                    $waMessage = rawurlencode("Halo Bapak/Ibu " . $client->nama . ", dari Posbakum PW Aisyiyah DKI Jakarta.");
                                @endphp

                                <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" target="_blank" class="inline-flex items-center gap-4 bg-green-50 p-6 rounded-3xl border-2 border-green-200 hover:bg-green-100 transition shadow-sm group">
                                    <div class="bg-green-600 p-3 rounded-2xl text-white shadow-lg group-hover:scale-110 transition">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.011 20.29c-3.553 0-6.441-2.89-6.441-6.44 0-1.21.33-2.34.89-3.3l-.53-1.93 1.98.52c.93-.53 2.01-.84 3.15-.84 3.55 0 6.44 2.89 6.44 6.44s-2.89 6.44-6.44 6.44m8.12-14.06c-2.17-2.16-5.06-3.35-8.13-3.35-6.34 0-11.5 5.17-11.5 11.5 0 2.03.53 4.01 1.54 5.75L1 23l6.58-1.72c1.68.92 3.58 1.4 5.52 1.4 6.34 0 11.5-5.17 11.5-11.5 0-3.07-1.2-5.95-3.37-8.13z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-black text-green-700 uppercase block">HUBUNGI KLIEN VIA WHATSAPP</label>
                                        <p class="text-2xl font-black text-slate-800 tracking-tighter">{{ $client->phone }}</p>
                                    </div>
                                </a>
                            </div>
                        </section>

                        {{-- Uraian Perkara --}}
                        <section>
                            <div class="flex items-center gap-4 mb-8">
                                <span class="h-px flex-1 bg-slate-200"></span>
                                <h3 class="text-xs font-black text-green-700 uppercase tracking-[0.3em] flex items-center gap-2">
                                    <span class="bg-green-100 p-1.5 rounded-lg">03</span> Uraian Perkara
                                </h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                                <div class="bg-amber-50 rounded-[2.5rem] border border-amber-200 overflow-hidden min-h-[350px]">
                                    <div class="p-6 bg-amber-100/50 border-b border-amber-200 text-center">
                                        <label class="text-[10px] font-black text-amber-800 uppercase tracking-widest">URAIAN SINGKAT MASALAH</label>
                                    </div>
                                    <div class="p-8 custom-scrollbar overflow-y-auto max-h-[400px]">
                                        <p class="text-sm text-slate-800 italic text-justify whitespace-pre-line leading-[2]">"{{ $client->uraian_masalah ?? 'Tidak ada uraian.' }}"</p>
                                    </div>
                                </div>
                                <div class="bg-green-50 rounded-[2.5rem] border border-green-200 overflow-hidden min-h-[350px]">
                                    <div class="p-6 bg-green-100/50 border-b border-green-200 text-center">
                                        <label class="text-[10px] font-black text-green-800 uppercase tracking-widest">HARAPAN KLIEN</label>
                                    </div>
                                    <div class="p-8 flex items-center justify-center h-full">
                                        <p class="text-lg font-black text-slate-900 text-center uppercase tracking-tight">{{ $client->harapan_klien ?? 'Tidak ada harapan.' }}</p>
                                    </div>
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
                                KE-{{ $item->pertemuan_ke }}
                            </span>
                        </td>
                        <td class="p-5 text-sm font-bold text-slate-700">
                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                        </td>
                        <td class="p-5 text-sm text-slate-700 font-medium">
                            {{ $item->advokat_nama ?? '-' }}
            <td class="p-5 text-sm text-slate-600 font-medium">
        @php
            // Ambil data pembuat laporan dari tabel users
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

                        {{-- ✅ Dokumen Final - Nonaktifkan unggah jika sudah selesai --}}
                        <section class="mt-8">
                            <div class="flex items-center gap-4 mb-6">
                                <span class="h-px flex-1 bg-slate-200"></span>
                                <h3 class="text-xs font-black text-green-700 uppercase tracking-[0.3em] flex items-center gap-3">
                                    <span class="bg-green-100 p-2 rounded-xl shadow-sm">04</span>
                                    Dokumen Final Perkara
                                </h3>
                                <span class="h-px flex-1 bg-slate-200"></span>
                            </div>

                            @if(!$isClosed)
                            <form action="{{ route('admin.clients.uploadPutusan', $client->id) }}" method="POST" enctype="multipart/form-data" class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                                @csrf
                            @else
                            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                            @endif

                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <h4 class="text-md font-black text-slate-800 uppercase tracking-tight">Dokumen Putusan</h4>

                                        @if($client->file_putusan)
                                            <div class="mt-2 flex items-center gap-2">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700">
                                                    ✔ Sudah Terunggah
                                                </span>
                                                <a href="{{ asset('storage/' . $client->file_putusan) }}" target="_blank" class="text-[10px] font-bold text-blue-600 hover:underline underline-offset-4">
                                                    Lihat File
                                                </a>
                                            </div>
                                        @else
                                            <p class="text-[11px] text-slate-400 mt-1 italic">Belum ada dokumen yang diunggah.</p>
                                        @endif
                                    </div>

                                    <div class="bg-slate-100 p-3 rounded-2xl">
                                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                </div>

                                @if(!$isClosed)
                                <div class="border-2 border-dashed border-slate-200 rounded-2xl p-4 flex items-center gap-4">
                                    <input type="file" name="file_putusan" class="text-xs text-slate-500 w-full file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-slate-800 file:text-white cursor-pointer">
                                </div>

                                <button type="submit" class="w-full mt-4 bg-slate-800 hover:bg-black text-white py-3 rounded-2xl text-xs font-black uppercase tracking-[0.1em] transition">
                                    {{ $client->file_putusan ? 'Perbarui Dokumen' : 'Simpan Dokumen' }}
                                </button>
                                @else
                                <p class="text-xs text-slate-500 italic mt-2">*Unggah dokumen dinonaktifkan karena kasus sudah ditutup</p>
                                @endif

                            @if(!$isClosed)
                            </form>
                            @else
                            </div>
                            @endif
                        </section>

                        {{-- Arsip Berkas Digital --}}
                        <section class="pb-10">
                            <div class="flex items-center gap-4 mb-8">
                                <span class="h-px flex-1 bg-slate-200"></span>
                                <h3 class="text-[10px] font-black text-green-700 uppercase tracking-[0.3em] flex items-center gap-2">
                                    <span class="bg-green-100 px-2 py-0.5 rounded-md">05</span> Arsip Berkas Digital
                                </h3>
                                <span class="h-px flex-1 bg-slate-200"></span>
                            </div>

                            <div class="space-y-8">
                                <div class="flex justify-center">
                                    <a href="{{ route('clients.showDocuments', $client->id) }}" class="flex flex-col items-center justify-center gap-3 p-6 bg-slate-900 text-white rounded-3xl hover:bg-black transition-all shadow-2xl active:scale-95 group w-full max-w-sm">
                                        <div class="bg-white/10 p-3 rounded-2xl group-hover:bg-yellow-400 transition-colors">
                                            <svg class="w-6 h-6 text-white group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Akses Dokumen</p>
                                            <p class="text-base font-bold uppercase tracking-tight">Buka Galeri Berkas</p>
                                        </div>
                                    </a>
                                </div>

                                @if(!$isClosed && $isAdmin && $client->status === 'Pendaftaran Diterima')
                                    <div class="max-w-2xl mx-auto pt-6 border-t border-dashed border-slate-200">
                                        <form action="{{ route('admin.clients.approve', $client->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" onclick="return confirm('Verifikasi berkas ini?')" class="w-full bg-green-600 hover:bg-green-700 text-white p-5 rounded-[2rem] shadow-xl transition-all flex items-center justify-center gap-4 group">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <div class="text-left">
                                                    <p class="text-[9px] font-black uppercase tracking-[0.2em] opacity-80">Langkah 1</p>
                                                    <p class="text-lg font-black uppercase tracking-tight">Terima & Verifikasi Berkas</p>
                                                </div>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </section>
                    </div>
                </div>
            </div>

            {{-- Modal Update Status & Jadwal - Hanya tampil jika kasus aktif --}}
            @if(!$isClosed)
            <div x-show="openStatus" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div x-show="openStatus" x-transition.opacity @click="openStatus = false" class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm"></div>

                    <div x-show="openStatus" x-transition.scale.95 class="inline-block bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg sm:w-full z-50">
                        <div class="bg-slate-900 p-8 text-white text-center">
                            <h3 class="font-black uppercase tracking-tight text-xl mb-1">Update Jadwal Konsultasi</h3>
                            <p class="text-green-400 text-[10px] font-bold uppercase tracking-[0.3em]">Manajemen Jadwal Konsultasi</p>
                        </div>

                        @php
                            $formAction = $isAdmin ? route('admin.clients.updateStatus', $client->id) : route('advokat.clients.updateStatus', $client->id);
                        @endphp

                        <form action="{{ $formAction }}" method="POST" class="p-10 space-y-6">
                            @csrf
                            @method('PATCH')

                            <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100">
                                <label class="block text-[10px] font-black text-blue-800 uppercase tracking-widest mb-3">Jadwalkan Konsultasi</label>
                                <input type="datetime-local" name="jadwal_konsultasi" value="{{ $client->jadwal_konsultasi ? \Carbon\Carbon::parse($client->jadwal_konsultasi)->format('Y-m-d\TH:i') : '' }}" class="w-full bg-white border-2 border-blue-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Catatan untuk Klien</label>
                                <textarea name="catatan_admin" rows="3" placeholder="Contoh: Mohon membawa berkas pendukung asli saat konsultasi..." class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm font-medium text-slate-700">{{ $client->catatan_admin }}</textarea>
                            </div>

                            <input type="hidden" name="status" value="Konsultasi Offline">

                            <div class="flex gap-4 pt-4">
                                <button type="button" @click="openStatus = false" class="flex-1 px-6 py-4 rounded-2xl text-[10px] font-black uppercase text-slate-400 hover:bg-slate-100 transition">Batal</button>
                                <button type="submit" class="flex-1 bg-green-600 text-white px-6 py-4 rounded-2xl text-[10px] font-black uppercase shadow-lg hover:bg-green-700 transition">Simpan Jadwal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            {{-- Modal Notifikasi Otomatis --}}
            @if(session('success_jadwal'))
                <div id="modalOtomatisFonnte" class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-sm">
                    <div class="bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl">
                        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-8 text-center text-white">
                            <h3 class="text-2xl font-black uppercase tracking-tight">Kirim Notifikasi</h3>
                            <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mt-1">Status: Otomatis (Fonnte API)</p>
                        </div>
                        <div class="p-8 space-y-4">
                            @php $data = session('success_jadwal'); @endphp
                            <button type="button" onclick="eksekusiWA('{{ $data['phone_klien'] }}', 'Halo {{ $data['nama_klien'] }}, jadwal konsultasi Anda pada: {{ $data['jadwal'] }}.', this)" class="w-full flex items-center justify-between p-5 bg-green-50 border-2 border-green-100 rounded-3xl group">
                                <div class="flex items-center gap-4">
                                    <div class="status-icon bg-green-600 text-white p-3 rounded-2xl"><i class="fa-solid fa-paper-plane"></i></div>
                                    <div>
                                        <p class="text-[10px] font-black text-green-700 uppercase">Kirim ke Klien</p>
                                        <p class="text-sm font-black text-slate-800">{{ $data['nama_klien'] }}</p>
                                    </div>
                                </div>
                            </button>
                            <button type="button" onclick="eksekusiWA('{{ $data['phone_advokat'] }}', 'Halo Advokat {{ $data['nama_advokat'] }}, Anda ditunjuk menangani klien {{ $data['nama_klien'] }}. Jadwal: {{ $data['jadwal'] }}', this)" class="w-full flex items-center justify-between p-5 bg-blue-50 border-2 border-blue-100 rounded-3xl group">
                                <div class="flex items-center gap-4">
                                    <div class="status-icon bg-blue-600 text-white p-3 rounded-2xl"><i class="fa-solid fa-paper-plane"></i></div>
                                    <div>
                                        <p class="text-[10px] font-black text-blue-700 uppercase">Kirim ke Advokat</p>
                                        <p class="text-sm font-black text-slate-800">{{ $data['nama_advokat'] }}</p>
                                    </div>
                                </div>
                            </button>
                            <button onclick="document.getElementById('modalOtomatisFonnte').remove()" class="w-full py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tutup Halaman</button>
                        </div>
                    </div>
                </div>

                <script>
                    function eksekusiWA(phone, pesan, btn) {
                        const iconBox = btn.querySelector('.status-icon');
                        btn.disabled = true;
                        iconBox.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

                        fetch("{{ route('admin.clients.ajaxKirimWA') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                target_phone: phone,
                                pesan: pesan
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if(data.success) {
                                iconBox.innerHTML = '<i class="fa-solid fa-check"></i>';
                                iconBox.classList.add('bg-emerald-500');
                                alert('Berhasil terkirim otomatis!');
                            } else {
                                alert('Gagal: ' + data.message);
                                iconBox.innerHTML = '<i class="fa-solid fa-paper-plane"></i>';
                            }
                        })
                        .finally(() => {
                            btn.disabled = false;
                        });
                    }
                </script>
            @endif
        </div>
    </x-app-layout>

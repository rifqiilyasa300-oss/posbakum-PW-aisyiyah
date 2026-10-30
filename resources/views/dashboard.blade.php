<x-app-layout>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    html { scroll-behavior: smooth; }
    section { scroll-margin-top: 80px; }
    .hero-section {
        position: relative;
        background-image: url("{{ asset('images/posbakum.jpg') }}");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    .hero-overlay { background: linear-gradient(to bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)); }

    /* Gaya untuk tahapan advokat menjorok ke dalam */
    .alur-advokat {
        margin-left: 28px;
        border-left: 2px dashed #cbd5e1;
        padding-left: 24px;
        position: relative;
    }
    /* Sembunyikan langkah yang belum tercapai */
    .langkah-terkunci {
        display: none !important;
    }
</style>

<div class="bg-gray-50 text-gray-800 antialiased min-h-screen">
    {{-- HERO SECTION --}}
    <section class="hero-section min-h-[90vh] flex items-center">
        <div class="hero-overlay absolute inset-0"></div>

@php
    $hasAnyPerkara = !is_null($activePerkara);
    $riwayatPerkara = $riwayatPerkara ?? collect();

    // ✅ Tahapan dasar sebelum bercabang
    $alurDasar = [
        'Menunggu Verifikasi',
        'Pendaftaran Diterima',
        'Perlu Perbaikan',
        'PENINJAUAN BERKAS',
        'Konsultasi Offline',
        'Proses',
        'Tunjuk Advokat',
        'KONFIRMASI KASUS (TLP/OFFLINE)'
    ];

    // ✅ Jalur 1: Litigasi
    $jalurLitigasi = [
        'PENDAFTARAN E-COURT (LITIGASI)',
        'PROSES PERSIDANGAN',
        'TERTUNDA',
        'KASUS SELESAI (ARSIP)'
    ];

    // ✅ Jalur 2: Non-Litigasi / Mediasi
    $jalurMediasi = [
        'MEDIASI (NON-LITIGASI)',
        'TERTUNDA',
        'KASUS SELESAI (ARSIP)'
    ];

    // ✅ Daftar padanan status
    $statusMap = [
        'menunggu verifikasi'               => 'Menunggu Verifikasi',
        'pendaftaran diterima'              => 'Pendaftaran Diterima',
        'perlu perbaikan'                   => 'Perlu Perbaikan',
        'revisi'                            => 'Perlu Perbaikan',
        'peninjauan berkas'                 => 'PENINJAUAN BERKAS',
        'konsultasi offline'                => 'Konsultasi Offline',
        'konsultasi'                        => 'Konsultasi Offline',
        'proses'                            => 'Proses',
        'tunjuk advokat'                    => 'Tunjuk Advokat',
        'ditangani advokat'                 => 'Tunjuk Advokat',
        'penugasan advokat'                 => 'Tunjuk Advokat',
        'konfirmasi kasus'                  => 'KONFIRMASI KASUS (TLP/OFFLINE)',
        'konfirmasi kasus (tlp/offline)'    => 'KONFIRMASI KASUS (TLP/OFFLINE)',
        'mediasi'                           => 'MEDIASI (NON-LITIGASI)',
        'mediasi (non-litigasi)'            => 'MEDIASI (NON-LITIGASI)',
        'pendaftaran e-court'               => 'PENDAFTARAN E-COURT (LITIGASI)',
        'pendaftaran e-court (litigasi)'    => 'PENDAFTARAN E-COURT (LITIGASI)',
        'proses persidangan'                => 'PROSES PERSIDANGAN',
        'tertunda'                          => 'TERTUNDA',
        'kasus selesai'                     => 'KASUS SELESAI (ARSIP)',
        'kasus selesai (arsip)'             => 'KASUS SELESAI (ARSIP)',
        'selesai'                           => 'KASUS SELESAI (ARSIP)'
    ];

    // ✅ Baca dan bersihkan status dari database
    $dbStatus = strtolower(trim(preg_replace('/\s+/', ' ', $activePerkara->status ?? '')));
    $catatanAdmin = strtolower($activePerkara->catatan_admin ?? '');

    $isPerluRevisi = (
        str_contains($catatanAdmin, 'perlu perbaikan') ||
        str_contains($catatanAdmin, 'revisi') ||
        $dbStatus === 'perlu perbaikan' ||
        $dbStatus === 'revisi'
    );

    if ($isPerluRevisi) {
        $langkahAktif = 'Perlu Perbaikan';
    } else {
        if (isset($statusMap[$dbStatus])) {
            $langkahAktif = $statusMap[$dbStatus];
        } else {
            if (str_contains($dbStatus, 'konfirmasi kasus')) {
                $langkahAktif = 'KONFIRMASI KASUS (TLP/OFFLINE)';
            } elseif (str_contains($dbStatus, 'mediasi')) {
                $langkahAktif = 'MEDIASI (NON-LITIGASI)';
            } elseif (str_contains($dbStatus, 'pendaftaran e-court')) {
                $langkahAktif = 'PENDAFTARAN E-COURT (LITIGASI)';
            } elseif (str_contains($dbStatus, 'proses persidangan')) {
                $langkahAktif = 'PROSES PERSIDANGAN';
            } elseif (str_contains($dbStatus, 'tertunda')) {
                $langkahAktif = 'TERTUNDA';
            } elseif (str_contains($dbStatus, 'selesai') || str_contains($dbStatus, 'arsip')) {
                $langkahAktif = 'KASUS SELESAI (ARSIP)';
            } else {
                $langkahAktif = ucwords($dbStatus);
            }
        }

        // Cek apakah langkah dikenali
        if (!in_array($langkahAktif, array_merge($alurDasar, $jalurLitigasi, $jalurMediasi))) {
            $langkahAktif = 'Menunggu Verifikasi';
        }
    }

    // ✅ Susun alur TAMPIL hanya jalur yang dipilih saja
    if (in_array($langkahAktif, $jalurLitigasi)) {
        // Masuk jalur Litigasi → gabung dasar + jalur litigasi saja
        $alurTampil = array_merge($alurDasar, $jalurLitigasi);
    } elseif (in_array($langkahAktif, $jalurMediasi)) {
        // Masuk jalur Mediasi → gabung dasar + jalur mediasi saja
        $alurTampil = array_merge($alurDasar, $jalurMediasi);
    } else {
        // Masih di tahap dasar → tampilkan dasar saja
        $alurTampil = $alurDasar;
    }

    // ✅ Ambil sampai langkah aktif saja
    $posisi = array_search($langkahAktif, $alurTampil);
    if ($posisi === false) $posisi = 0;

    $riwayatTahapan = [];
    foreach ($alurTampil as $index => $langkah) {
        if ($langkah === 'Perlu Perbaikan' && !$isPerluRevisi) continue;
        if ($index <= $posisi) {
            $riwayatTahapan[] = $langkah;
        }
    }

    $isSelesai = in_array($langkahAktif, ['KASUS SELESAI (ARSIP)']);
    $isArsip = $isSelesai;

    if ($hasAnyPerkara) {
        if (!empty($activePerkara->legal_advice)) {
            $catatanTampil = $activePerkara->legal_advice;
            $sumberCatatan = 'CATATAN & ASESMEN DARI ADVOKAT';
            $warnaLatar = 'bg-green-50 border-green-100';
            $warnaTeks = 'text-green-700';
            $ikonCatatan = '📝';
        } elseif (!empty($activePerkara->catatan_admin)) {
            $catatanTampil = $activePerkara->catatan_admin;
            $sumberCatatan = 'CATATAN DARI ADMIN';
            $warnaLatar = 'bg-amber-50 border-amber-200';
            $warnaTeks = 'text-amber-700';
            $ikonCatatan = '📋';
        } else {
            $catatanTampil = 'Belum ada keterangan tambahan.';
            $sumberCatatan = 'INFORMASI';
            $warnaLatar = 'bg-gray-50 border-gray-200';
            $warnaTeks = 'text-gray-600';
            $ikonCatatan = 'ℹ️';
        }
    }
@endphp
        <div class="container mx-auto px-4 relative z-10 pt-20">
            <div class="flex flex-col items-center text-center max-w-4xl mx-auto">
                <span class="inline-block py-1 px-4 rounded-full bg-green-500/20 text-green-300 font-semibold text-sm mb-6 border border-green-500/30">
                    Posbakum PW ‘Aisyiyah DKI Jakarta
                </span>
                <h2 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight leading-[1.1]">
                    Keadilan Hukum <br> <span class="text-white">Untuk Semua.</span>
                </h2>
                <p class="text-green-50 text-xl mb-10 opacity-90 leading-relaxed max-w-2xl">
                    Memberikan akses keadilan yang transparan bagi seluruh lapisan masyarakat, khususnya perempuan dan anak.
                </p>

                <div class="flex flex-wrap justify-center gap-4">
                    <a href="https://wa.me/628161159564" target="_blank"
                    class="inline-flex items-center px-8 py-4 bg-white text-emerald-700 font-black rounded-2xl shadow-xl hover:bg-slate-100 transition no-underline group uppercase text-sm tracking-widest">
                        <i class="fab fa-whatsapp text-xl mr-3 text-emerald-500 group-hover:scale-110 transition"></i>
                        Call Center
                    </a>

                    {{-- ✅ Tampilkan tombol Isi Formulir jika BELUM ada kasus ATAU SUDAH selesai diarsip --}}
                    @if(!$activePerkara || $isArsip)
                        <a href="{{ route('client.register') }}"
                        class="inline-flex items-center px-8 py-4 bg-white text-emerald-900 font-black rounded-2xl shadow-lg hover:bg-amber-300 transition no-underline uppercase text-sm tracking-widest">
                            <i class="fas fa-edit mr-3"></i>
                            Isi Formulir Baru
                        </a>
                    @else
                        <button disabled
                            class="inline-flex items-center px-8 py-4 bg-slate-300 text-slate-500 font-black rounded-2xl cursor-not-allowed shadow-lg uppercase text-sm tracking-widest">
                            <i class="fas fa-lock mr-3"></i>
                            Perkara Sedang Berjalan
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </section>

        {{-- TRACKING & RIWAYAT --}}
        <section class="container mx-auto px-4 -mt-8 pb-20 relative z-20">

            {{-- Tampilkan alur HANYA jika ada perkara aktif dan BELUM diarsip --}}
            @if($hasAnyPerkara && !$isArsip)

                {{-- Notifikasi, jadwal, data perkara, dan alur langkah --}}
                <div class="max-w-4xl mx-auto mb-16">
                    <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.08)] border border-white/50 overflow-hidden">
                        <div class="bg-white/60 px-10 py-8 border-b border-slate-100 flex justify-between items-center flex-wrap gap-6">
                            <div>
                                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">Nomor Registrasi Perkara Aktif</p>
                                <h4 class="text-2xl font-black text-slate-900 font-mono tracking-tighter bg-emerald-50 px-3 py-1 rounded-lg inline-block">{{ $activePerkara->reg_code }}</h4>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('client.perkara.show', $activePerkara->id) }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-2xl text-xs font-black transition-all shadow-lg shadow-emerald-500/20 no-underline uppercase tracking-widest">
                                    <i class="fas fa-eye"></i> Lihat Data Perkara
                                </a>

                                {{-- ✅ TOMBOL REVISI BERKAS - MUNCUL JIKA STATUS SESUAI --}}
                                @php
                                    // Bersihkan spasi & ubah ke huruf kecil agar cocok
                                    $statusBersih = strtolower(trim($activePerkara->status ?? ''));
                                @endphp
                                @if(in_array($statusBersih, ['perlu perbaikan', 'ditolak', 'verifikasi ulang dokumen']))
                                    <a href="{{ route('client.edit', $activePerkara->id) }}"
                                    class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-2xl text-xs font-black transition-all shadow-lg shadow-amber-500/20 no-underline uppercase tracking-widest">
                                        <i class="fas fa-pen-to-square"></i> Revisi Berkas
                                    </a>
                                @endif
                            </div>
                        </div>

             {{-- ✅ TAMPILKAN JADWAL KONSULTASI - HANYA JIKA BELUM LEWAT VERIFIKASI 2 --}}
{{-- ✅ TAMPILKAN JADWAL SESUAI ATURAN YANG DIMINTA --}}
@if($activePerkara)
    @php
        // Ambil tahap aktif sekarang
        $langkahSkrg = $langkahAktif ?? ($activePerkara->status ?? '');

        // Daftar tahap di mana jadwal ADMIN harus disembunyikan
        $tahapSetelahVerifikasi = [
            'Tunjuk Advokat',
            'KONFIRMASI KASUS (TLP/OFFLINE)',
            'MEDIASI (NON-LITIGASI)',
            'PENDAFTARAN E-COURT (LITIGASI)',
            'PROSES PERSIDANGAN',
            'TERTUNDA',
            'KASUS SELESAI (ARSIP)'
        ];

        $sudahMasukTahapLanjut = in_array($langkahSkrg, $tahapSetelahVerifikasi);
    @endphp

    {{-- 📅 JADWAL ADMIN: Muncul HANYA jika BELUM masuk tahap lanjut --}}
    @if(!$sudahMasukTahapLanjut && !empty($activePerkara->jadwal_konsultasi))
        @php
            $waktu = \Carbon\Carbon::parse($activePerkara->jadwal_konsultasi);
        @endphp
        @if($waktu->isFuture())
            <div class="mx-8 my-6 p-5 bg-blue-50 border border-blue-200 rounded-xl shadow-sm">
                <h5 class="font-bold text-blue-800 text-base mb-3 flex items-center gap-2">
                    <i class="fas fa-calendar-check text-lg"></i> Jadwal Konsultasi
                </h5>
                <div class="text-blue-700 text-sm space-y-1.5">
                    <p>📅 Hari/Tanggal: <strong>{{ $waktu->translatedFormat('l, d F Y') }}</strong></p>
                    <p>⏰ Pukul: <strong>{{ $waktu->format('H:i') }} WIB</strong></p>
                    <p>📍 Tempat: Kantor Posbakum PW ‘Aisyiyah DKI Jakarta<br>Jl. Tebet Timur Dalam No. 4, Jakarta Selatan</p>

                    @if(!empty($activePerkara->catatan_admin))
                        <hr class="my-2 border-blue-100">
                        <p class="font-medium">📝 Keterangan:</p>
                        <p class="italic">{{ $activePerkara->catatan_admin }}</p>
                    @endif
                </div>
            </div>
        @endif
    @endif

    {{-- 📅 JADWAL ADVOKAT: MUNCUL DARI TUNJUK ADVOKAT SAMPAI SELESAI --}}
    @if(!$isArsip && $sudahMasukTahapLanjut && !empty($activePerkara->jadwal_konsultasi))
        @php
            $waktu = \Carbon\Carbon::parse($activePerkara->jadwal_konsultasi);
        @endphp
        @if($waktu->isFuture())
            <div class="mx-8 my-6 p-5 bg-green-50 border border-green-200 rounded-xl shadow-sm">
                <h5 class="font-bold text-green-800 text-base mb-3 flex items-center gap-2">
                    <i class="fas fa-calendar-check text-lg"></i> Jadwal Pertemuan
                </h5>
                <div class="text-green-700 text-sm space-y-1.5">
                    <p>📅 Hari/Tanggal: <strong>{{ $waktu->translatedFormat('l, d F Y') }}</strong></p>
                    <p>⏰ Pukul: <strong>{{ $waktu->format('H:i') }} WIB</strong></p>
                    <p>📍 Tempat: Sesuai kesepakatan / Kantor Posbakum</p>

                    @if(!empty($activePerkara->legal_advice))
                        <hr class="my-2 border-green-100">
                        <p class="font-medium">📝 Keterangan Advokat:</p>
                        <p class="italic">{{ $activePerkara->legal_advice }}</p>
                    @endif
                </div>
            </div>
        @endif
    @endif
@endif

<div class="relative pl-12 md:pl-20 py-8">
    <div class="absolute left-6 md:left-10 top-0 h-full w-[2px] bg-slate-100 rounded-full"></div>

@foreach($riwayatTahapan as $index => $langkah)
    @php
        // $index sekarang selalu berada dalam jangkauan langkah yang sudah/sedang terjadi
        $sudah = ($index < count($riwayatTahapan) - 1);
        $aktif = ($index == count($riwayatTahapan) - 1);

        $isLangkahAdvokat = in_array($langkah, [
            'KONFIRMASI KASUS (TLP/OFFLINE)',
            'MEDIASI (NON-LITIGASI)',
            'PENDAFTARAN E-COURT (LITIGASI)',
            'PROSES PERSIDANGAN',
            'TERTUNDA',
            'KASUS SELESAI (ARSIP)'
        ]);
    @endphp

        <div class="relative mb-10 {{ $isLangkahAdvokat ? 'alur-advokat' : '' }}">
            <div class="absolute {{ $isLangkahAdvokat ? '-left-[42px] w-8 h-8' : '-left-[54px] md:-left-[70px] w-10 h-10' }}
                {{ $aktif ? 'bg-blue-600' : ($sudah ? 'bg-emerald-600' : 'bg-slate-200') }}
                rounded-xl flex items-center justify-center ring-2 ring-white shadow z-10">
                @if($sudah)
                    <i class="fas fa-check text-white text-xs"></i>
                @elseif($aktif)
                    <i class="fas fa-spinner fa-spin text-white text-xs"></i>
                @endif
            </div>

            <h5 class="{{ $isLangkahAdvokat ? 'font-semibold text-base' : 'font-black text-lg' }} text-slate-900 uppercase">
                {{ $langkah }}
            </h5>

            {{-- Tampilkan keterangan hanya untuk langkah yang sedang aktif --}}
            @if($aktif)
                @if($activePerkara->advokat_id && in_array($langkah, ['Proses', 'Tunjuk Advokat', 'Siap ditangani oleh Advokat']))
                    <div class="mt-3 p-2.5 bg-blue-50 border border-blue-100 rounded-xl inline-flex items-center gap-2">
                        <span class="text-xs font-bold text-blue-800">✅ Ditangani Advokat: {{ $activePerkara->advokat->name }}</span>
                    </div>
                @endif

                @if(!empty($catatanTampil))
                    <div class="mt-3 p-3 {{ $warnaLatar ?? 'bg-gray-50 border-gray-200' }} rounded-xl max-w-xl">
                        <p class="text-xs font-bold {{ $warnaTeks ?? 'text-gray-600' }} uppercase mb-1">
                            {{ $ikonCatatan ?? 'ℹ️' }} {{ $sumberCatatan ?? 'INFORMASI' }}
                        </p>
                        <p class="text-sm text-slate-700 italic">"{{ $catatanTampil }}"</p>
                    </div>
                @endif
            @endif
        </div>
    @endforeach
</div>
            </div>
        </div>

    @else
        {{-- ✅ Tampilan: Belum ada kasus ATAU Sudah diarsip --}}
        <div class="max-w-4xl mx-auto mb-16">
            <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.08)] border border-white/50 overflow-hidden">
                <div class="bg-white/60 px-10 py-8 border-b border-slate-100">
                    <h4 class="text-xl font-black text-slate-800 uppercase tracking-widest">
                        @if($isArsip)
                            Perkara Selesai Diarsipkan
                        @else
                            Belum Ada Perkara Aktif
                        @endif
                    </h4>
                </div>
                <div class="text-center py-10">
                    <div class="max-w-2xl mx-auto p-6 bg-green-50 rounded-xl border border-green-200">
                        <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
                        <p class="text-lg font-bold text-green-800 mb-2">
                            @if($isArsip)
                                Perkara telah selesai dan dipindahkan ke riwayat
                            @else
                                Silakan ajukan perkara baru
                            @endif
                        </p>
                        <p class="text-sm text-green-600">
                            Anda dapat mengajukan perkara baru kapan saja jika diperlukan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ✅ Bagian Riwayat Tetap Selalu Muncul --}}
    @if($riwayatPerkara->count() > 0)
        <div class="max-w-4xl mx-auto">
            <h3 class="text-sm font-black text-slate-500 uppercase tracking-widest mb-6 px-4 mt-12">Riwayat Perkara Terdahulu</h3>
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                {{-- ✅ Tambah kelas + data-label agar bisa jadi kartu rapi di HP --}}
<table class="riwayat-perkara w-full text-left">
    <thead class="bg-slate-50">
        <tr>
            <th class="px-8 py-4 text-xs font-black text-slate-400 uppercase">Kode Reg</th>
            <th class="px-8 py-4 text-xs font-black text-slate-400 uppercase">Status & Keterangan</th>
            <th class="px-8 py-4 text-xs font-black text-slate-400 uppercase">Aksi</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-100">
        @foreach($riwayatPerkara as $item)
            @php
                // ✅ PERBAIKAN UTAMA: Ambil dari kolom STATUS dulu, baru tahapan
                if ($item->status == 'Ditolak') {
                    $riwayatLangkah = 'Ditolak';
                } else {
                    $riwayatLangkah = !empty($item->status) ? $item->status : (!empty($item->tahapan) ? $item->tahapan : 'Tidak diketahui');
                }

                if (!empty($item->legal_advice)) {
                    $riwayatCatatan = $item->legal_advice;
                    $riwayatSumber = '📝 Advokat';
                } elseif (!empty($item->catatan_admin)) {
                    $riwayatCatatan = $item->catatan_admin;
                    $riwayatSumber = '📋 Admin';
                } else {
                    $riwayatCatatan = 'Tidak ada keterangan';
                    $riwayatSumber = '';
                }
            @endphp
            <tr>
                <td data-label="Kode Reg" class="px-8 py-4 font-mono font-bold text-slate-700">
                    {{ $item->reg_code }}
                </td>

                {{-- ✅ Status & Keterangan: Diubah ke rata tengah --}}
                <td data-label="Status & Keterangan" class="px-8 py-4">
                    <div class="flex flex-col items-center justify-center text-center">
                        <span class="inline-block px-3 py-1 mb-1 rounded-full text-[11px] font-black uppercase
                            {{ $riwayatLangkah == 'Ditolak' ? 'bg-red-100 text-red-700' :
                            ($riwayatLangkah == 'KASUS SELESAI (ARSIP)' || $riwayatLangkah == 'Selesai' ? 'bg-green-100 text-green-700' :
                            ($riwayatLangkah == 'Rujuk' || $riwayatLangkah == 'TERTUNDA' ? 'bg-yellow-100 text-yellow-700' :
                            'bg-gray-100 text-gray-700')) }}">
                            {{ $riwayatLangkah }}
                        </span>
                        <div class="text-[11px] text-slate-500 italic max-w-[200px] text-center">
                            {{ $riwayatSumber }}: "{{ Str::limit($riwayatCatatan, 40) }}"
                        </div>
                    </div>
                </td>

                {{-- ✅ Aksi: Tetap rata kanan, sesuai CSS --}}
                <td data-label="Aksi" class="aksi px-8 py-4">
                    <div class="flex flex-col items-end gap-2">
                        <a href="{{ route('client.perkara.show', $item->id) }}" class="text-emerald-600 font-black text-xs uppercase hover:underline">Lihat Detail</a>
                        @if($item->status == 'Rujuk')
                            <a href="{{ route('client.surat.rujukan', $item->id) }}" class="text-yellow-600 font-black text-xs uppercase hover:underline">Cetak Surat Rujukan</a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
            </div>
        </div>
    @endif

</section>
</div>
</x-app-layout>

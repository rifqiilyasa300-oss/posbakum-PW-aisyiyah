<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                    {{ __('Dashboard Admin') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Monitoring data klien, staff, dan proses layanan hukum.
                </p>
            </div>

            <div class="hidden md:flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                    System Active
                </span>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 py-20 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ALERT --}}
            @if(session('success'))
                <div class="mb-8 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl shadow-sm">
                    <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                    </svg>

                    <div>
                        <p class="font-bold text-sm">Berhasil</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- HERO --}}
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-8 md:p-10 shadow-2xl mb-10">
                <div class="absolute top-0 right-0 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                    <div class="max-w-2xl">
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-white/10 border border-white/10 text-white text-[11px] font-black uppercase tracking-[0.2em] mb-5">
                            Admin Control Center
                        </span>
                        <h1 class="text-3xl md:text-5xl font-black text-white leading-tight tracking-tight">
                            Selamat Datang,
                            <span class="text-emerald-400">Administrator</span>
                        </h1>
                        <p class="mt-4 text-slate-300 text-base leading-relaxed">
                            Kelola data klien, verifikasi berkas, monitor progres kasus,
                            dan pantau seluruh aktivitas layanan hukum secara real-time.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('admin.staff.index') }}"
                            class="inline-flex items-center px-6 py-3 rounded-2xl bg-white text-slate-800 font-bold text-sm hover:scale-[1.03] transition-all duration-300 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m10 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m10 0H7" />
                            </svg>
                            Kelola Staff
                        </a>
                        <a href="{{ route('admin.staff.create') }}"
                            class="inline-flex items-center px-6 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm hover:scale-[1.03] transition-all duration-300 shadow-lg shadow-emerald-500/30">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Staff
                        </a>
                    </div>
                </div>
            </div>

            {{-- STATISTIC --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
                <div class="group bg-white rounded-3xl border border-slate-200 p-6 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Total Klien</p>
                            <h3 class="mt-4 text-4xl font-black text-slate-800">{{ $totalKlien }}</h3>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m10 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m10 0H7" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="group bg-white rounded-3xl border border-slate-200 p-6 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Perlu Verifikasi</p>
                            <h3 class="mt-4 text-4xl font-black text-slate-800">{{ $perluVerifikasi }}</h3>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="group bg-white rounded-3xl border border-slate-200 p-6 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Kasus Diproses</p>
                            <h3 class="mt-4 text-4xl font-black text-slate-800">{{ $kasusDiproses }}</h3>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4V7" />
                            </svg>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.incomes.index') }}" class="group block bg-white rounded-3xl border border-slate-200 p-6 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Total Pemasukan</p>
                            <h3 class="mt-4 text-2xl font-black text-slate-800 leading-snug">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3s3 1.343 3 3-1.343 3-3 3m0-12c1.11 0 2.08.402 2.599 1M12 8V7m0 1v12m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                </a>
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
                                <td class="border border-slate-300 px-6 py-5 align-middle text-center"><span class="font-mono font-bold text-blue-600">{{ $client->reg_code }}</span></td>
                                <td class="border border-slate-300 px-6 py-5 align-middle text-center"><p class="font-bold text-slate-800">{{ $client->nama }}</p></td>
                                <td class="border border-slate-300 px-6 py-5 align-middle text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full {{ $client->kategori_layanan == 'PRODEO' ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700' }} border text-[11px] font-black uppercase tracking-widest">
                                        {{ $client->kategori_layanan }}
                                    </span>
                                </td>
                                <td class="border border-slate-300 px-6 py-5 align-middle text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full border text-[11px] font-black uppercase tracking-widest {{ $client->status == 'Ditolak' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-amber-100 text-amber-700 border-amber-200' }}">
                                        {{ $client->status }}
                                    </span>
                                </td>
                                <td class="border border-slate-300 px-6 py-5 align-middle text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        @if($client->ktp_path)<a href="{{ asset('storage/ktp/' . basename($client->ktp_path)) }}" target="_blank" class="text-slate-600 font-bold text-[10px] hover:underline">📂 KTP</a>@endif
                                        @if($client->sktm_path)<a href="{{ asset('storage/sktm/' . basename($client->sktm_path)) }}" target="_blank" class="text-slate-600 font-bold text-[10px] hover:underline">📂 SKTM</a>@endif
                                        @if($client->bukti_bayar_path)<a href="{{ asset('storage/pembayaran/' . basename($client->bukti_bayar_path)) }}" target="_blank" class="text-blue-600 font-bold text-[10px] hover:underline">📂 Bukti Bayar</a>@endif
                                    </div>
                                </td>
                                <td class="border border-slate-300 px-6 py-5 align-middle text-center relative">
                                    <div class="relative inline-block" x-data="{ open: false }" @click.outside="open = false">
                                        <button @click="open = !open" class="w-24 px-4 py-2 bg-slate-800 text-white text-[10px] font-bold rounded-xl shadow-md hover:bg-slate-900 transition">AKSI ▾</button>
                                       <div x-show="open" x-cloak class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-2xl z-[9999] p-2 space-y-1 text-left">

    {{-- 1. Tombol Verifikasi/ACC --}}
    @if($client->status == 'Menunggu Verifikasi')
        <form action="{{ route('admin.clients.approve', $client->id) }}" method="POST">
            @csrf @method('PATCH')
            <button type="submit" onclick="return confirm('Yakin ingin memverifikasi/ACC klien ini?')"
                class="w-full px-3 py-2 text-[11px] font-bold text-emerald-700 hover:bg-emerald-50 rounded-lg text-left flex items-center">
                ✅ Setujui (ACC)
            </button>
        </form>
    @endif

    {{-- 2. Tombol Revisi (Info Kurang) --}}
    <button type="button" onclick="openModalBayarKurang('{{ $client->id }}')"
        class="w-full px-3 py-2 text-[11px] font-bold text-amber-700 hover:bg-amber-50 rounded-lg text-left flex items-center">
        ⚠️ Perlu Revisi
    </button>

    {{-- 3. Tombol Tolak --}}
    <button type="button" onclick="openModalTolak('{{ $client->id }}')"
        class="w-full px-3 py-2 text-[11px] font-bold text-red-700 hover:bg-red-50 rounded-lg text-left flex items-center">
        ❌ Tolak
    </button>

    <hr class="border-slate-100 my-1">

    {{-- 4. Tombol Detail --}}
    <a href="{{ route('admin.clients.show', $client->id) }}"
        class="block px-3 py-2 text-[11px] font-bold text-slate-700 hover:bg-slate-100 rounded-lg flex items-center">
        👁️ Lihat Detail
    </a>
</div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="border border-slate-300 px-6 py-20 text-center text-slate-400">Tidak ada pendaftaran baru.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TOLAK --}}
<div id="modalTolak" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8">
        <h3 class="text-xl font-black text-slate-900 mb-4">Alasan Penolakan</h3>
        <form id="formTolak" action="" method="POST">
            @csrf @method('PATCH')
            <textarea name="catatan_admin" class="w-full border-2 border-slate-100 rounded-2xl p-4 mb-4" rows="4" placeholder="Sebutkan alasan penolakan..." required></textarea>

            {{-- Tombol Batal & Kirim --}}
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modalTolak').classList.add('hidden')"
                        class="flex-1 bg-slate-100 text-slate-600 py-3 rounded-xl font-bold hover:bg-slate-200 transition">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-red-600 text-white py-3 rounded-xl font-bold hover:bg-red-700 transition">
                    Kirim Penolakan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL BAYAR KURANG --}}
<div id="modalBayarKurang" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8">
        <h3 class="text-xl font-black text-slate-900 mb-4">Catatan Kekurangan</h3>
        <form id="formBayarKurang" action="" method="POST">
            @csrf @method('PATCH')
            <textarea name="catatan_admin" class="w-full border-2 border-slate-100 rounded-2xl p-4 mb-4" rows="4" placeholder="Sebutkan kekurangan dokumen..." required></textarea>

            {{-- Tombol Batal & Kirim --}}
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modalBayarKurang').classList.add('hidden')"
                        class="flex-1 bg-slate-100 text-slate-600 py-3 rounded-xl font-bold hover:bg-slate-200 transition">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-amber-600 text-white py-3 rounded-xl font-bold hover:bg-amber-700 transition">
                    Kirim Catatan
                </button>
            </div>
        </form>
    </div>
</div>

    <script>
    // Fungsi untuk Modal Tolak
    function openModalTolak(id) {
        const modal = document.getElementById('modalTolak');
        const form = document.getElementById('formTolak');

        // Pastikan route ini sesuai dengan route di web.php Anda
        form.action = `/admin/clients/${id}/reject`;

        modal.classList.remove('hidden');
    }

    // Fungsi untuk Modal Bayar Kurang
function openModalBayarKurang(id) {
    const form = document.getElementById('formBayarKurang');

    // Gunakan fungsi route Laravel untuk menghasilkan URL yang valid
    // Kita gunakan placeholder :id yang akan diganti oleh JS
    let url = "{{ route('admin.clients.markPayment', ':id') }}";
    form.action = url.replace(':id', id);

    // Debugging: Lihat di console apa yang dihasilkan
    console.log("Mengirim ke:", form.action);

    document.getElementById('modalBayarKurang').classList.remove('hidden');
}
</script>
</x-app-layout>

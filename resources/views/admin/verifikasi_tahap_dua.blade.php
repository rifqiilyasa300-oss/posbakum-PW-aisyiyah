<x-app-layout>
    {{-- Pembungkus dengan Background Hijau Lembut --}}
    <div class="min-h-screen bg-[#f0f7f4]" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDQwIDQwIj48ZyBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNCI+PHBhdGggZD0iTTAgMGg0MHY0MEgwVjB6bTIwIDIwaDIwdjIwSDIwVjIwek0wIDIwaDIwdjIwSDBWMjB6TTIwIDBoMjB2MjBIMjBWMHoiLz48L2c+PC9nPjwvc3ZnPg==');">

        <x-slot name="header">
            <h2 class="font-black text-xl text-slate-800 leading-tight uppercase tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ __('Verifikasi Tahap Dua') }}
            </h2>
        </x-slot>

        <div class="py-12 px-4">
            <div class="max-w-3xl mx-auto">

                {{-- Kartu Informasi Klien --}}
                <div class="bg-white rounded-3xl shadow-xl p-8 mb-8 border border-slate-100/80 flex flex-col items-center text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">
                        Data Klien yang Diverifikasi
                    </p>
                    <h3 class="text-3xl font-black text-slate-800 mb-5">
                        {{ $client->nama }}
                    </h3>

                    <div class="flex flex-wrap gap-4 justify-center">
                        <span class="bg-emerald-50 text-emerald-700 px-5 py-2 rounded-full text-sm font-bold border border-emerald-100">
                            🆔 {{ $client->reg_code }}
                        </span>
                        <span class="bg-blue-50 text-blue-700 px-5 py-2 rounded-full text-sm font-bold uppercase border border-blue-100">
                            📂 {{ $client->kategori_layanan }}
                        </span>
                        <span class="bg-amber-50 text-amber-700 px-5 py-2 rounded-full text-sm font-bold border border-amber-100">
                            📅 Konsultasi: {{ $client->jadwal_konsultasi ? \Carbon\Carbon::parse($client->jadwal_konsultasi)->format('d M Y H:i') : 'Belum diatur' }}
                        </span>
                    </div>
                </div>

                {{-- Form Verifikasi --}}
                <div class="bg-white rounded-3xl shadow-xl p-8 md:p-10 border border-slate-100/80">
                    <form action="{{ route('admin.verifikasi.dua', $client->id) }}" method="POST">
                        @csrf

                        {{-- Judul Bagian --}}
                        <div class="mb-8 pb-4 border-b border-slate-100">
                            <h4 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#0a6640]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Keputusan Hasil Konsultasi
                            </h4>
                            <p class="text-sm text-slate-500 mt-1">Tentukan langkah selanjutnya untuk perkara ini</p>
                        </div>

                        {{-- Pilihan Keputusan --}}
                        <div class="mb-6">
                            <label class="block font-bold text-slate-700 mb-3">Keputusan Akhir:</label>
                            <select name="pilihan" id="pilihanKeputusan" class="w-full rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:border-[#0a6640] focus:ring-2 focus:ring-[#0a6640]/20 px-4 py-3.5 text-slate-700 transition-all" required>
                                <option value="" disabled selected>-- Pilih salah satu --</option>
                                <option value="Proses">✅ Lanjut (Proses Pendampingan)</option>
                                <option value="Rujuk">🔁 Rujuk ke Lembaga Lain</option>
                                <option value="Tidak Lanjut">❌ Tidak Lanjut (Tidak Memenuhi Syarat)</option>
                                <option value="Selesai">🏁 Selesai (Kasus Tuntas)</option>
                            </select>
                        </div>

                        {{-- Form Khusus Rujuk (Muncul Otomatis) --}}
                        <div id="formRujuk" class="hidden mb-6 p-6 bg-blue-50 rounded-xl border border-blue-200">
                            <h5 class="font-bold text-blue-800 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                Data Tujuan Rujukan
                            </h5>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lembaga / Instansi</label>
                                <input type="text" name="tujuan_rujuk" id="tujuanRujuk" class="w-full rounded-lg border border-slate-300 px-4 py-3" placeholder="Contoh: Pengadilan Negeri Jakarta Barat / Dinas Sosial">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Alamat Lengkap Instansi</label>
                                <textarea name="alamat_tujuan" id="alamatTujuan" rows="3" class="w-full rounded-lg border border-slate-300 px-4 py-3" placeholder="Tulis alamat lengkap, kecamatan, kota dan kode pos"></textarea>
                            </div>
                        </div>

                        {{-- Catatan Umum --}}
                        <div class="mb-8">
                            <label class="block font-semibold text-slate-700 mb-3">Catatan & Alasan Keputusan:</label>
                            <textarea name="catatan_admin" rows="5" class="w-full rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:border-[#0a6640] focus:ring-2 focus:ring-[#0a6640]/20 px-4 py-4 text-slate-700 leading-relaxed transition-all" required placeholder="Tuliskan alasan atau keterangan secara jelas..."></textarea>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex flex-col sm:flex-row justify-end gap-4 pt-5 border-t border-slate-100">
                            <a href="{{ route('admin.clients.show', $client->id) }}" class="px-6 py-3 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition text-center">
                                Batal & Kembali
                            </a>
                            <button type="submit" class="bg-[#0a6640] hover:bg-[#085233] text-white px-8 py-3 rounded-xl font-bold uppercase tracking-wide transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Simpan Keputusan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- Script langsung ditaruh di sini tanpa @push --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pilihan = document.getElementById('pilihanKeputusan');
            const formRujuk = document.getElementById('formRujuk');
            const inputTujuan = document.getElementById('tujuanRujuk');
            const inputAlamat = document.getElementById('alamatTujuan');

            pilihan.addEventListener('change', function() {
                if (this.value === 'Rujuk') {
                    formRujuk.classList.remove('hidden');
                    inputTujuan.required = true;
                    inputAlamat.required = true;
                } else {
                    formRujuk.classList.add('hidden');
                    inputTujuan.required = false;
                    inputAlamat.required = false;
                    inputTujuan.value = '';
                    inputAlamat.value = '';
                }
            });
        });
    </script>
</x-app-layout>

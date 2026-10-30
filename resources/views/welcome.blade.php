<x-app-layout>
    <!-- Menambahkan FontAwesome jika belum ada di layout utama -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        html { scroll-behavior: smooth; }
        section { scroll-margin-top: 80px; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #059669; }

        .hero-section {
            position: relative;
            background-image: url("{{ asset('images/posbakum.jpg') }}");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .hero-overlay {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7));
        }

        .bg-dashed {
            background-image: radial-gradient(#d1d5db 2px, transparent 2px);
            background-size: 24px 24px;
        }
    </style>

    <div class="bg-gray-50 text-gray-800 antialiased">

        {{-- HERO SECTION --}}
        <section class="hero-section min-h-[90vh] flex items-center">
            <div class="hero-overlay absolute inset-0"></div>
            <div class="container mx-auto px-4 relative z-10 pt-10">
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
                    <div class="flex flex-wrap justify-center gap-5">
                    {{-- CARI BARIS 46, GANTI CLASS INI: --}}
                    <a href="{{ route('client.register') }}"
                        class="inline-flex items-center px-8 py-4 bg-white text-green-900 font-bold rounded-xl shadow-2xl hover:bg-gray-100 hover:-translate-y-1 transition-all duration-300 no-underline">
                        <i class="fas fa-edit mr-2"></i>
                        Ajukan Bantuan Sekarang
                    </a>
                        <a href="https://wa.me/628161159564" target="_blank"
                           class="inline-flex items-center px-8 py-4 bg-white/10 text-white font-bold rounded-xl border border-white/20 backdrop-blur-md hover:bg-white/20 transition-all no-underline group">
                            <i class="fab fa-whatsapp text-xl mr-2 text-green-400"></i>
                            CALL CENTER
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- SECTION 1: PROFIL & VISI MISI (Informasi Kelembagaan) --}}
        <section id="profil" class="py-24 bg-white">
            <div class="container mx-auto px-4 max-w-6xl">
                {{-- KOLOM PROFIL (Lebar penuh/center) --}}
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-4">
                        <span class="text-emerald-600 font-bold uppercase tracking-widest text-sm">Profil Lembaga</span>
                        <h2 class="text-4xl font-black text-slate-900 mt-2 uppercase leading-tight">Pos Bantuan Hukum (Posbakum) PW ‘Aisyiyah DKI Jakarta</h2>
                    </div>

                    <div class="space-y-6 text-slate-600 text-lg leading-relaxed text-justify bg-slate-50 p-10 rounded-[3rem] border border-slate-100 shadow-sm">
                        <p>Pos Bantuan Hukum (Posbakum) Pimpinan Wilayah ‘Aisyiyah DKI Jakarta merupakan unit layanan bantuan hukum yang berada di bawah naungan Majelis Hukum dan Hak Asasi Manusia (MHH) PW ‘Aisyiyah DKI Jakarta. Kami hadir sebagai wujud komitmen ‘Aisyiyah dalam memberikan akses keadilan bagi masyarakat, khususnya perempuan, anak, dan kelompok rentan, melalui layanan hukum yang profesional, mudah diakses, serta berkeadilan.</p>

                        <p>Secara kelembagaan, kami ditetapkan melalui Surat Keputusan Pimpinan Wilayah ‘Aisyiyah DKI Jakarta Nomor 098/SK-PWA/I/2024 tanggal 5 Januari 2024. Struktur kepengurusan kami terdiri dari unsur advokat dan paralegal berpengalaman, didukung oleh divisi litigasi, non-litigasi, perundang-undangan, hak asasi manusia, pelayanan hukum, hingga teknologi informasi.</p>

                        <p>Posbakum PW ‘Aisyiyah DKI Jakarta secara resmi diluncurkan pada 25 Juni 2024 di Gedung Dakwah ‘Aisyiyah, Tebet, Jakarta Selatan. Hal ini menjadi langkah strategis kami dalam memperluas layanan bantuan hukum berbasis organisasi perempuan yang inklusif dan berorientasi pada pemberdayaan masyarakat.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- SECTION 2: STRUKTUR ORGANISASI (Informasi Kelembagaan) --}}
        <section class="py-24 bg-slate-50">
            <div class="container mx-auto px-4 max-w-6xl text-center">
                <h2 class="text-3xl font-black text-slate-900 mb-12 uppercase">Struktur Kepengurusan Posbakum</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

                    {{-- Ketua --}}
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                        <div class="text-emerald-600 font-black text-xl mb-2 uppercase">Ketua</div>
                        <p class="text-slate-800 text-sm uppercase font-bold leading-tight">Sitti Fatimah</p>
                    </div>

                    {{-- Sekretaris --}}
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                        <div class="text-emerald-600 font-black text-xl mb-2 uppercase">Sekretaris</div>
                        <p class="text-slate-800 text-sm uppercase font-bold leading-tight">Dini Wahdiyati, S.Sos, M.I.Kom</p>
                    </div>

                    {{-- Bendahara --}}
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                        <div class="text-emerald-600 font-black text-xl mb-2 uppercase">Bendahara</div>
                        <p class="text-slate-800 text-sm uppercase font-bold leading-tight">Istaufina Daya Putri</p>
                    </div>

                    {{-- Divisi Pelayanan Hukum --}}
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                        <div class="text-emerald-600 font-black text-xl mb-2 uppercase">Divisi Pelayanan</div>
                        <p class="text-slate-800 text-sm uppercase font-bold leading-tight">Mardhatilla Khairina</p>
                    </div>

                </div>
            </div>
        </section>

        {{-- SECTION 3: LAYANAN & KUALIFIKASI (Layanan Hukum & Interaktif) --}}
        <section id="layanan" class="py-24 bg-white" x-data="{ openModal: false, modalTitle: '', modalDetail: '', modalIcon: '', modalColor: '' }">
            <div class="container mx-auto px-4 max-w-6xl">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-black text-slate-900 uppercase">Layanan Hukum Terpadu</h2>
                    <div class="w-20 h-1.5 bg-yellow-400 mx-auto mt-4 rounded-full"></div>
                </div>

                <div class="grid md:grid-cols-4 gap-6">
                    @php
                        $services = [
                            ['icon' => 'gavel', 'title' => 'LITIGASI', 'color' => 'text-green-600', 'detail' => 'Pendampingan hukum di dalam persidangan (Pidana, Perdata, TUN) untuk memberikan akses keadilan bagi masyarakat tidak mampu secara profesional.'],
                            ['icon' => 'handshake', 'title' => 'NON-LITIGASI', 'color' => 'text-blue-600', 'detail' => 'Penyelesaian sengketa melalui Mediasi, Negosiasi, dan Konsultasi Hukum untuk mencapai perdamaian tanpa harus melalui proses pengadilan.'],
                            ['icon' => 'book-reader', 'title' => 'EDUKASI', 'color' => 'text-emerald-700', 'detail' => 'Penyelenggaraan program edukasi dan penyuluhan hukum untuk meningkatkan kesadaran serta pemahaman hukum bagi masyarakat luas.'],
                            ['icon' => 'comments', 'title' => 'KONSULTASI', 'color' => 'text-purple-600', 'detail' => 'Layanan konsultasi hukum gratis. Kami hadir melayani Anda setiap hari Senin - Sabtu, pukul 10.00 - 16.00 WIB.'],
                        ];
                    @endphp

                    @foreach($services as $s)
                    <div @click="openModal=true; modalTitle='{{$s['title']}}'; modalIcon='{{$s['icon']}}'; modalDetail='{{$s['detail']}}'; modalColor='{{$s['color']}}'"
                        class="p-8 bg-slate-50 rounded-[2.5rem] text-center border border-transparent hover:border-emerald-500 hover:bg-white hover:shadow-2xl transition-all cursor-pointer group">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm group-hover:bg-emerald-600 group-hover:text-white transition-all">
                            <i class="fas fa-{{$s['icon']}} text-2xl {{$s['color']}} group-hover:text-white"></i>
                        </div>
                        <h4 class="font-black text-lg mb-2 uppercase">{{$s['title']}}</h4>
                        <span class="text-xs font-bold text-emerald-600 uppercase">Lihat Detail →</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- MODAL INTERAKTIF --}}
            <div x-show="openModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display:none">
                <div class="bg-white rounded-[3rem] p-10 max-w-lg w-full shadow-2xl relative border border-slate-100">
                    <div class="text-center">
                        <i :class="'fas fa-' + modalIcon + ' text-5xl mb-6 ' + modalColor"></i>
                        <h3 class="text-2xl font-black mb-4 uppercase" x-text="modalTitle"></h3>
                        <p class="text-slate-600 leading-relaxed text-lg mb-8" x-text="modalDetail"></p>

                        {{-- Panduan untuk KONSULTASI --}}
                        <template x-if="modalTitle === 'KONSULTASI'">
                            <div class="bg-emerald-50 p-4 rounded-2xl text-left mb-6 border border-emerald-100">
                                <p class="text-xs font-black text-emerald-800 uppercase mb-2">Informasi Penting:</p>
                                <p class="text-sm text-emerald-700 italic">"Layanan kami terbuka untuk masyarakat umum yang membutuhkan akses bantuan hukum secara gratis."</p>
                            </div>
                        </template>

                        {{-- Panduan untuk EDUKASI (Baru ditambahkan) --}}
                        <template x-if="modalTitle === 'EDUKASI'">
                            <div class="bg-blue-50 p-4 rounded-2xl text-left mb-6 border border-blue-100">
                                <p class="text-xs font-black text-blue-800 uppercase mb-2">Informasi Edukasi & Penyuluhan:</p>
                                <p class="text-sm text-blue-700">
                                    Untuk mengetahui informasi terkait edukasi dan penyuluhan hukum yang sedang berlangsung serta informasi kolaborasi,
                                    silahkan menghubungi <strong>call center</strong> kami melalui WhatsApp atau pantau informasi terbaru di Instagram
                                    <a href="https://www.instagram.com/posbakum_aisyiyah_jakarta/" target="_blank" class="font-bold underline">@posbakum_aisyiyah_jakarta</a>.
                                </p>
                            </div>
                        </template>

                        <button @click="openModal=false" class="w-full py-4 bg-slate-900 text-white font-black rounded-2xl uppercase tracking-widest text-sm shadow-lg">Tutup Informasi</button>
                    </div>
                </div>
            </div>
        </section>

        {{-- SECTION 4: TRANSPARANSI & JARINGAN (Klasifikasi & Jangkauan) --}}
        <section class="py-24 bg-slate-900 text-white">
            <div class="container mx-auto px-4 max-w-4xl"> <div class="text-center mb-16">
                    <h2 class="text-4xl font-black uppercase">Jaringan Layanan Jakarta</h2>
                    <p class="text-slate-400 mt-4 uppercase tracking-widest text-sm">Transparansi Layanan POSBAKUM 'Aisyiyah DKI Jakarta</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="text-center p-10 bg-white/5 rounded-[3rem] border border-white/10">
                        <div class="text-5xl font-black text-emerald-400 mb-2">5</div>
                        <div class="text-sm font-bold uppercase tracking-widest">Posbakum Wilayah</div>
                        <p class="text-xs text-slate-500 mt-2">Tersebar di 5 Kota Administrasi DKI Jakarta</p>
                    </div>

                    <div class="text-center p-10 bg-white/5 rounded-[3rem] border border-white/10">
                        <div class="text-5xl font-black text-blue-400 mb-2">100%</div>
                        <div class="text-sm font-bold uppercase tracking-widest">Transparansi</div>
                        <p class="text-xs text-slate-500 mt-2">Laporan Keuangan & Kinerja Akuntabel</p>
                    </div>
                </div>
            </div>
        </section>

                {{-- SECTION 5: LOKASI KANTOR (Kontak & Lokasi - KLIKABLE) --}}
        <section id="lokasi" class="py-24 bg-white">
            <div class="container mx-auto px-4 max-w-6xl">
                <div class="bg-slate-50 p-12 rounded-[4rem] flex flex-col md:flex-row gap-12 items-center border border-slate-100 shadow-sm">
                    <div class="md:w-1/2">
                        <h2 class="text-3xl font-black text-slate-900 mb-6 uppercase">Kantor Layanan Pusat</h2>
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 shrink-0">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <p class="text-slate-600 font-bold uppercase tracking-tight leading-tight">
                                    Pusat Dakwah ‘Aisyiyah DKI Jakarta,<br>Jl. Tebet Timur Dalam No. 4, Jakarta Selatan.
                                </p>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 shrink-0">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <p class="text-slate-600 font-bold uppercase tracking-tight">628161159564</p>
                            </div>
                        </div>
                    </div>

                    <a href="https://www.google.com/maps/place/POSBAKUM+PW+Aisyiyah+Jakarta+(Tebet)/@-6.2321419,106.8488757,17z/data=!3m1!4b1!4m6!3m5!1s0x2e69f361df2d4a8f:0x14ddc8d7c9bff4a4!8m2!3d-6.2321473!4d106.8534891!16s%2Fg%2F11yx0kk5q_?entry=ttu&g_ep=EgoyMDI2MDUyMC4wIKXMDSoASAFQAw%3D%3D"
                    target="_blank"
                    class="md:w-1/2 w-full h-[400px] rounded-[3rem] overflow-hidden border-8 border-white shadow-xl relative block group cursor-pointer">

                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.24276722806!2d106.8509011!3d-6.2321382!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3796d11f67b%3A0xc00f146526e992b8!2sPOSBAKUM%20PW%20Aisyiyah%20Jakarta%20(Tebet)!5e0!3m2!1sid!2sid!4v1716100000000!5m2!1sid!2sid"
                            class="w-full h-full border-0 pointer-events-none"
                            allowfullscreen=""
                            loading="lazy">
                        </iframe>

                        <div class="absolute inset-0 bg-black/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <div class="bg-emerald-600 text-white px-6 py-3 rounded-full font-black uppercase text-sm shadow-xl">
                                <i class="fas fa-external-link-alt mr-2"></i> Buka di Google Maps
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>

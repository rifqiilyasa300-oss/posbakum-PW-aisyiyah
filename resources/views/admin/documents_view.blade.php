<x-app-layout>
    {{-- Library Fancybox --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    {{-- FontAwesome untuk ikon dokumen --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        [x-cloak] { display: none !important; }
        @media print { .no-print { display: none !important; } }

        .drive-item {
            transition: all 0.25s ease;
            border: 1px solid #f1f5f9;
            background: #ffffff;
        }
        .drive-item:hover {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px -3px rgba(0, 0, 0, 0.08);
        }
        .file-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }
    </style>

    <div class="py-24 bg-slate-50 min-h-screen" x-data="{ openUpload: false }">
        <div class="max-w-7xl mx-auto px-4 md:px-8">

            {{-- Bagian Header --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 no-print bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 gap-6">
                <div class="flex items-center gap-5">
                    <div class="p-4 bg-green-50 rounded-2xl">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-slate-900 text-2xl font-black uppercase tracking-tight">{{ $client->nama }}</h1>
                        <p class="text-slate-500 text-[11px] font-bold tracking-widest uppercase mt-1">Galeri Dokumen Perkara • No. Registrasi: {{ $client->reg_code }}</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <a href="{{ auth()->user()->role == 'admin' ? route('admin.clients.show', $client->id) : route('advokat.clients.show', $client->id) }}"
                       class="bg-white hover:bg-slate-50 text-slate-600 text-[11px] font-black uppercase tracking-widest px-6 py-3 rounded-xl transition border border-slate-200 shadow-sm">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                    </a>
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('advokat'))
                        <button @click="openUpload = true" class="bg-green-600 hover:bg-green-700 text-white text-[11px] font-black uppercase tracking-widest px-6 py-3 rounded-xl transition shadow-lg shadow-green-100 flex items-center gap-2">
                            <i class="fa-solid fa-upload"></i>
                            Unggah Dokumen
                        </button>
                    @endif
                </div>
            </div>

            {{-- Kontainer Utama Dokumen --}}
            <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-sm border border-slate-100">
                <h2 class="text-slate-500 text-xs font-black uppercase tracking-[0.3em] mb-8">Daftar Berkas & Dokumen</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                    {{-- KTP --}}
                    @if($client->ktp_path)
                    <div class="drive-item p-5 rounded-[1.5rem]">
                        <div class="aspect-video bg-slate-50 rounded-xl mb-4 overflow-hidden relative group border border-slate-100">
                            @if(Str::endsWith(strtolower($client->ktp_path), '.pdf'))
                                <div class="w-full h-full flex items-center justify-center bg-red-50 text-red-500">
                                    <i class="fa-solid fa-file-pdf text-4xl"></i>
                                </div>
                            @else
                                <img src="{{ asset('storage/ktp/' . $client->ktp_path) }}" class="w-full h-full object-cover">
                            @endif
                            <a href="{{ asset('storage/ktp/' . $client->ktp_path) }}"
                               class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-bold uppercase backdrop-blur-sm rounded-xl"
                               {{ Str::endsWith(strtolower($client->ktp_path), '.pdf') ? 'target="_blank"' : 'data-fancybox="galeri_ktp"' }}>
                                Buka Berkas
                            </a>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="file-icon bg-blue-100 text-blue-600">
                                <i class="fa-solid fa-id-card text-xl"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-sm font-bold text-slate-800 truncate">KTP</p>
                                <p class="text-[10px] text-slate-500 font-medium uppercase mt-0.5">Identitas Resmi</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- SKTM --}}
                    @if($client->sktm_path)
                    <div class="drive-item p-5 rounded-[1.5rem]">
                        <div class="aspect-video bg-slate-50 rounded-xl mb-4 overflow-hidden relative group border border-slate-100">
                            @if(Str::endsWith(strtolower($client->sktm_path), '.pdf'))
                                <div class="w-full h-full flex items-center justify-center bg-red-50 text-red-500">
                                    <i class="fa-solid fa-file-pdf text-4xl"></i>
                                </div>
                            @else
                                <img src="{{ asset('storage/sktm/' . $client->sktm_path) }}" class="w-full h-full object-cover">
                            @endif
                            <a href="{{ asset('storage/sktm/' . $client->sktm_path) }}"
                               class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-bold uppercase backdrop-blur-sm rounded-xl"
                               {{ Str::endsWith(strtolower($client->sktm_path), '.pdf') ? 'target="_blank"' : 'data-fancybox="galeri_sktm"' }}>
                                Buka Berkas
                            </a>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="file-icon bg-green-100 text-green-600">
                                <i class="fa-solid fa-file-lines text-xl"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-sm font-bold text-slate-800 truncate">SKTM</p>
                                <p class="text-[10px] text-slate-500 font-medium uppercase mt-0.5">Surat Keterangan Tidak Mampu</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Bukti Pembayaran --}}
                    @if($client->bukti_bayar_path)
                    <div class="drive-item p-5 rounded-[1.5rem]">
                        <div class="aspect-video bg-slate-50 rounded-xl mb-4 overflow-hidden relative group border border-slate-100">
                            <img src="{{ asset('storage/pembayaran/' . $client->bukti_bayar_path) }}" class="w-full h-full object-cover">
                            <a href="{{ asset('storage/pembayaran/' . $client->bukti_bayar_path) }}"
                               class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-bold uppercase backdrop-blur-sm rounded-xl"
                               data-fancybox="galeri_bukti">
                                Buka Berkas
                            </a>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="file-icon bg-yellow-100 text-yellow-600">
                                <i class="fa-solid fa-receipt text-xl"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-sm font-bold text-slate-800 truncate">Bukti Pembayaran</p>
                                <p class="text-[10px] text-slate-500 font-medium uppercase mt-0.5">Dokumen Administrasi</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Dokumen Tambahan / Proses --}}
                    @foreach($client->additional_documents as $doc)
                    @php
                        $ext = strtolower(pathinfo($doc->file_path, PATHINFO_EXTENSION));
                        $isWord = in_array($ext, ['doc', 'docx']);
                        $isPdf = ($ext == 'pdf');
                        $fileUrl = route('clients.viewFile', ['id' => $client->id, 'type' => 'tambahan']) . '?doc_id=' . $doc->id;
                    @endphp
                    <div class="drive-item p-5 rounded-[1.5rem]">
                        <div class="aspect-video bg-slate-50 rounded-xl mb-4 overflow-hidden relative group border border-slate-100">
                            @if($isWord)
                                <div class="w-full h-full flex flex-col items-center justify-center bg-blue-50 text-blue-600">
                                    <i class="fa-solid fa-file-word text-5xl"></i>
                                </div>
                            @elseif($isPdf)
                                <div class="w-full h-full flex flex-col items-center justify-center bg-red-50 text-red-600">
                                    <i class="fa-solid fa-file-pdf text-5xl"></i>
                                </div>
                            @else
                                <img src="{{ $fileUrl }}" class="w-full h-full object-cover">
                            @endif
                            <a href="{{ $fileUrl }}"
                               class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-bold uppercase backdrop-blur-sm rounded-xl"
                               target="_blank"
                               @if(!$isWord && !$isPdf) data-fancybox="galeri_dokumen" @endif>
                               Buka Berkas
                            </a>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="file-icon bg-slate-100 text-slate-600">
                                <i class="fa-solid {{ $isWord ? 'fa-file-word' : ($isPdf ? 'fa-file-pdf' : 'fa-file-image') }} text-xl"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-sm font-bold text-slate-800 truncate" title="{{ $doc->file_name }}">{{ $doc->file_name }}</p>
                                <p class="text-[10px] text-slate-500 font-medium uppercase mt-0.5">Dokumen Proses / Tambahan</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Tampilan Jika Kosong --}}
                @if(!$client->ktp_path && !$client->sktm_path && !$client->bukti_bayar_path && $client->additional_documents->isEmpty())
                <div class="col-span-full py-20 text-center border-2 border-dashed border-slate-200 rounded-[2rem] bg-slate-50 mt-8">
                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" class="w-20 h-20 mx-auto opacity-20 mb-4">
                    <p class="text-slate-500 font-black uppercase tracking-widest text-sm">Belum ada dokumen yang diunggah</p>
                    <p class="text-slate-400 text-xs mt-1">Silakan klik tombol "Unggah Dokumen" untuk menambahkan berkas baru</p>
                </div>
                @endif
            </div>
        </div>

        {{-- MODAL UPLOAD --}}
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('advokat'))
        <div x-show="openUpload"
             x-data="{
                selectedFiles: [],
                isUploading: false,
                handleFiles(event) {
                    this.selectedFiles = Array.from(event.target.files).map(file => ({
                        name: file.name,
                        size: (file.size / 1024).toFixed(1) + ' KB'
                    }));
                }
             }"
             x-transition.opacity class="fixed inset-0 z-[9999] flex items-center justify-center p-4" x-cloak>
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openUpload = false"></div>
            <div class="bg-white w-full max-w-lg rounded-[2rem] p-8 shadow-2xl relative z-10 border border-slate-100 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-slate-900 font-black uppercase tracking-tight text-lg">Unggah Dokumen Baru</h3>
                    <button @click="openUpload = false" class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
                </div>

                <form action="{{ auth()->user()->hasRole('admin') ? route('admin.clients.uploadAdditional', $client->id) : route('advokat.clients.uploadAdditional', $client->id) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      @submit="isUploading = true">
                    @csrf

                    <div class="mb-6">
                        <label class="block bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl p-8 text-center cursor-pointer hover:border-green-400 transition hover:bg-green-50">
                            <input type="file" name="additional_docs[]" multiple required @change="handleFiles" class="hidden">
                            <i class="fa-solid fa-cloud-arrow-up text-4xl text-green-500 mb-3"></i>
                            <span class="text-xs font-black uppercase tracking-widest text-slate-600">Klik atau seret berkas ke sini</span>
                            <p class="text-[10px] text-slate-400 mt-1">Mendukung format: PDF, DOC, DOCX, JPG, PNG</p>
                        </label>
                    </div>

                    <div x-show="selectedFiles.length > 0" class="mb-6 space-y-3 max-h-40 overflow-y-auto pr-2">
                        <p class="text-xs font-bold text-slate-600 uppercase">Daftar berkas dipilih:</p>
                        <template x-for="(file, index) in selectedFiles" :key="index">
                            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg border border-slate-100">
                                <i class="fa-solid fa-file text-slate-400"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-700 truncate" x-text="file.name"></p>
                                    <p class="text-[9px] text-slate-400" x-text="file.size"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <button type="submit"
                            :disabled="isUploading"
                            class="w-full bg-green-600 hover:bg-green-700 disabled:bg-slate-300 text-white text-xs font-black uppercase tracking-[0.2em] py-3 rounded-lg shadow-md transition">
                        <span x-show="!isUploading">Simpan & Unggah</span>
                        <span x-show="isUploading">Memproses...</span>
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        Fancybox.bind("[data-fancybox]", {
            Toolbar: true,
            Thumbs: false,
            closeButton: "top",
            animated: true,
            showClass: "f-fadeIn"
        });
    </script>
</x-app-layout>

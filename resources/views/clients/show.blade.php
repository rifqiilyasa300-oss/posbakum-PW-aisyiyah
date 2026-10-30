<x-app-layout>
    {{-- Tambahan CSS khusus tampilan HP --}}
    <style>
        /* Perbaikan tata letak untuk layar HP */
        @media only screen and (max-width: 767px) {
            /* Tambah jarak atas agar tidak ketutupan navigasi */
            .detail-wrapper {
                padding: 20px 0 !important;
                margin-top: 110px !important; /* Sesuaikan tinggi navigasi */
            }
            .detail-container {
                padding: 0 1rem !important;
            }

            /* Ubah grid 2 kolom jadi 1 kolom penuh */
            .detail-grid {
                display: flex !important;
                flex-direction: column !important;
                gap: 1.5rem !important;
            }

            /* Ubah grid data identitas jadi 1 kolom */
            .identitas-grid {
                display: flex !important;
                flex-direction: column !important;
                gap: 1rem !important;
            }

            /* Sesuaikan padding agar tidak terlalu sempit */
            .card-header {
                padding: 1.25rem !important;
            }
            .card-body {
                padding: 1.25rem !important;
            }
            .berkas-card, .tahapan-card {
                padding: 1.25rem !important;
            }

            /* Sesuaikan ukuran teks agar pas di layar kecil */
            h2 {
                font-size: 1.25rem !important;
            }
            p, label {
                font-size: 0.9rem !important;
            }

            /* Pastikan tidak ada elemen yang keluar layar */
            * {
                max-width: 100% !important;
                box-sizing: border-box !important;
                overflow-wrap: break-word !important;
            }

            /* Baris judul dan status agar turun ke bawah */
            .header-bar {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 10px !important;
            }
        }

        /* Untuk layar besar juga beri jarak aman */
        @media only screen and (min-width: 768px) {
            .detail-wrapper {
                margin-top: 90px !important;
            }
        }
    </style>

    <div class="detail-wrapper" style="background-color: #f8fafc; min-height: 100vh; padding: 40px 0; font-family: 'Roboto', sans-serif;">
        <div class="detail-container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">

            <div class="header-bar" style="margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('dashboard') }}"
    style="text-decoration: none; color: #064e3b; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 5px;">
        <span>&larr;</span> KEMBALI KE DASHBOARD
    </a>
                    <span style="color: #cbd5e1;">|</span>
                    <span style="color: #64748b; font-size: 14px;">Detail Pendaftaran Perkara</span>
                </div>
                <div style="padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; background: #ecfdf5; color: #065f46; border: 1px solid #10b981;">
                    STATUS: {{ strtoupper($perkara->status ?? 'Pendaftaran Diterima') }}
                </div>
            </div>

            <div class="detail-grid" style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 30px; align-items: start;">

                <div style="background: white; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <div class="card-header" style="background: #064e3b; padding: 25px 35px; border-bottom: 5px solid #065f46;">
                        <h2 style="color: white; font-size: 22px; font-weight: 500; margin: 0;">{{ strtoupper($perkara->nama ?? 'Nama Klien') }}</h2>
                        <p style="color: #a7f3d0; font-size: 13px; margin-top: 5px; opacity: 0.9;">Kode Registrasi: {{ $perkara->reg_code }}</p>
                    </div>

                    <div class="card-body" style="padding: 35px;">
                        <h3 style="font-size: 13px; color: #064e3b; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; font-weight: bold;">A. Profil Identitas</h3>
                        <div class="identitas-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">


                            <div style="grid-column: span 2;">
                                <label style="display: block; font-size: 11px; color: #64748b; font-weight: bold; text-transform: uppercase;">Nomor Induk Kependudukan (NIK)</label>
                                <p style="font-size: 15px; font-weight: 500; color: #1e293b; margin-top: 4px; font-family: monospace;">{{ $perkara->nik ?? '-' }}</p>
                            </div>

                            <div style="grid-column: span 2;">
                                <label style="display: block; font-size: 11px; color: #64748b; font-weight: bold; text-transform: uppercase;">Alamat Email Terdaftar</label>
                                <p style="font-size: 15px; font-weight: 500; color: #1e293b; margin-top: 4px;">{{ $perkara->email ?? '-' }}</p>
                            </div>


                            <div>
                                <label style="display: block; font-size: 11px; color: #64748b; font-weight: bold; text-transform: uppercase;">Tempat, Tanggal Lahir</label>
                                <p style="font-size: 15px; font-weight: 500; color: #1e293b; margin-top: 4px;">{{ $perkara->tempat_tanggal_lahir ?? '-' }}</p>
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; color: #64748b; font-weight: bold; text-transform: uppercase;">Jenis Kelamin</label>
                                <p style="font-size: 15px; font-weight: 500; color: #1e293b; margin-top: 4px;">{{ $perkara->jenis_kelamin ?? '-' }}</p>
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; color: #64748b; font-weight: bold; text-transform: uppercase;">Agama</label>
                                <p style="font-size: 15px; font-weight: 500; color: #1e293b; margin-top: 4px;">{{ $perkara->agama ?? '-' }}</p>
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; color: #64748b; font-weight: bold; text-transform: uppercase;">Status Perkawinan</label>
                                <p style="font-size: 15px; font-weight: 500; color: #1e293b; margin-top: 4px;">{{ $perkara->status_perkawinan ?? '-' }}</p>
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; color: #64748b; font-weight: bold; text-transform: uppercase;">Pendidikan Terakhir</label>
                                <p style="font-size: 15px; font-weight: 500; color: #1e293b; margin-top: 4px;">{{ $perkara->pendidikan ?? '-' }}</p>
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; color: #64748b; font-weight: bold; text-transform: uppercase;">Pekerjaan</label>
                                <p style="font-size: 15px; font-weight: 500; color: #1e293b; margin-top: 4px;">{{ $perkara->pekerjaan ?? '-' }}</p>
                            </div>
                        </div>

                        <h3 style="font-size: 13px; color: #064e3b; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; font-weight: bold;">B. Kontak & Alamat</h3>
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; font-size: 11px; color: #64748b; font-weight: bold; text-transform: uppercase;">Nomor HP / WhatsApp</label>
                            <p style="font-size: 16px; font-weight: bold; color: #064e3b; margin-top: 4px;">{{ $perkara->phone ?? '-' }}</p>
                        </div>
                        <div class="identitas-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                            <div style="grid-column: span 2;">
                                <label style="display: block; font-size: 11px; color: #64748b; font-weight: bold; text-transform: uppercase;">Alamat KTP</label>
                                <div style="font-size: 14px; color: #1e293b; background: #f8fafc; padding: 12px; border-radius: 8px; margin-top: 6px; line-height: 1.5;">{{ $perkara->alamat_ktp ?? '-' }}</div>
                            </div>
                            <div style="grid-column: span 2; margin-top: 10px;">
                                <label style="display: block; font-size: 11px; color: #64748b; font-weight: bold; text-transform: uppercase;">Alamat Domisili</label>
                                <div style="font-size: 14px; color: #1e293b; background: #f8fafc; padding: 12px; border-radius: 8px; margin-top: 6px; line-height: 1.5;">{{ $perkara->alamat_domisili ?? '-' }}</div>
                            </div>
                        </div>

                        <h3 style="font-size: 13px; color: #064e3b; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; font-weight: bold;">C. Informasi Masalah Hukum</h3>
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; font-size: 11px; color: #64748b; font-weight: bold; text-transform: uppercase;">Kategori Layanan</label>
                            <span style="display: inline-block; margin-top: 5px; padding: 4px 12px; background: #ecfdf5; color: #065f46; border-radius: 4px; font-size: 12px; font-weight: bold;">{{ $perkara->kategori_layanan ?? 'UMUM' }}</span>
                        </div>
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; font-size: 11px; color: #64748b; font-weight: bold; text-transform: uppercase;">Uraian Masalah</label>
                            <div style="font-size: 14px; color: #1e293b; line-height: 1.6; background: #fffbeb; border: 1px solid #fef3c7; padding: 15px; border-radius: 8px; margin-top: 8px; white-space: pre-wrap;">{{ $perkara->uraian_masalah ?? '-' }}</div>
                        </div>
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; font-size: 11px; color: #64748b; font-weight: bold; text-transform: uppercase;">Harapan Klien</label>
                            <div style="font-size: 14px; color: #1e293b; line-height: 1.6; background: #f8fafc; padding: 15px; border-radius: 8px; margin-top: 8px; white-space: pre-wrap;">{{ $perkara->harapan_klien ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="berkas-card" style="background: #1e293b; border-radius: 12px; padding: 30px; color: white; margin-bottom: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                        <h3 style="font-size: 12px; color: #34d399; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 25px;">Berkas Terlampir</h3>
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            @if(!empty($perkara->ktp_path))
                            <div style="display: flex; align-items: center; justify-content: space-between; background: #334155; padding: 12px; border-radius: 8px; border: 1px solid #475569;">
                                <span style="font-size: 12px;">Dokumen KTP</span>
                                <a href="{{ route('clients.viewFile', [$perkara->id, 'ktp']) }}" target="_blank" style="color: #34d399; font-size: 12px; font-weight: bold; text-decoration: none;">LIHAT FILE</a>
                            </div>
                            @endif
                            @if(!empty($perkara->sktm_path))
                            <div style="display: flex; align-items: center; justify-content: space-between; background: #334155; padding: 12px; border-radius: 8px; border: 1px solid #475569;">
                                <span style="font-size: 12px;">Dokumen SKTM</span>
                                <a href="{{ route('clients.viewFile', [$perkara->id, 'sktm']) }}" target="_blank" style="color: #34d399; font-size: 12px; font-weight: bold; text-decoration: none;">LIHAT FILE</a>
                            </div>
                            @endif
                            @if(!empty($perkara->bukti_bayar_path))
                            <div style="display: flex; align-items: center; justify-content: space-between; background: #334155; padding: 12px; border-radius: 8px; border: 1px solid #475569;">
                                <span style="font-size: 12px;">Bukti Pembayaran</span>
                                <a href="{{ route('clients.viewFile', [$perkara->id, 'pembayaran']) }}" target="_blank" style="color: #34d399; font-size: 12px; font-weight: bold; text-decoration: none;">LIHAT FILE</a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="tahapan-card" style="background: white; border-radius: 12px; padding: 30px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                        <h3 style="font-size: 14px; font-weight: bold; color: #1e293b; margin-bottom: 20px;">Tahapan Berjalan</h3>

                        <div style="margin-bottom: 20px; border-left: 3px solid #10b981; padding-left: 15px;">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 8px;">Tahapan Saat Ini:</label>

                            <p style="font-size: 15px; font-weight: bold; color: #064e3b; margin: 0;">
                                {{ strtoupper($perkara->status) }}
                            </p>

                            @if(!empty($perkara->catatan_admin))
                                <p style="font-size: 12px; color: #64748b; margin-top: 5px; font-style: italic;">
                                    Catatan: {{ $perkara->catatan_admin }}
                                </p>
                            @endif
                        </div>

                        {{-- Tombol Surat Rujukan muncul jika status = Rujuk --}}
                        @if($perkara->status === 'Rujuk')
                        <div style="margin-top: 20px; padding-top: 15px; border-top: 1px dashed #e2e8f0;">
                            <a href="{{ route('client.surat.rujukan', $perkara->id) }}"
                            style="display: block; width: 100%; padding: 10px; background: #f59e0b; color: white; text-align: center; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 13px;">
                                📄 BUKA SURAT RUJUKAN
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

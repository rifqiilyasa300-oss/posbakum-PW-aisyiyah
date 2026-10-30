<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Rujukan - POSBAKUM PW AISYIYAH DKI JAKARTA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.6;
            margin: 2cm 2cm 2cm 2cm;
            color: #000;
            background: #fff;
        }

        /* ====================================== */
        /* ✅ PERBAIKAN KHUSUS TAMPILAN HP */
        /* ====================================== */
        @media only screen and (max-width: 767px) {
            body {
                margin: 120px 1rem 2rem 1rem !important; /* Jarak atas agar tidak ketutupan tombol */
                font-size: 10pt !important;
            }

            .kop-teks h2 {
                font-size: 12pt !important;
            }

            .kop-teks h3 {
                font-size: 11pt !important;
            }

            .kop-teks p {
                font-size: 8pt !important;
            }

            .label {
                min-width: 120px !important;
                font-size: 9pt !important;
            }

            .isi-data, .isi, .ttd {
                font-size: 9pt !important;
            }

            /* Sesuaikan posisi tombol agar tidak menutupi kop surat */
            .panel-kontrol, .tombol-klien {
                position: fixed !important;
                top: 10px !important;
                right: 10px !important;
                left: 10px !important;
                width: auto !important;
                flex-direction: row !important;
                justify-content: center !important;
                gap: 8px !important;
                padding: 8px !important;
                z-index: 9999 !important;
            }

            .btn-kembali-klien, .btn-cetak-klien,
            .btn-simpan, .btn-cetak, .btn-kembali {
                font-size: 9pt !important;
                padding: 6px 10px !important;
                flex: 1 !important;
            }
        }

        /* Panel Isian Penandatangan - Hanya terlihat untuk Admin */
        .panel-kontrol {
            position: fixed;
            top: 15px;
            right: 15px;
            background: #f8f9fa;
            padding: 10px 12px;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            z-index: 999;
            width: 260px;
        }

        .panel-kontrol h4 {
            font-size: 10pt;
            margin-bottom: 8px;
            color: #333;
        }

        .panel-kontrol input {
            width: 100%;
            padding: 5px 7px;
            margin-bottom: 6px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 10pt;
        }

        .btn-simpan, .btn-cetak, .btn-kembali {
            width: 100%;
            padding: 7px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 10pt;
            margin-bottom: 4px;
            text-align: center;
            display: block;
            text-decoration: none;
        }

        .btn-simpan {
            background-color: #0a6640;
            color: white;
        }

        .btn-simpan:hover {
            background-color: #085233;
            color: white;
        }

        .btn-cetak {
            background-color: #2563eb;
            color: white;
        }

        .btn-cetak:hover {
            background-color: #1d4ed8;
            color: white;
        }

        .btn-kembali {
            background-color: #6c757d;
            color: white;
        }

        .btn-kembali:hover {
            background-color: #5a6268;
            color: white;
        }

        /* Tombol untuk Klien: Kembali + Cetak */
        .tombol-klien {
            position: fixed;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 10px;
            z-index: 999;
        }

        .btn-kembali-klien {
            background-color: #6c757d;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
            font-size: 11pt;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .btn-kembali-klien:hover {
            background-color: #5a6268;
            color: white;
        }

        .btn-cetak-klien {
            background-color: #10b981;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: bold;
            border: none;
            font-size: 11pt;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .btn-cetak-klien:hover {
            background-color: #059669;
        }

        /* Kop Surat */
        .kop-surat {
            text-align: center;
            margin-bottom: 10px;
        }

        .kop-surat img {
            width: 55px;
            height: auto;
            display: inline-block;
            vertical-align: middle;
            margin-right: 10px;
        }

        .kop-teks {
            display: inline-block;
            vertical-align: middle;
            text-align: center;
        }

        .kop-teks h2 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-teks h3 {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-teks p {
            font-size: 9pt;
            line-height: 1.4;
        }

        .garis {
            border: none;
            border-top: 2px solid #000;
            border-bottom: 1px solid #000;
            margin: 6px 0 15px 0;
            height: 2px;
        }

        .tanggal {
            text-align: right;
            margin-bottom: 8px;
            font-size: 11pt;
        }

        /* ✅ Baris data lurus sejajar */
        .baris-data {
            display: flex;
            margin: 4px 0;
            font-size: 11pt;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .label {
            min-width: 180px;
            display: inline-block;
            flex-shrink: 0;
            font-weight: normal;
        }

        .isi-data {
            flex: 1;
            text-align: left;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .isi {
            text-align: justify;
            margin-top: 12px;
            font-size: 11pt;
        }

        .data-klien {
            margin: 10px 0 10px 20px;
        }

        .tujuan {
            margin: 12px 0 12px 20px;
        }

        .keterangan {
            margin: 8px 0;
        }

        /* Bagian Tanda Tangan */
        .ttd {
            margin-top: 35px;
            text-align: right;
            font-size: 11pt;
        }

        .nama-ttd {
            margin-top: 50px;
            font-weight: bold;
            text-decoration: underline;
            min-height: 20px;
        }

        .nip-ttd {
            margin-top: 3px;
        }

        /* Sembunyikan semua tombol saat dicetak */
        @media print {
            .panel-kontrol, .tombol-klien {
                display: none !important;
            }
            body {
                margin: 2cm 2cm 1.8cm 2cm !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                font-size: 11pt !important;
            }
            @page {
                size: A4;
                margin: 2cm;
            }
        }
    </style>
</head>
<body>
    <!-- ✅ KHUSUS ADMIN: Tampilkan panel untuk isi & simpan data -->
    @if(auth()->check() && auth()->user()->role === 'admin')
    <div class="panel-kontrol">
        <h4>Isi Penandatangan</h4>

        <form action="{{ route('admin.surat.rujukan', $client->id) }}" method="POST">
            @csrf
            <input type="text" name="nama_penandatangan" id="inputNama" placeholder="Nama lengkap & gelar" value="{{ old('nama_penandatangan', $client->nama_penandatangan ?? '') }}">
            <input type="text" name="nip_penandatangan" id="inputNip" placeholder="NIP / Nomor Induk" value="{{ old('nip_penandatangan', $client->nip_penandatangan ?? '') }}">
            <button type="submit" class="btn-simpan">💾 Simpan Data</button>
        </form>

        <button type="button" onclick="window.print()" class="btn-cetak">🖨️ Cetak Surat</button>
        <a href="{{ route('admin.clients.show', $client->id) }}" class="btn-kembali">⬅️ Kembali ke Detail</a>
    </div>
    @endif

    <!-- ✅ KHUSUS KLIEN: Tombol Kembali + Cetak -->
    @if(auth()->check() && auth()->user()->role === 'klien')
    <div class="tombol-klien">
        @php
            // Cek dari mana asal halaman sebelumnya
            $previousUrl = url()->previous();
            // Jika halaman sebelumnya mengandung '/perkara/' berarti dari detail, jika tidak arahkan ke dashboard
            $kembaliKe = str_contains($previousUrl, '/perkara/')
                ? route('client.perkara.show', $client->id)
                : route('dashboard');
        @endphp
        <a href="{{ $kembaliKe }}" class="btn-kembali-klien">⬅️ Kembali</a>
        <button type="button" onclick="window.print()" class="btn-cetak-klien">🖨️ Cetak Surat</button>
    </div>
    @endif

    <!-- Isi Surat -->
    <div class="kop-surat">
        <img src="{{ asset('images/logo-aisyiyah.png') }}" alt="Logo Aisyiyah">
        <div class="kop-teks">
            <h2>POS BANTUAN HUKUM</h2>
            <h3>PIMPINAN WILAYAH AISYIYAH DKI JAKARTA</h3>
            <p>Jl. TEBET TIMUR DALAM NO.4 RT.012 RW.005 JAKARTA SELATAN 12820<br>
            Telp: 0816 1159 564 | Email: posbakum_aisyiyah_jakarta<br>
            Website: https://aisyiyah.muhammadiyahjakarta.id</p>
        </div>
    </div>

    <hr class="garis">

    <div class="tanggal">
        Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
    </div>

    <div class="baris-data">
        <span class="label"><strong>Nomor</strong></span>
        <span class="isi-data">: {{ $client->reg_code }}/SR/PW-Aisyiyah-DKI/{{ date('Y') }}</span>
    </div>
    <div class="baris-data">
        <span class="label"><strong>Perihal</strong></span>
        <span class="isi-data">: SURAT RUJUKAN</span>
    </div>

    <div class="isi">
        <p>Yang bertanda tangan di bawah ini, Kepala Pos Bantuan Hukum Pimpinan Wilayah Aisyiyah DKI Jakarta, menerangkan bahwa:</p>

        <div class="data-klien">
            <div class="baris-data">
                <span class="label">Nama Lengkap</span>
                <span class="isi-data">: {{ $client->nama }}</span>
            </div>
            <div class="baris-data">
                <span class="label">Tempat, Tanggal Lahir</span>
                <span class="isi-data">: {{ $client->tempat_tanggal_lahir }}</span>
            </div>
            <div class="baris-data">
                <span class="label">Alamat Lengkap</span>
                <span class="isi-data">: {{ $client->alamat_ktp }}</span>
            </div>
            <div class="baris-data">
                <span class="label">Nomor Registrasi</span>
                <span class="isi-data">: {{ $client->reg_code }}</span>
            </div>
        </div>

        <p>Berdasarkan hasil wawancara, pemeriksaan berkas, dan konsultasi hukum yang telah dilaksanakan pada tanggal {{ $client->jadwal_konsultasi ? \Carbon\Carbon::parse($client->jadwal_konsultasi)->translatedFormat('d F Y') : '-' }}, perkara yang dihadapi oleh yang bersangkutan memerlukan penanganan lebih lanjut dari instansi yang berwenang. Oleh karena itu, kami merujuk perkara ini kepada:</p>

        <div class="tujuan">
            <div class="baris-data">
                <span class="label">Instansi</span>
                <span class="isi-data">: {{ $client->tujuan_rujuk ?? '-' }}</span>
            </div>
            <div class="baris-data">
                <span class="label">Alamat</span>
                <span class="isi-data">: {{ $client->alamat_tujuan ?? '-' }}</span>
            </div>
            <div class="baris-data">
                <span class="label">Keterangan</span>
                <span class="isi-data">: {{ $client->catatan_admin ?? '-' }}</span>
            </div>
        </div>

        <p>Demikian surat rujukan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <!-- Bagian Tanda Tangan -->
    <div class="ttd">
        <p>Hormat kami,</p>
        <p>Kepala Pos Bantuan Hukum<br>
        Pimpinan Wilayah Aisyiyah DKI Jakarta</p>

        <p class="nama-ttd" id="ttdNama">
            {{ $client->nama_penandatangan ?? '.................................' }}
        </p>
        <p class="nip-ttd" id="ttdNip">
            {{ !empty($client->nip_penandatangan) ? 'NIP. '.$client->nip_penandatangan : 'NIP. ...........................' }}
        </p>
    </div>

    <!-- Script hanya untuk admin -->
    @if(auth()->check() && auth()->user()->role === 'admin')
    <script>
        const inputNama = document.getElementById('inputNama');
        const inputNip = document.getElementById('inputNip');
        const ttdNama = document.getElementById('ttdNama');
        const ttdNip = document.getElementById('ttdNip');

        inputNama.addEventListener('input', function() {
            ttdNama.textContent = this.value.trim() || '.................................';
        });

        inputNip.addEventListener('input', function() {
            ttdNip.textContent = this.value.trim() ? `NIP. ${this.value}` : 'NIP. ...........................';
        });
    </script>
    @endif
</body>
</html>

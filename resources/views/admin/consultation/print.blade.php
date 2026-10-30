<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - {{ $report->nama_klien }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4; margin: 0; }
        body { background-color: #525659; margin: 0; padding: 20px 0; display: flex; flex-direction: column; align-items: center; font-family: Arial, sans-serif; }

        .a4-container {
            width: 210mm;
            min-height: 297mm;
            padding: 15mm;
            background: white;
            box-sizing: border-box;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .formal-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .formal-table td { border: 1px solid #000; padding: 6px 10px; vertical-align: top; font-size: 10pt; }
        .label { font-weight: bold; background-color: #f8fafc; }

        /* Lingkaran Radio Button (Visual) */
        .radio-circle {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            border-radius: 50%;
            margin-right: 5px;
            vertical-align: middle;
        }

        /* Garis-garis khas form */
        .editable-lines {
            width: 100%;
            background-image: repeating-linear-gradient(transparent, transparent 24px, #000 25px);
            background-size: 100% 25px;
            line-height: 25px;
            padding: 0;
            margin-top: 5px;
            font-size: 11pt;
            white-space: pre-wrap;
            min-height: 50px;
        }

        .no-print { width: 210mm; display: flex; justify-content: space-between; margin-bottom: 10px; }

        @media print {
            .no-print { display: none !important; }
            body { background: none; padding: 0; }
            .a4-container { box-shadow: none; margin: 0; border: none; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="{{ route('admin.clients.show', $report->client_id) }}" class="bg-gray-500 text-white px-4 py-2 rounded text-sm font-bold">Kembali</a>
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded font-bold">CETAK DOKUMEN</button>
    </div>

    <div class="a4-container">
        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td width="15%"><img src="{{ asset('images/logo-aisyiyah.png') }}" width="60"></td>
                <td align="center">
                    <div style="font-size: 12pt; font-weight: bold;">POS BANTUAN HUKUM (POSBAKUM)</div>
                    <div style="font-size: 14pt; font-weight: bold;">PIMPINAN WILAYAH AISYIYAH JAKARTA</div>
                    <div style="margin-top: 8px; font-weight: bold; text-decoration: underline; text-transform: uppercase;">Laporan Hasil Konsultasi Hukum</div>
                </td>
                <td width="130">
                    <table style="width: 100%; border: 1px solid #000; font-size: 8pt; border-collapse: collapse;">
                        <tr><td style="border: 1px solid #000; padding: 2px;">No. Dok</td><td style="border: 1px solid #000; padding: 2px;">: {{ $report->no_dokumen ?? '-' }}</td></tr>
                        <tr><td style="border: 1px solid #000; padding: 2px;">Tanggal</td><td style="border: 1px solid #000; padding: 2px;">: {{ \Carbon\Carbon::parse($report->created_at)->format('d/m/Y') }}</td></tr>
                        <tr><td style="border: 1px solid #000; padding: 2px;">Revisi</td><td style="border: 1px solid #000; padding: 2px;">: 00</td></tr>
                        <tr><td style="border: 1px solid #000; padding: 2px;">Halaman</td><td style="border: 1px solid #000; padding: 2px;">: 1 dari 1</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="formal-table">
            <tr><td class="label">No. Reg</td><td>#{{ $report->no_reg }}</td><td class="label">Tanggal</td><td>{{ \Carbon\Carbon::parse($report->tanggal)->format('d/m/Y') }}</td></tr>
            <tr><td class="label">Nama Klien</td><td>{{ $report->nama_klien }}</td><td class="label">Waktu</td><td>{{ $report->waktu }}</td></tr>
            <tr>
                <td class="label">Kasus</td><td>{{ $report->kasus }}</td>
                <td class="label">Konsultan Hukum</td>
                <td>
                    <div style="font-weight: bold; margin-bottom: 4px;">Advokat:</div>
                    <div style="color: #666; margin-bottom: 6px;">{{ $report->advokat_nama ?: '...' }}</div>
                    <div style="font-size: 9pt;">
                        <span class="radio-circle" style="{{ $report->konsultan_tipe == 'Pusat' ? 'background: #000;' : '' }}"></span> Paralegal Pusat
                        <span class="radio-circle" style="margin-left: 10px; {{ $report->konsultan_tipe == 'Daerah' ? 'background: #000;' : '' }}"></span> Paralegal Daerah<br>
                        <span class="radio-circle" style="{{ $report->konsultan_tipe == 'Cabang' ? 'background: #000;' : '' }}"></span> Paralegal Cabang
                    </div>
                    <!-- Garis titik-titik dihapus pada baris di bawah ini -->
                    <div style="margin-top: 8px; width: 100%; height: 15px;">{{ $report->paralegal_nama }}</div>
                </td>
            </tr>
            <tr><td class="label">Pertemuan ke</td><td>{{ $report->pertemuan_ke }}</td><td class="label">Supervisor</td><td>{{ $report->supervisor_nama }}</td></tr>
            <tr><td class="label">Tempat</td><td>{{ $report->tempat }}</td><td class="label">Koordinator Hukum</td><td>{{ $report->koordinator_nama }}</td></tr>
        </table>

        <div class="mb-4"><span style="font-weight: bold; font-size: 10pt;">Permasalahan klien :</span><div class="editable-lines">{{ $report->permasalahan_klien }}</div></div>
        <div class="mb-4"><span style="font-weight: bold; font-size: 10pt;">Harapan klien :</span><div class="editable-lines">{{ $report->harapan_klien }}</div></div>
        <div class="mb-4"><span style="font-weight: bold; font-size: 10pt;">Legal Advice :</span><div class="editable-lines">{{ $report->legal_advice }}</div></div>
        <div class="mb-4"><span style="font-weight: bold; font-size: 10pt;">Rencana Tindak Lanjut :</span><div class="editable-lines">{{ $report->rencana_tindak_lanjut }}</div></div>
        <div class="mb-4"><span style="font-weight: bold; font-size: 9pt;">Catatan Supervisi :</span><div class="editable-lines">{{ $report->catatan_supervisi ?? '-' }}</div></div>

        <div style="margin-top: auto; text-align: right; font-size: 8pt; font-weight: bold; border-top: 1px solid #000; padding-top: 5px;">Page 1 of 1</div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Konsultasi Hukum</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* PENGATURAN KERTAS A4 */
        @page { size: A4; margin: 0; }
        body {
            background-color: #525659;
            margin: 0;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Arial, sans-serif;
        }

.a4-container {
    width: 210mm;
    min-height: 297mm; /* Gunakan min-height agar bisa memanjang */
    padding: 15mm;
    background: white;
    box-shadow: 0 0 10px rgba(0,0,0,0.5);
    display: flex;
    flex-direction: column;
    position: relative;
    /* overflow: hidden;  <-- HAPUS INI */
}

        /* RESET INPUT UNTUK FORM */
        input[type="text"], input[type="date"], input[type="time"], textarea {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-family: Arial, sans-serif;
            font-size: 10pt;
            padding: 2px 0;
        }

        textarea {
            resize: none;
            overflow-y: hidden;
            word-wrap: break-word;
            white-space: pre-wrap;
        }

        .formal-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .formal-table td { border: 1px solid #000; padding: 6px 10px; vertical-align: top; }
        .label { font-weight: bold; font-size: 10pt; background-color: #f8fafc; }

        .editable-lines {
            width: 100%;
            border: none;
            background-image: repeating-linear-gradient(transparent, transparent 24px, #000 25px);
            background-size: 100% 25px;
            line-height: 25px;
            padding: 0;
            margin-top: 5px;
            font-size: 11pt;
            outline: none;
            display: block;
        }

        .no-print-zone {
            width: 210mm;
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        /* Footer yang menempel di bawah */
        .page-footer {
            margin-top: auto;
            text-align: right;
            font-size: 8pt;
            font-weight: bold;
            padding-top: 10px;
            border-top: 1px solid #ccc;
        }

        @media print {
            .no-print-zone { display: none !important; }
            body { background: none; padding: 0; }
            .a4-container { box-shadow: none; margin: 0; border: none; }
        }
    </style>
</head>
<body>

    <form action="{{ auth()->user()->role === 'admin' ? route('admin.consultation.store') : route('advokat.consultation.store') }}" method="POST">
        @csrf
        <input type="hidden" name="client_id" value="{{ $client->id }}">
        <input type="hidden" name="pertemuan_ke" value="{{ $pertemuanKe }}">

        <div class="no-print-zone">
            <a href="{{ url()->previous() }}" class="bg-gray-500 text-white px-4 py-2 rounded text-sm font-bold"> Kembali</a>
            <div class="flex gap-2">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded font-bold shadow-lg hover:bg-green-700"> SIMPAN LAPORAN</button>
                <button type="button" onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded font-bold shadow-lg hover:bg-blue-700"> CETAK</button>
            </div>
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
                            <tr><td style="border: 1px solid #000; padding: 2px;">No. Dok</td><td style="border: 1px solid #000; padding: 2px;">: <input type="text" name="no_dokumen" style="width: 60px;" placeholder="..."></td></tr>
                            <tr><td style="border: 1px solid #000; padding: 2px;">Tanggal</td><td style="border: 1px solid #000; padding: 2px;">: {{ date('d/m/Y') }}</td></tr>
                            <tr><td style="border: 1px solid #000; padding: 2px;">Revisi</td><td style="border: 1px solid #000; padding: 2px;">: 00</td></tr>
                            <tr><td style="border: 1px solid #000; padding: 2px;">Halaman</td><td style="border: 1px solid #000; padding: 2px;">: 1 dari 1</td></tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table class="formal-table">
                <tr>
                    <td class="label">No. Reg</td>
                    <td width="35%"><input type="text" value="#{{ $client->reg_code }}" readonly></td>
                    <td class="label">Tanggal</td>
                    <td width="35%"><input type="date" name="tanggal" value="{{ date('Y-m-d') }}"></td>
                </tr>
                <tr>
                    <td class="label">Nama Klien</td>
                    <td><input type="text" value="{{ $client->nama }}" readonly></td>
                    <td class="label">Waktu</td>
                    <td><input type="time" name="waktu" value="{{ date('H:i') }}"></td>
                </tr>
                <tr>
                    <td rowspan="2" class="label">Kasus</td>
                    <td rowspan="2"><textarea name="kasus" style="height: 60px;"></textarea></td>
                    <td class="label" style="border-bottom: none;">Konsultan Hukum</td>
                    <td>
                        <div style="font-weight: bold; margin-bottom: 4px;">Advokat:</div>
                        <input type="text" name="advokat_nama" placeholder="Ketik nama..." style="margin-bottom: 6px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px; font-size: 8.5pt;">
                            <label style="display: flex; align-items: center; gap: 4px;"><input type="radio" name="konsultan_tipe" value="Pusat"> Paralegal Pusat</label>
                            <label style="display: flex; align-items: center; gap: 4px;"><input type="radio" name="konsultan_tipe" value="Daerah"> Paralegal Daerah</label>
                            <label style="display: flex; align-items: center; gap: 4px; grid-column: span 2;"><input type="radio" name="konsultan_tipe" value="Cabang"> Paralegal Cabang</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="border-top: none;"></td>
                    <td><input type="text" name="paralegal_nama" placeholder="ktik..."></td>
                </tr>
                <tr>
                    <td class="label">Pertemuan ke</td>
                    <td><input type="text" value="{{ $pertemuanKe }}" readonly></td>
                    <td class="label">Supervisor</td>
                    <td><input type="text" name="supervisor_nama"></td>
                </tr>
                <tr>
                    <td class="label">Tempat</td>
                    <td><input type="text" name="tempat" value="Kantor Posbakum PWA Jakarta"></td>
                    <td class="label">Koordinator Hukum</td>
                    <td><input type="text" name="koordinator_nama"></td>
                </tr>
            </table>

            <div class="mb-2"><span style="font-weight: bold; font-size: 10pt;">Permasalahan klien :</span><textarea name="permasalahan_klien" class="editable-lines" rows="5" required></textarea></div>
            <div class="mb-2"><span style="font-weight: bold; font-size: 10pt;">Harapan klien :</span><textarea name="harapan_klien" class="editable-lines" rows="2" required></textarea></div>
            <div class="mb-2"><span style="font-weight: bold; font-size: 10pt;">Legal Advice :</span><textarea name="legal_advice" class="editable-lines" rows="5" required></textarea></div>
            <div class="mb-2"><span style="font-weight: bold; font-size: 10pt;">Rencana Tindak Lanjut :</span><textarea name="rencana_tindak_lanjut" class="editable-lines" rows="2" required></textarea></div>
            <div class="mb-2"><span style="font-weight: bold; font-size: 9pt;">Catatan Supervisi :</span><textarea name="catatan_supervisi" class="editable-lines" rows="2"></textarea></div>

            <div class="page-footer">Page 1 of 1</div>
        </div>
    </form>

<script>
    document.querySelectorAll('textarea').forEach(el => {
        // Jalankan saat halaman dimuat untuk menyesuaikan tinggi awal
        el.style.height = 'auto';
        el.style.height = (el.scrollHeight) + 'px';

        // Jalankan saat mengetik
        el.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
</script>
</body>
</html>

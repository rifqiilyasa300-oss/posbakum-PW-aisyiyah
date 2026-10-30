<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Data Penggugat</title>
    <style>
        @page { size: A4; margin: 1.5cm; }
        body { font-family: "Times New Roman", serif; font-size: 11pt; color: #000; line-height: 1.3; }
        .container { width: 100%; }

        .header { text-align: center; margin-bottom: 20px; }
        .header img { width: 60px; margin-bottom: 5px; }
        .header h1 { font-size: 12pt; margin: 0; font-weight: bold; }
        .header h2 { font-size: 12pt; margin: 0; font-weight: bold; }

        .section-title { font-weight: bold; margin: 15px 0 5px 0; text-transform: uppercase; }

        /* Baris Input */
        .form-row { display: flex; margin-bottom: 3px; align-items: baseline; }
        .label { width: 160px; flex-shrink: 0; }
        .dots { flex-grow: 1; border-bottom: 1px dotted #000; min-height: 18px; font-weight: bold; }

        /* Grid Checkbox */
        .grid-container { display: grid; grid-template-columns: 1fr 1fr; gap: 5px; margin-top: 5px; }
        .check-item { display: flex; align-items: center; }
        input[type="checkbox"] { margin-right: 8px; transform: scale(1.1); }
        .garis-bawah { border-bottom: 1px dotted #000; width: 120px; display: inline-block; }

        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="{{ asset('images/logo-aisyiyah.png') }}" alt="Logo">
        <h1>FORM DATA PENGGUGAT</h1>
        <h2>POSBAKUM PW 'AISYIYAH DKI JAKARTA</h2>
    </div>

    <!-- SEKSI A -->
    <div class="section-title">A. DATA IDENTITAS PENGGUGAT</div>
    <div class="form-row"><div class="label">Nama Lengkap</div><div class="dots">{{ strtoupper($client->nama) }}</div></div>
    <div class="form-row"><div class="label">NIK</div><div class="dots">{{ $client->nik }}</div></div>
    <div class="form-row"><div class="label">Tempat, Tanggal Lahir</div><div class="dots">{{ $client->tempat_tanggal_lahir }}</div></div>
    <div class="form-row"><div class="label">Jenis Kelamin</div><div class="dots">{{ $client->jenis_kelamin }}</div></div>
    <div class="form-row"><div class="label">Agama</div><div class="dots">{{ $client->agama }}</div></div>
    <div class="form-row"><div class="label">Pekerjaan</div><div class="dots">{{ $client->pekerjaan }}</div></div>
    <div class="form-row"><div class="label">Alamat Lengkap</div><div class="dots">{{ $client->alamat_ktp }}</div></div>
    <div class="form-row"><div class="label" style="height:15px;"></div><div class="dots"></div></div>
    <div class="form-row"><div class="label">No. HP/WhatsApp</div><div class="dots">{{ $client->phone }}</div></div>
    <div class="form-row"><div class="label">Email (jika ada)</div><div class="dots">{{ $client->email }}</div></div>

 <div class="section-title">B. DATA PIHAK TERGUGAT</div>
<div class="form-row">
    <div class="label">Nama Lengkap</div>
    <div class="dots"><input type="text" name="tergugat_nama" class="input-form"></div>
</div>
<div class="form-row">
    <div class="label">Alamat Lengkap</div>
    <div class="dots"><input type="text" name="tergugat_alamat" class="input-form"></div>
</div>
<div class="form-row">
    <div class="label">Hubungan dengan Klien</div>
    <div class="dots"><input type="text" name="tergugat_hubungan" class="input-form"></div>
</div>
<div class="form-row">
    <div class="label">No. HP (jika diketahui)</div>
    <div class="dots"><input type="text" name="tergugat_hp" class="input-form"></div>
</div>

<style>
    /* Tambahkan CSS ini di dalam tag <style> Anda */
    .input-form {
        width: 100%;
        border: none;
        background: transparent;
        font-family: "Times New Roman", serif;
        font-size: 11pt;
        font-weight: bold;
        outline: none;
        padding: 0;
    }
</style>

    <!-- SEKSI C -->
    <div class="section-title">C. JENIS PERKARA</div>
    <div class="grid-container">
        <div class="check-item"><input type="checkbox"> Perceraian</div>
        <div class="check-item"><input type="checkbox"> KDRT</div>
        <div class="check-item"><input type="checkbox"> Hak Asuh Anak</div>
        <div class="check-item"><input type="checkbox"> Hutang Piutang</div>
        <div class="check-item"><input type="checkbox"> Waris</div>
        <div class="check-item"><input type="checkbox"> Sengketa Tanah</div>
        <div class="check-item"><input type="checkbox"> Perdata Umum</div>
        <div class="check-item"><input type="checkbox"> Lainnya <span class="garis-bawah"></span></div>
    </div>

    <!-- SEKSI D -->
    <div class="section-title">D. BUKTI DAN DOKUMEN</div>
    <div class="grid-container">
        <div class="check-item"><input type="checkbox"> KTP</div>
        <div class="check-item"><input type="checkbox"> Surat Perjanjian</div>
        <div class="check-item"><input type="checkbox"> KK</div>
        <div class="check-item"><input type="checkbox"> Bukti Transfer</div>
        <div class="check-item"><input type="checkbox"> Buku Nikah</div>
        <div class="check-item"><input type="checkbox"> Foto / Screenshot</div>
        <div class="check-item"><input type="checkbox"> Akta Kelahiran Anak</div>
        <div class="check-item"><input type="checkbox"> Lainnya <span class="garis-bawah"></span></div>
    </div>
</div>

<div class="no-print" style="margin-top: 30px;">
    <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">PRINT DOKUMEN</button>
</div>

</body>
</html>

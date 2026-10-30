<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PERBAIKI FORMULIR - Posbakum Aisyiyah</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        /* CSS RESET & BASE */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #f0ebf8; font-family: 'Roboto', sans-serif; padding: 12px; color: #202124; }
        .container { max-width: 770px; margin: 0 auto; }
        .banner-container { width: 100%; min-height: 150px; background-color: #fff; border: 1px solid #dadce0; border-bottom: none; border-radius: 8px 8px 0 0; overflow: hidden; display: flex; align-items: center; justify-content: center; }
        .banner-container img { width: 100%; max-height: 250px; object-fit: contain; padding: 10px; }
        .card { background-color: #fff; border: 1px solid #dadce0; border-radius: 8px; padding: 24px; margin-bottom: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .title-card { border-top: 10px solid #dc2626; border-radius: 0 0 8px 8px; } /* Warna merah untuk tanda perbaikan */
        .title-card h1 { font-size: 28px; font-weight: 400; margin-bottom: 8px; color: #991b1b; }
        .label-text { font-size: 16px; font-weight: 500; margin-bottom: 8px; display: block; }
        .sub-label { font-size: 12px; color: #70757a; margin-bottom: 15px; }
        .input-line { width: 100%; border: none; border-bottom: 1px solid #dadce0; padding: 8px 0; font-size: 14px; outline: none; background: transparent; transition: border-bottom 0.3s; }
        .input-line:focus { border-bottom: 2px solid #15803d; }
        .radio-group { margin-top: 15px; }
        .radio-item { display: flex; align-items: center; margin-bottom: 12px; cursor: pointer; }
        .radio-item input { margin-right: 12px; width: 20px; height: 20px; accent-color: #15803d; }
        .file-button { display: inline-flex; align-items: center; padding: 8px 16px; border: 1px solid #dadce0; border-radius: 4px; color: #15803d; font-weight: 500; cursor: pointer; font-size: 14px; background: #fff; }
        .btn-submit { background-color: #15803d; color: white; border: none; padding: 12px 30px; border-radius: 4px; font-weight: 500; cursor: pointer; font-size: 16px; }
        .error-msg { background: #fef2f2; border: 1px solid #fee2e2; color: #dc2626; padding: 15px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; }
        footer { text-align: center; font-size: 12px; color: #70757a; padding: 20px 0; }
        .required { color: #d93025; }
        .info-pembayaran { background: #f8f9fa; border: 1px dashed #dadce0; padding: 15px; border-radius: 8px; margin-bottom: 15px; }
        .alert-catatan { background: #fff1f2; border: 1px solid #fecdd3; padding: 15px; border-radius: 8px; margin-top: 15px; color: #9f1239; }
        .row { display: flex; gap: 15px; flex-wrap: wrap; }
        .col { flex: 1; min-width: 200px; }
    </style>
</head>
<body>

    <div class="container">
        <div class="banner-container">
            <img src="{{ asset('images/logo-aisyiyah.png') }}" alt="Logo Posbakum Aisyiyah">
        </div>

        @if ($errors->any())
            <div class="error-msg">
                <strong>Mohon perbaiki kesalahan berikut:</strong>
                <ul style="margin-top: 5px; list-style-position: inside;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('client.update', $client->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="card title-card">
                <h1>PERBAIKI DATA PENDAFTARAN</h1>
                <div class="alert-catatan">
                    <strong>Alasan Penolakan Admin:</strong><br>
                    {{ $client->catatan_admin ?? 'Tidak ada keterangan khusus' }}
                </div>
                <p style="color: #d93025; font-size: 14px; margin-top: 15px;">* Wajib diisi</p>
            </div>

            <div class="card">
                <label class="label-text">JENIS LAYANAN <span class="required">*</span></label>
                <div class="radio-group">
                    <label class="radio-item">
                        <input type="radio" name="jenis_layanan" value="BERBAYAR" required id="radio-umum" {{ old('jenis_layanan', $client->kategori_layanan) == 'UMUM' ? 'checked' : '' }}>
                        <span>UMUM (BERBAYAR)</span>
                    </label>
                    <label class="radio-item">
                        <input type="radio" name="jenis_layanan" value="GRATIS" id="radio-prodeo" {{ old('jenis_layanan', $client->kategori_layanan) == 'PRODEO' ? 'checked' : '' }}>
                        <span>PRODEO (GRATIS)</span>
                    </label>
                </div>
            </div>

            <div class="card" id="pembayaran-card" style="display: none;">
                <label class="label-text">BUKTI PEMBAYARAN ADMINISTRASI</label>
                <div class="info-pembayaran">
                    <p style="font-size: 14px;">Transfer <strong>Rp 300.000</strong> ke BRI <strong>083601000042568</strong></p>
                </div>
                <div class="sub-label">File saat ini: {{ $client->bukti_bayar_path ?? 'Belum ada' }}</div>
                <input type="file" name="upload_bukti_bayar" id="bayar-input" style="display: none;" onchange="document.getElementById('bayar-name').innerText = this.files[0].name">
                <label for="bayar-input" class="file-button">+ Ganti Bukti Bayar</label>
                <span id="bayar-name" style="margin-left: 10px; font-size: 13px; color: #555;"></span>
            </div>

            <div class="card" id="sktm-card" style="display: none;">
                <label class="label-text">UPLOAD SKTM</label>
                <div class="sub-label">File saat ini: {{ $client->sktm_path ?? 'Belum ada' }}</div>
                <input type="file" name="upload_sktm" id="sktm-input" style="display: none;" onchange="document.getElementById('sktm-name').innerText = this.files[0].name">
                <label for="sktm-input" class="file-button">+ Ganti File SKTM</label>
                <span id="sktm-name" style="margin-left: 10px; font-size: 13px; color: #555;"></span>
            </div>

            <div class="card">
                <label class="label-text">ALAMAT EMAIL <span class="required">*</span></label>
                <input type="email" name="email" value="{{ old('email', $client->email) }}" required class="input-line">
            </div>

            <div class="card">
                <label class="label-text">NAMA LENGKAP <span class="required">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $client->nama) }}" required class="input-line">
            </div>

            <div class="card">
                <label class="label-text">NIK <span class="required">*</span></label>
                <input type="text" name="nik" value="{{ old('nik', $client->nik) }}" required class="input-line" maxlength="16" pattern="\d{16}" title="Masukkan 16 digit angka NIK">
            </div>

            <div class="card row">
                <div class="col">
                    <label class="label-text">TEMPAT LAHIR <span class="required">*</span></label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $client->tempat_lahir) }}" required class="input-line">
                </div>
                <div class="col">
                    <label class="label-text">TANGGAL LAHIR <span class="required">*</span></label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $client->tanggal_lahir ? \Carbon\Carbon::parse($client->tanggal_lahir)->format('Y-m-d') : '') }}" required class="input-line">
                </div>
            </div>

            <div class="card">
                <label class="label-text">JENIS KELAMIN <span class="required">*</span></label>
                <div class="radio-group">
                    <label class="radio-item">
                        <input type="radio" name="jenis_kelamin" value="PEREMPUAN" required {{ old('jenis_kelamin', $client->jenis_kelamin) == 'PEREMPUAN' ? 'checked' : '' }}>
                        <span>PEREMPUAN</span>
                    </label>
                    <label class="radio-item">
                        <input type="radio" name="jenis_kelamin" value="LAKI-LAKI" {{ old('jenis_kelamin', $client->jenis_kelamin) == 'LAKI-LAKI' ? 'checked' : '' }}>
                        <span>LAKI-LAKI</span>
                    </label>
                </div>
            </div>

            <div class="card">
                <label class="label-text">ALAMAT SESUAI KTP <span class="required">*</span></label>
                <input type="text" name="alamat_ktp" value="{{ old('alamat_ktp', $client->alamat_ktp) }}" required class="input-line">
            </div>

            <div class="card">
                <label class="label-text">ALAMAT DOMISILI <span class="required">*</span></label>
                <input type="text" name="alamat_domisili" value="{{ old('alamat_domisili', $client->alamat_domisili) }}" required class="input-line">
            </div>

            <div class="card">
                <label class="label-text">NOMOR HP / WHATSAPP <span class="required">*</span></label>
                <input type="text" name="nomor_hp" value="{{ old('nomor_hp', $client->phone) }}" required class="input-line" placeholder="Contoh: 08123456789">
            </div>

            <div class="card">
                <label class="label-text">STATUS PERKAWINAN <span class="required">*</span></label>
                <input type="text" name="status_perkawinan" value="{{ old('status_perkawinan', $client->status_perkawinan) }}" required class="input-line">
            </div>

            <div class="card">
                <label class="label-text">AGAMA <span class="required">*</span></label>
                <input type="text" name="agama" value="{{ old('agama', $client->agama) }}" required class="input-line">
            </div>

            <div class="card">
                <label class="label-text">PENDIDIKAN TERAKHIR <span class="required">*</span></label>
                <input type="text" name="pendidikan" value="{{ old('pendidikan', $client->pendidikan) }}" required class="input-line">
            </div>

            <div class="card">
                <label class="label-text">PEKERJAAN <span class="required">*</span></label>
                <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $client->pekerjaan) }}" required class="input-line">
            </div>

            <div class="card">
                <label class="label-text">URAIAN SINGKAT MASALAH <span class="required">*</span></label>
                <textarea name="uraian_masalah" required class="input-line" style="height: 100px; resize: none;">{{ old('uraian_masalah', $client->uraian_masalah) }}</textarea>
            </div>

            <div class="card">
                <label class="label-text">HARAPAN KLIEN <span class="required">*</span></label>
                <input type="text" name="harapan_klien" value="{{ old('harapan_klien', $client->harapan_klien) }}" required class="input-line">
            </div>

            <div class="card">
                <label class="label-text">UPLOAD KTP</label>
                <div class="sub-label">File saat ini: {{ $client->ktp_path ?? 'Belum ada' }}</div>
                <input type="file" name="upload_ktp" id="ktp" style="display: none;" onchange="document.getElementById('file-name').innerText = this.files[0].name">
                <label for="ktp" class="file-button">+ Ganti File KTP</label>
                <span id="file-name" style="margin-left: 10px; font-size: 13px; color: #555;"></span>
            </div>

            <div style="margin-top: 20px; margin-bottom: 40px; display: flex; justify-content: space-between; align-items: center;">
                <button type="submit" class="btn-submit">Simpan & Kirim Ulang</button>
                <a href="{{ route('dashboard') }}" style="text-decoration: none; color: #70757a; font-size: 14px;">Batal</a>
            </div>
        </form>

        <footer>
            <p>&copy; {{ date('Y') }} Posbakum Aisyiyah Jakarta - Seluruh data dilindungi.</p>
        </footer>
    </div>

<script>
    const sktmCard = document.getElementById('sktm-card');
    const pembayaranCard = document.getElementById('pembayaran-card');
    const radioUmum = document.getElementById('radio-umum');
    const radioProdeo = document.getElementById('radio-prodeo');

    function handleKategoriChange() {
        if (radioProdeo.checked) {
            sktmCard.style.display = 'block';
            pembayaranCard.style.display = 'none';
        } else if (radioUmum.checked) {
            pembayaranCard.style.display = 'block';
            sktmCard.style.display = 'none';
        }
    }

    radioUmum.addEventListener('change', handleKategoriChange);
    radioProdeo.addEventListener('change', handleKategoriChange);
    document.addEventListener('DOMContentLoaded', handleKategoriChange);
</script>
</body>
</html>

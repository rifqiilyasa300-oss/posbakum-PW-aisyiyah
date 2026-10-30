<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMULIR E-KONSULTANSI - Posbakum Aisyiyah</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        /* CSS RESET & BASE */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #f0ebf8; font-family: 'Roboto', sans-serif; padding: 12px; color: #202124; }
        .container { max-width: 770px; margin: 0 auto; }
        .banner-container { width: 100%; min-height: 150px; background-color: #fff; border: 1px solid #dadce0; border-bottom: none; border-radius: 8px 8px 0 0; overflow: hidden; display: flex; align-items: center; justify-content: center; }
        .banner-container img { width: 100%; max-height: 250px; object-fit: contain; padding: 10px; }
        .card { background-color: #fff; border: 1px solid #dadce0; border-radius: 8px; padding: 24px; margin-bottom: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .title-card { border-top: 10px solid #15803d; border-radius: 0 0 8px 8px; }
        .title-card h1 { font-size: 28px; font-weight: 400; margin-bottom: 8px; color: #166534; }
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

        /* Tambahan sedikit styling untuk info pembayaran agar jelas */
        .info-pembayaran { background: #f8f9fa; border: 1px dashed #dadce0; padding: 15px; border-radius: 8px; margin-bottom: 15px; }
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

        <form action="{{ route('client.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card title-card">
                <h1>FORMULIR E-KONSULTANSI</h1>
                <p style="font-size: 14px; color: #6b7280;">Sistem Informasi Bantuan Hukum Posbakum Aisyiyah</p>
                <p style="color: #d93025; font-size: 14px; margin-top: 15px;">* Wajib diisi</p>
            </div>

            <div class="card">
                <label class="label-text">JENIS LAYANAN <span class="required">*</span></label>
                <div class="sub-label">Pilih GRATIS jika memiliki SKTM, atau BERBAYAR untuk umum.</div>
                <div class="radio-group">
                    <label class="radio-item">
                        <input type="radio" name="jenis_layanan" value="BERBAYAR" required id="radio-umum" {{ old('jenis_layanan') == 'BERBAYAR' ? 'checked' : '' }}>
                        <span>UMUM (BERBAYAR)</span>
                    </label>
                    <label class="radio-item">
                        <input type="radio" name="jenis_layanan" value="GRATIS" id="radio-prodeo" {{ old('jenis_layanan') == 'GRATIS' ? 'checked' : '' }}>
                        <span>PRODEO (GRATIS)</span>
                    </label>
                </div>
            </div>

            <!-- CARD PEMBAYARAN (Hanya Muncul jika BERBAYAR dipilih) -->
            <div class="card" id="pembayaran-card" style="display: none;">
                <label class="label-text">PEMBAYARAN ADMINISTRASI <span class="required">*</span></label>
                <div class="info-pembayaran">
                    <p style="font-size: 14px; margin-bottom: 10px;">Silakan melakukan transfer sebesar <strong>Rp 300.000</strong> ke rekening berikut:</p>
                    <p style="font-size: 15px; color: #166534;"><strong>Bank Rakyat Indonesia (BRI)</strong></p>
                    <p style="font-size: 18px; letter-spacing: 1px;"><strong>083601000042568</strong></p>
                    <p style="font-size: 14px;">A.n Yayasan Aisyiyah DKI Jakarta QQ Posbakum Aisyiyah DKI</p>
                </div>
                <div class="sub-label">Upload Bukti Transfer. Format PDF/JPG (Maks 2MB).</div>
                <input type="file" name="upload_bukti_bayar" id="bayar-input" style="display: none;" onchange="document.getElementById('bayar-name').innerText = this.files[0].name">
                <label for="bayar-input" class="file-button">+ Tambahkan Bukti Bayar</label>
                <span id="bayar-name" style="font-size: 12px; margin-left: 10px; color: #70757a;"></span>
            </div>

            <!-- CARD KHUSUS SKTM (Hanya Muncul jika GRATIS dipilih) -->
            <div class="card" id="sktm-card" style="display: none;">
                <label class="label-text">UPLOAD SKTM <span class="required">*</span></label>
                <div class="sub-label">Wajib bagi pemohon GRATIS. Format PDF/JPG (Maks 2MB).</div>
                <input type="file" name="upload_sktm" id="sktm-input" style="display: none;" onchange="document.getElementById('sktm-name').innerText = this.files[0].name">
                <label for="sktm-input" class="file-button">+ Tambahkan file SKTM</label>
                <span id="sktm-name" style="font-size: 12px; margin-left: 10px; color: #70757a;"></span>
            </div>

            <div class="card">

                <label class="label-text">ALAMAT EMAIL <span class="required">*</span></label>

                <div class="sub-label">Pastikan email aktif untuk menerima update perkembangan perkara</div>

                <input type="email" name="email" required placeholder="Jawaban Anda" class="input-line">

            </div>

            <div class="card">
                <label class="label-text">NAMA LENGKAP <span class="required">*</span></label>
                <div class="sub-label">Sesuai KTP</div>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', Auth::user()->name) }}" required placeholder="Jawaban Anda" class="input-line">
            </div>

            <div class="card">
                <label class="label-text">NIK <span class="required">*</span></label>
                <div class="sub-label">Masukkan 16 digit NIK</div>
                <input type="text" name="nik" value="{{ old('nik') }}" required maxlength="16" placeholder="Masukkan 16 digit NIK" class="input-line">
            </div>

            <div class="card">
                <label class="label-text">TEMPAT & TANGGAL LAHIR <span class="required">*</span></label>
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required placeholder="Contoh: Jakarta" class="input-line" style="width: 50%;">
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="input-line" style="width: 50%;">
                </div>
            </div>

            <div class="card">
                <label class="label-text">JENIS KELAMIN <span class="required">*</span></label>
                <div class="radio-group">
                    <label class="radio-item">
                        <input type="radio" name="jenis_kelamin" value="PEREMPUAN" required {{ old('jenis_kelamin') == 'PEREMPUAN' ? 'checked' : '' }}>
                        <span>PEREMPUAN</span>
                    </label>
                    <label class="radio-item">
                        <input type="radio" name="jenis_kelamin" value="LAKI-LAKI" {{ old('jenis_kelamin') == 'LAKI-LAKI' ? 'checked' : '' }}>
                        <span>LAKI-LAKI</span>
                    </label>
                </div>
            </div>

            <div class="card">
                <label class="label-text">ALAMAT LENGKAP (KTP) <span class="required">*</span></label>
                <input type="text" name="alamat_ktp" value="{{ old('alamat_ktp') }}" required placeholder="Jawaban Anda" class="input-line">
            </div>

            <div class="card">
                <label class="label-text">ALAMAT DOMISILI <span class="required">*</span></label>
                <input type="text" name="alamat_domisili" value="{{ old('alamat_domisili') }}" required placeholder="Jawaban Anda" class="input-line">
            </div>

            <div class="card">
                <label class="label-text">NOMOR HP/WHATSAPP <span class="required">*</span></label>
                <input type="text" name="nomor_hp" value="{{ old('nomor_hp') }}" required placeholder="Contoh: 0812xxxx" class="input-line">
            </div>

            <div class="card">
                <label class="label-text">STATUS PERKAWINAN <span class="required">*</span></label>
                <input type="text" name="status_perkawinan" value="{{ old('status_perkawinan') }}" required placeholder="Contoh: Kawin / Belum Kawin" class="input-line">
            </div>

            <div class="card">
                <label class="label-text">AGAMA <span class="required">*</span></label>
                <input type="text" name="agama" value="{{ old('agama') }}" required placeholder="Jawaban Anda" class="input-line">
            </div>

            <div class="card">
                <label class="label-text">PENDIDIKAN TERAKHIR <span class="required">*</span></label>
                <input type="text" name="pendidikan" value="{{ old('pendidikan') }}" required placeholder="Jawaban Anda" class="input-line">
            </div>

            <div class="card">
                <label class="label-text">PEKERJAAN <span class="required">*</span></label>
                <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}" required placeholder="Jawaban Anda" class="input-line">
            </div>

            <div class="card">
                <label class="label-text">URAIAN SINGKAT MASALAH <span class="required">*</span></label>
                <textarea name="uraian_masalah" required placeholder="Ceritakan masalah hukum Anda secara singkat..." class="input-line" style="height: 100px; resize: none;">{{ old('uraian_masalah') }}</textarea>
            </div>

            <div class="card">
                <label class="label-text">HARAPAN KLIEN <span class="required">*</span></label>
                <input type="text" name="harapan_klien" value="{{ old('harapan_klien') }}" required placeholder="Apa solusi yang Anda inginkan?" class="input-line">
            </div>

            <div class="card">
                <label class="label-text">UPLOAD KTP <span class="required">*</span></label>
                <div class="sub-label">Format PDF/JPG (Maks 2MB).</div>
                <input type="file" name="upload_ktp" id="ktp" style="display: none;" required onchange="document.getElementById('file-name').innerText = this.files[0].name">
                <label for="ktp" class="file-button">+ Tambahkan file KTP</label>
                <span id="file-name" style="font-size: 12px; margin-left: 10px; color: #70757a;"></span>
            </div>

            <div style="margin-top: 20px; margin-bottom: 40px; display: flex; justify-content: space-between;">
                <button type="submit" class="btn-submit">Kirim Pendaftaran</button>
                <a href="{{ route('dashboard') }}" style="text-decoration: none; color: #70757a; font-size: 14px; align-self: center;">Batal</a>
            </div>
        </form>

        <footer>
            <p>&copy; 2026 Posbakum Aisyiyah Jakarta - Seluruh data dilindungi.</p>
        </footer>
    </div>

    <script>
        const sktmCard = document.getElementById('sktm-card');
        const sktmInput = document.getElementById('sktm-input');
        const pembayaranCard = document.getElementById('pembayaran-card');
        const pembayaranInput = document.getElementById('bayar-input');

        const radioUmum = document.getElementById('radio-umum');
        const radioProdeo = document.getElementById('radio-prodeo');

        function handleKategoriChange() {
            if (radioProdeo.checked) {
                // Tampilkan SKTM, Sembunyikan Pembayaran
                sktmCard.style.display = 'block';
                sktmInput.required = true;

                pembayaranCard.style.display = 'none';
                pembayaranInput.required = false;
            } else if (radioUmum.checked) {
                // Tampilkan Pembayaran, Sembunyikan SKTM
                pembayaranCard.style.display = 'block';
                pembayaranInput.required = true;

                sktmCard.style.display = 'none';
                sktmInput.required = false;
            } else {
                sktmCard.style.display = 'none';
                pembayaranCard.style.display = 'none';
            }
        }

        radioUmum.addEventListener('change', handleKategoriChange);
        radioProdeo.addEventListener('change', handleKategoriChange);

        // Cek saat halaman dimuat (jika ada error validation agar input tetap sinkron)
        window.onload = handleKategoriChange;
    </script>
</body>
</html>

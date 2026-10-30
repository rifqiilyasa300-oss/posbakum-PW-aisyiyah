<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use App\Models\ClientDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NotifikasiAdmin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Menyimpan data pendaftaran klien baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'      => 'required|string|max:255',
            'nik'               => 'required|digits:16',
            'nomor_hp'          => 'required|string|max:20',
            'email'             => 'required|email|max:255',
            'tempat_lahir'      => 'required|string|max:100',
            'tanggal_lahir'     => 'required|date',
            'jenis_kelamin'     => 'required|in:LAKI-LAKI,PEREMPUAN',
            'alamat_ktp'        => 'required|string',
            'alamat_domisili'   => 'required|string',
            'status_perkawinan' => 'required|string|max:50',
            'agama'             => 'required|string|max:50',
            'pendidikan'        => 'required|string|max:100',
            'pekerjaan'         => 'required|string|max:100',
            'uraian_masalah'    => 'required|string',
            'harapan_klien'     => 'required|string',
            'jenis_layanan'     => 'required|in:BERBAYAR,GRATIS',
            'upload_ktp'        => 'required|file|mimes:jpeg,png,jpg,pdf|max:10240',
            'upload_sktm'       => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
            'upload_bukti_bayar'=> 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
        ]);

        // ✅ Validasi tambahan sesuai jenis layanan
        if ($request->jenis_layanan === 'GRATIS' && empty($request->upload_sktm)) {
            return back()->withInput()->with('error', 'Layanan GRATIS memerlukan upload SKTM.');
        }
        if ($request->jenis_layanan === 'BERBAYAR' && empty($request->upload_bukti_bayar)) {
            return back()->withInput()->with('error', 'Layanan BERBAYAR memerlukan upload bukti pembayaran.');
        }

        try {
            $ktp_name = null;
            if ($request->hasFile('upload_ktp')) {
                $fileKtp = $request->file('upload_ktp');
                $ktp_name = time() . '_KTP_' . Str::slug($request->nama_lengkap) . '.' . $fileKtp->getClientOriginalExtension();
                $fileKtp->storeAs('ktp', $ktp_name, 'public');
            }

            $sktm_name = null;
            if ($request->hasFile('upload_sktm')) {
                $fileSktm = $request->file('upload_sktm');
                $sktm_name = time() . '_SKTM_' . Str::slug($request->nama_lengkap) . '.' . $fileSktm->getClientOriginalExtension();
                $fileSktm->storeAs('sktm', $sktm_name, 'public');
            }

            $bukti_bayar_name = null;
            if ($request->hasFile('upload_bukti_bayar')) {
                $fileBayar = $request->file('upload_bukti_bayar');
                $bukti_bayar_name = time() . '_PAY_' . Str::slug($request->nama_lengkap) . '.' . $fileBayar->getClientOriginalExtension();
                $fileBayar->storeAs('pembayaran', $bukti_bayar_name, 'public');
            }

            $client = Client::create([
                'user_id'              => Auth::id(),
                'reg_code'             => 'PBK-' . date('Y') . '-' . strtoupper(Str::random(5)),
                'nama'                 => $request->nama_lengkap,
                'nik'                  => $request->nik,
                'email'                => $request->email,
                'phone'                => $request->nomor_hp,
                'tempat_lahir'         => $request->tempat_lahir,
                'tanggal_lahir'        => $request->tanggal_lahir,
                'jenis_kelamin'        => $request->jenis_kelamin,
                'alamat_ktp'           => $request->alamat_ktp,
                'alamat_domisili'      => $request->alamat_domisili,
                'status_perkawinan'    => $request->status_perkawinan,
                'agama'                => $request->agama,
                'pendidikan'           => $request->pendidikan,
                'pekerjaan'            => $request->pekerjaan,
                'uraian_masalah'       => $request->uraian_masalah,
                'harapan_klien'        => $request->harapan_klien,
                'kategori_layanan'     => $request->jenis_layanan == 'GRATIS' ? 'PRODEO' : 'UMUM',
                'ktp_path'             => $ktp_name,
                'sktm_path'            => $sktm_name,
                'bukti_bayar_path'     => $bukti_bayar_name,
                'status'               => 'Menunggu Verifikasi',
                'tahapan'              => 'Verifikasi Berkas',
                'tujuan_rujuk'         => null,
                'alamat_tujuan'        => null,
            ]);

            try {
                $nomorAdmin = '6281315783606';
                if (substr($nomorAdmin, 0, 1) == '0') {
                    $nomorAdmin = '62' . substr($nomorAdmin, 1);
                }

                $pesanAdmin = "📋 PENDAFTARAN BARU\n\nNama: {$client->nama}\nKode: {$client->reg_code}\nStatus: Menunggu Verifikasi\n\nMohon segera diperiksa.";
                WhatsAppService::send($nomorAdmin, $pesanAdmin);

                $admins = User::where('role', 'admin')->get();
                $pesanLonceng = "Pendaftaran baru: " . $client->nama;
                $urlLonceng = route('admin.clients.show', $client->id);
                Notification::send($admins, new NotifikasiAdmin($pesanLonceng, $urlLonceng));
            } catch (\Exception $eNotif) {
                Log::error("Notifikasi ke admin gagal: " . $eNotif->getMessage());
            }

            return redirect()->route('dashboard')->with('success', 'Pendaftaran berhasil dikirim!');

        } catch (\Exception $e) {
            Log::error("Pendaftaran Gagal: " . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal mengirim pendaftaran. Silakan coba lagi.');
        }
    }

public function tracking()
{
    $activePerkara = Client::where('user_id', Auth::id())
        ->whereNotIn('status', ['Ditolak', 'Tidak Lanjut', 'KASUS SELESAI (ARSIP)', 'Selesai', 'Rujuk'])
        ->latest()
        ->first();

    $alurAcuan = [
        'Menunggu Verifikasi',
        'Pendaftaran Diterima',
        'Perlu Perbaikan',
        'PENINJAUAN BERKAS',
        'Konsultasi Offline',
        'Proses',
        'Tunjuk Advokat',
        'KONFIRMASI KASUS (TLP/OFFLINE)',
        'MEDIASI (NON-LITIGASI)',
        'PENDAFTARAN E-COURT (LITIGASI)',
        'PROSES PERSIDANGAN',
        'TERTUNDA',
        'KASUS SELESAI (ARSIP)'
    ];

    $langkahAktif = 'Menunggu Verifikasi';

    if ($activePerkara) {
        // ✅ UTAMA: Baca dari kolom STATUS dulu
        $dbStatus = trim($activePerkara->status ?? '');
        $dbTahapan = trim($activePerkara->tahapan ?? '');

        $petaStatus = [
            'menunggu verifikasi'       => 'Menunggu Verifikasi',
            'pendaftaran diterima'      => 'Pendaftaran Diterima',
            'perlu perbaikan'           => 'Perlu Perbaikan',
            'revisi'                    => 'Perlu Perbaikan',
            'peninjauan berkas'         => 'PENINJAUAN BERKAS',
            'konsultasi offline'        => 'Konsultasi Offline',
            'proses'                    => 'Proses',
            'tunjuk advokat'            => 'Tunjuk Advokat',
            'ditangani advokat'         => 'Tunjuk Advokat',
            'konfirmasi kasus'          => 'KONFIRMASI KASUS (TLP/OFFLINE)',
            'mediasi'                   => 'MEDIASI (NON-LITIGASI)',
            'pendaftaran e-court'       => 'PENDAFTARAN E-COURT (LITIGASI)',
            'proses persidangan'        => 'PROSES PERSIDANGAN',
            'tertunda'                  => 'TERTUNDA',
            'selesai'                   => 'KASUS SELESAI (ARSIP)'
        ];

        if (!empty($dbStatus)) {
            $kunci = strtolower($dbStatus);
            $langkahAktif = $petaStatus[$kunci] ?? ucwords($dbStatus);
        } elseif (!empty($dbTahapan)) {
            $kunci = strtolower($dbTahapan);
            $langkahAktif = $petaStatus[$kunci] ?? ucwords($dbTahapan);
        }

        if (!in_array($langkahAktif, $alurAcuan)) {
            $langkahAktif = 'Menunggu Verifikasi';
        }
    }

    $indexAktif = array_search($langkahAktif, $alurAcuan);
    if ($indexAktif === false) $indexAktif = 0;

    $daftarYangDitampilkan = array_slice($alurAcuan, 0, $indexAktif + 1);

    $riwayatPerkara = Client::where('user_id', Auth::id())
        ->whereIn('status', ['Ditolak', 'Tidak Lanjut', 'KASUS SELESAI (ARSIP)', 'Selesai', 'Rujuk'])
        ->latest()
        ->get();

    return view('dashboard', compact('activePerkara', 'daftarYangDitampilkan', 'langkahAktif', 'riwayatPerkara'));
}
    public function show($id)
    {
        // ✅ Akses sesuai peran: Admin bebas, Advokat hanya kasusnya, Klien hanya miliknya
        $perkara = Client::where('id', $id)
            ->when(Auth::user()->role === 'klien', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->when(Auth::user()->role === 'advokat', function($q) {
                $q->where('advokat_id', Auth::id());
            })
            ->firstOrFail();

        return view('clients.show', compact('perkara'));
    }

public function edit($id)
{
    $client = Client::where('user_id', Auth::id())->findOrFail($id);

    // Bersihkan spasi & ubah jadi huruf kecil agar cocok persis
    $statusBersih = trim(strtolower($client->status));

    // ✅ DIPERBAIKI: Hanya izinkan revisi untuk status yang sesuai
    if (!in_array($statusBersih, ['perlu perbaikan', 'verifikasi ulang dokumen'])) {
        return redirect()->route('dashboard')->with('error', 'Perkara tidak bisa diedit pada tahap ini.');
    }

    return view('clients.edit', compact('client'));
}

public function update(Request $request, $id)
{
    $client = Client::where('user_id', Auth::id())->findOrFail($id);

    // ✅ Tambahkan validasi untuk email dan nomor_hp
    $request->validate([
        'nama'                  => 'required|string|max:255',
        'nik'                   => 'required|digits:16',
        'nomor_hp'              => 'required|string|max:20', // ✅ Ditambahkan
        'email'                 => 'required|email|max:255', // ✅ Ditambahkan
        'tempat_lahir'          => 'required|string|max:100',
        'tanggal_lahir'         => 'required|date',
        'jenis_kelamin'         => 'required|in:LAKI-LAKI,PEREMPUAN',
        'alamat_ktp'            => 'required|string',
        'alamat_domisili'       => 'required|string',
        'status_perkawinan'     => 'required|string|max:50',
        'agama'                 => 'required|string|max:50',
        'pendidikan'            => 'required|string|max:100',
        'pekerjaan'             => 'required|string|max:100',
        'uraian_masalah'        => 'required|string',
        'harapan_klien'         => 'required|string',
        'upload_ktp'            => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
        'upload_sktm'           => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
        'upload_bukti_bayar'    => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
    ]);

    $dataToUpdate = [
        'nama'              => $request->nama,
        'nik'               => $request->nik,
        'phone'             => $request->nomor_hp, // ✅ Ditambahkan (sesuai nama kolom di tabel)
        'email'             => $request->email,     // ✅ Ditambahkan
        'tempat_lahir'      => $request->tempat_lahir,
        'tanggal_lahir'     => $request->tanggal_lahir,
        'jenis_kelamin'     => $request->jenis_kelamin,
        'alamat_ktp'        => $request->alamat_ktp,
        'alamat_domisili'   => $request->alamat_domisili,
        'status_perkawinan' => $request->status_perkawinan,
        'agama'             => $request->agama,
        'pendidikan'        => $request->pendidikan,
        'pekerjaan'         => $request->pekerjaan,
        'uraian_masalah'    => $request->uraian_masalah,
        'harapan_klien'     => $request->harapan_klien,
        'status'            => 'Menunggu Verifikasi',
        'catatan_admin'     => null,
        'tahapan'           => 'Verifikasi Berkas',
        'updated_by'        => Auth::id(),
    ];

    if ($request->hasFile('upload_ktp')) {
        if ($client->ktp_path) Storage::disk('public')->delete('ktp/' . $client->ktp_path);
        $fileKtp = $request->file('upload_ktp');
        $ktp_name = time() . '_KTP_' . Str::slug($client->nama) . '.' . $fileKtp->getClientOriginalExtension();
        $fileKtp->storeAs('ktp', $ktp_name, 'public');
        $dataToUpdate['ktp_path'] = $ktp_name;
    }

    if ($request->hasFile('upload_sktm')) {
        if ($client->sktm_path) Storage::disk('public')->delete('sktm/' . $client->sktm_path);
        $fileSktm = $request->file('upload_sktm');
        $sktm_name = time() . '_SKTM_' . Str::slug($client->nama) . '.' . $fileSktm->getClientOriginalExtension();
        $fileSktm->storeAs('sktm', $sktm_name, 'public');
        $dataToUpdate['sktm_path'] = $sktm_name;
    }

    if ($request->hasFile('upload_bukti_bayar')) {
        if ($client->bukti_bayar_path) Storage::disk('public')->delete('pembayaran/' . $client->bukti_bayar_path);
        $fileBayar = $request->file('upload_bukti_bayar');
        $bayar_name = time() . '_PAY_' . Str::slug($client->nama) . '.' . $fileBayar->getClientOriginalExtension();
        $fileBayar->storeAs('pembayaran', $bayar_name, 'public');
        $dataToUpdate['bukti_bayar_path'] = $bayar_name;
    }

    $client->update($dataToUpdate);

    // ✅ NOTIFIKASI SETELAH KLIEN MENGIRIM REVISI
    try {
        $nomorAdmin = '6281315783606';
        $pesanWA = "🔄 PENGAJUAN DIREVISI\n\n"
                 . "Nama: {$client->nama}\n"
                 . "Kode: {$client->reg_code}\n"
                 . "Status: Dikirim ulang untuk diverifikasi\n\n"
                 . "Silakan periksa kembali berkasnya.";
        WhatsAppService::send($nomorAdmin, $pesanWA);

        $admins = User::where('role', 'admin')->get();
        $pesanLonceng = "Pengajuan direvisi: {$client->nama}";
        $urlLonceng = route('admin.clients.show', $client->id);
        Notification::send($admins, new NotifikasiAdmin($pesanLonceng, $urlLonceng));

        Mail::raw($pesanWA, function ($mail) use ($client) {
            $mail->to('admin@posbakum-aisyiyah.or.id')
                 ->subject("Pengajuan Direvisi - {$client->reg_code}");
        });

    } catch (\Exception $e) {
        Log::error("Notifikasi revisi gagal dikirim: " . $e->getMessage());
    }

    return redirect()->route('dashboard')->with('success', 'Data berhasil diperbaiki dan dikirim untuk diverifikasi kembali!');
}

    /**
     * Upload dokumen tambahan (Admin & Advokat)
     */
    public function uploadAdditional(Request $request, $id)
    {
        $request->validate([
            'additional_docs'      => 'required|array',
            'additional_docs.*'    => 'file|mimes:jpeg,png,jpg,pdf,doc,docx|max:10240',
            'doc_names'            => 'required|array',
        ]);

        $client = Client::findOrFail($id);

        // ✅ Cek hak akses sebelum bisa upload
        if (Auth::user()->role === 'advokat' && $client->advokat_id != Auth::id()) {
            abort(403, 'Anda tidak berhak mengunggah dokumen untuk kasus ini.');
        }

        if ($request->hasFile('additional_docs')) {
            $files = $request->file('additional_docs');
            $names = $request->input('doc_names');

            foreach ($files as $index => $file) {
                $originalName = $file->getClientOriginalName();
                $customName = !empty($names[$index]) ? $names[$index] : $originalName;
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '_' . Str::random(5) . '.' . $extension;

                $file->storeAs('tambahan', $fileName, 'public');

                ClientDocument::create([
                    'client_id' => $id,
                    'file_path' => $fileName,
                    'file_name' => $customName,
                    'uploaded_by' => Auth::id() // ✅ Tambahan: catat siapa yang mengunggah
                ]);
            }
        }

        return redirect()->back()->with('success', 'Dokumen berhasil diunggah.');
    }

    public function cetakSuratRujukan($id)
    {
        $client = Client::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->where('status', 'Rujuk')
                        ->firstOrFail();

        return view('admin.surat_rujukan', compact('client'));
    }

    /**
     * Halaman Galeri Dokumen
     */
    public function showDocuments($id)
    {
        // ✅ Cek hak akses sama seperti fungsi show()
        $client = Client::where('id', $id)
            ->when(Auth::user()->role === 'klien', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->when(Auth::user()->role === 'advokat', function($q) {
                $q->where('advokat_id', Auth::id());
            })
            ->with('additional_documents') // ✅ Ambil data dokumen tambahan sekaligus
            ->firstOrFail();

        return view('admin.documents_view', compact('client'));
    }

    /**
     * Tampilkan file dokumen
     */
    public function viewFile($id, $type)
    {
        // ✅ Cek akses dulu sebelum buka file
        $client = Client::where('id', $id)
            ->when(Auth::user()->role === 'klien', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->when(Auth::user()->role === 'advokat', function($q) {
                $q->where('advokat_id', Auth::id());
            })
            ->firstOrFail();

        $path = '';

        switch ($type) {
            case 'ktp':
                $path = 'ktp/' . $client->ktp_path;
                break;
            case 'sktm':
                $path = 'sktm/' . $client->sktm_path;
                break;
            case 'pembayaran':
                $path = 'pembayaran/' . $client->bukti_bayar_path;
                break;
            case 'tambahan':
                $docId = request('doc_id');
                $doc = ClientDocument::where('id', $docId)
                                     ->where('client_id', $id)
                                     ->firstOrFail();
                $path = 'tambahan/' . $doc->file_path;
                break;
            default:
                abort(404, 'Jenis dokumen tidak ditemukan.');
        }

        if (empty($path) || !Storage::disk('public')->exists($path)) {
            abort(404, 'Berkas tidak ditemukan di server.');
        }

        return response()->file(Storage::disk('public')->path($path));
    }

    /**
     * ✅ Fungsi untuk unggah dokumen Putusan
     */
    public function uploadPutusan(Request $request, $id)
    {
        $request->validate([
            'file_putusan' => 'required|file|mimes:pdf|max:15360', // Maks 15MB
        ]);

        $client = Client::findOrFail($id);

        // Cek akses
        if (Auth::user()->role === 'advokat' && $client->advokat_id != Auth::id()) {
            abort(403, 'Tidak diizinkan mengunggah dokumen ini.');
        }

        // Hapus file lama jika ada
        if ($client->file_putusan) {
            Storage::disk('public')->delete('putusan/' . $client->file_putusan);
        }

        $file = $request->file('file_putusan');
        $fileName = 'PUTUSAN_' . $client->reg_code . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('putusan', $fileName, 'public');

        $client->update([
            'file_putusan' => $fileName,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Dokumen putusan berhasil disimpan.');
    }

    // ==================================================
    // ✅ FUNGSI BARU: Atur Jadwal Konsultasi
    // ==================================================
public function aturJadwal(Request $request, $id)
{
    $client = Client::findOrFail($id);

    // Pengecekan hak akses
    if (Auth::user()->role === 'advokat' && $client->advokat_id != Auth::id()) {
        abort(403, 'Anda tidak berhak mengatur jadwal kasus ini.');
    }

    $bolehTahapan = [
        'KONFIRMASI KASUS (TLP/OFFLINE)',
        'MEDIASI (NON-LITIGASI)',
        'PENDAFTARAN E-COURT (LITIGASI)',
        'PROSES PERSIDANGAN'
    ];

    if (!in_array(trim($client->tahapan), $bolehTahapan)) {
        abort(403, 'Kasus belum dikonfirmasi, tidak bisa mengatur jadwal.');
    }

    // Validasi input
    $request->validate([
        'jadwal_tanggal' => 'nullable|date|after_or_equal:today',
        'jadwal_jam'     => 'nullable|date_format:H:i',
        'catatan_jadwal' => 'nullable|string|max:500',
    ]);

    // Gabungkan tanggal dan jam
    $jadwal = null;
    if ($request->filled('jadwal_tanggal') && $request->filled('jadwal_jam')) {
        $jadwal = $request->jadwal_tanggal . ' ' . $request->jadwal_jam;
    }

    // Cegah isi salah satu saja
    if (($request->filled('jadwal_tanggal') && !$request->filled('jadwal_jam')) ||
        (!$request->filled('jadwal_tanggal') && $request->filled('jadwal_jam'))) {
        return redirect()->back()->with('warning', 'Tanggal dan jam harus diisi keduanya atau dikosongkan keduanya.');
    }

    // Simpan data
    $client->update([
        'jadwal_konsultasi' => $jadwal,
        'catatan_admin'     => $request->catatan_jadwal ?? $client->catatan_admin,
        'updated_at'        => now()
    ]);

    // Ambil data terbaru dari database
    $clientTerbaru = Client::findOrFail($id);

    // Kirim notifikasi jika ada jadwal
    if ($jadwal) {
        $tanggalFormatted = \Carbon\Carbon::parse($jadwal)->translatedFormat('l, d F Y');
        $jamFormatted = \Carbon\Carbon::parse($jadwal)->format('H:i');

        $isiCatatan = !empty($clientTerbaru->catatan_admin)
            ? "\n📝 Keterangan: {$clientTerbaru->catatan_admin}"
            : "\n📝 Keterangan: Mohon hadir tepat waktu dan bawa dokumen asli.";

        // --- Notifikasi WhatsApp ---
        $waNumber = preg_replace('/[^0-9]/', '', $clientTerbaru->phone);
        if (!empty($waNumber)) {
            if (str_starts_with($waNumber, '0')) {
                $waNumber = '62' . substr($waNumber, 1);
            } elseif (!str_starts_with($waNumber, '62')) {
                $waNumber = '62' . $waNumber;
            }

            // ✅ PESAN WA SUDAH SESUAI FORMAT YANG KAMU MAU
            $pesanWA = rawurlencode(
                "Halo Bapak/Ibu *{$clientTerbaru->nama}*%0A%0A" .
                "Pendaftaran Anda telah disetujui.%0A" .
                "Jadwal konsultasi tatap muka Anda di Posbakum:%0A" .
                "📅 Hari/Tanggal: {$tanggalFormatted}%0A" .
                "⏰ Pukul: {$jamFormatted} WIB%0A" .
                "📍 Tempat: Kantor Posbakum PW ‘Aisyiyah DKI Jakarta%0A" .
                "Jl. Tebet Timur Dalam No. 4, Jakarta Selatan%0A" .
                "{$isiCatatan}%0A%0A" .
                "Terima kasih."
            );

            $linkWA = "https://wa.me/{$waNumber}?text={$pesanWA}";
        }

        // --- Notifikasi Email ---
        if (!empty($clientTerbaru->email)) {
            try {
                \Illuminate\Support\Facades\Mail::raw(
                    "Halo {$clientTerbaru->nama},\n\n" .
                    "Pendaftaran Anda telah disetujui.\n" .
                    "Jadwal konsultasi tatap muka Anda di Posbakum:\n" .
                    "📅 Hari/Tanggal: {$tanggalFormatted}\n" .
                    "⏰ Pukul: {$jamFormatted} WIB\n" .
                    "📍 Tempat: Kantor Posbakum PW ‘Aisyiyah DKI Jakarta\n" .
                    "Jl. Tebet Timur Dalam No. 4, Jakarta Selatan\n" .
                    "{$isiCatatan}\n\n" .
                    "Terima kasih,\nTim Posbakum PW ‘Aisyiyah DKI Jakarta",
                    function ($message) use ($clientTerbaru) {
                        $message->to($clientTerbaru->email)
                                ->subject("Jadwal Konsultasi Hukum Anda");
                    }
                );
            } catch (\Exception $e) {
                Log::warning('Gagal kirim email jadwal: ' . $e->getMessage());
            }
        }

        session()->flash('info', 'Jadwal & catatan disimpan, notifikasi terkirim lengkap!');
    } else {
        session()->flash('info', 'Jadwal berhasil dikosongkan.');
    }

    return redirect()->back()->with('success', 'Data jadwal diperbarui!');
}
}

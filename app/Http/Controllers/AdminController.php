<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use App\Models\Gallery;
use App\Models\Income;
use App\Services\WhatsAppService;
use App\Notifications\NotifikasiAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Total Klien tetap dihitung semua
        $totalKlien = Client::count();

        // 2. Revisi: 'Ditolak' dikeluarkan dari status yang perlu diverifikasi
        $statusVerifikasi = ['Menunggu Verifikasi', 'Pendaftaran Diterima', 'Perlu Perbaikan'];
        $perluVerifikasi = Client::whereIn('status', $statusVerifikasi)->count();

        // 3. Kasus Diproses (sama, tidak perlu ditolak)
        $kasusDiproses = Client::whereNotIn('status', ['Menunggu Verifikasi', 'Pendaftaran Diterima', 'Selesai', 'Ditolak', 'Perlu Perbaikan', 'Rujuk', 'Tidak Lanjut'])->count();

        $totalPemasukan = Income::sum('amount');

        // 4. REVISI UTAMA: 'Ditolak' tidak akan muncul di tabel daftar pendaftaran klien
        $clients = Client::whereIn('status', $statusVerifikasi)
                        ->latest()
                        ->get();

        return view('admin.dashboard', compact('clients', 'totalKlien', 'perluVerifikasi', 'kasusDiproses', 'totalPemasukan'));
    }

    public function arsipIndex()
    {
        $clients = Client::whereIn('status', [
                'Selesai',
                'KASUS SELESAI (ARSIP)',  // ✅ Menggunakan nilai persis dari database
                'Ditolak',
                'Ditolak / Ditutup',
                'Rujuk',
                'Tidak Lanjut'
            ])
            ->latest()
            ->get();

        return view('admin.arsip', compact('clients'));
    }

    public function indexIncomes()
    {
        $incomes = Income::latest()->get();
        $totalPemasukan = Income::sum('amount');
        return view('admin.incomes.index', compact('incomes', 'totalPemasukan'));
    }

    public function kelolaKasus()
    {
        // Hanya ambil kasus yang sudah diverifikasi (status 'Proses' ke atas)
        $clients = Client::whereIn('status', [
                        'Proses',
                        'Tunjuk Advokat',
                        'Konsultasi Offline',
                        'PENINJAUAN BERKAS'
                    ])
                    ->latest()
                    ->get();

        return view('admin.kelola', compact('clients'));
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        $advokats = User::where('role', 'advokat')
            ->withCount(['cases' => function ($query) {
                // Hanya hitung kasus yang MASIH dalam proses/aktif
                $query->whereIn('status', [
                    'Tunjuk Advokat',
                    'Konfirmasi Kasus',
                    'Mediasi',
                    'Pendaftaran E-Court',
                    'Proses Persidangan',
                    'Proses'
                ]);
            }])
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.show', compact('client', 'advokats'));
    }

    // Menampilkan daftar staff
    public function indexStaff()
    {
        $staffs = User::whereIn('role', ['admin', 'advokat'])->orderBy('role', 'asc')->get();
        return view('admin.users.index', compact('staffs'));
    }

    // Menampilkan form tambah staff
    public function createStaff()
    {
        return view('admin.users.create');
    }

    // Menampilkan form edit staff
    public function editStaff($id)
    {
        $staff = User::findOrFail($id);
        return view('admin.users.edit', compact('staff'));
    }

    // Menyimpan data staff baru
    public function storeStaff(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6|confirmed',
            'role'      => 'required|in:admin,advokat',
            'phone'     => 'required|string|max:20',
            'keahlian'  => 'nullable|string',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'phone'     => $request->phone,
            'keahlian'  => $request->keahlian,
        ]);

        $msg = "Halo {$user->name}, akun {$user->role} Anda di Posbakum telah aktif.\n\n" .
               "Email: {$user->email}\nPassword: {$request->password}";

        // Kirim WA dengan normalisasi nomor
        if (!empty($user->phone)) {
            try {
                $phoneClean = preg_replace('/[^0-9]/', '', $user->phone);
                $waTarget = str_starts_with($phoneClean, '0') ? '62' . substr($phoneClean, 1) : (str_starts_with($phoneClean, '62') ? $phoneClean : '62' . $phoneClean);
                WhatsAppService::send($waTarget, $msg);
            } catch (\Exception $e) {
                Log::error("Gagal kirim WA: " . $e->getMessage());
            }
        }

        // Kirim Email
        try {
            Mail::raw($msg, function($message) use ($user) {
                $message->to($user->email)->subject('Akun Staff POSBAKUM Aktif');
            });
        } catch (\Exception $e) {
            Log::error("Gagal kirim email: " . $e->getMessage());
        }

        return redirect()->route('admin.staff.index')->with('success', 'Staff/Advokat berhasil ditambahkan.');
    }

    // Mengupdate data staff
    public function updateStaff(Request $request, $id)
    {
        $staff = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$staff->id,
            'role'  => 'required|in:admin,advokat',
            'phone' => 'required|string',
        ]);

        $data = $request->only(['name', 'email', 'role', 'phone', 'keahlian']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $staff->update($data);

        return redirect()->route('admin.staff.index')->with('success', 'Data staff berhasil diperbarui.');
    }

    // Menghapus data staff
    public function destroyStaff($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }
        $user->delete();
        return redirect()->back()->with('success', 'Akun staff berhasil dihapus.');
    }

    public function uploadPutusan(Request $request, $id)
    {
        $request->validate(['file_putusan' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120']);
        $client = Client::findOrFail($id);
        if ($request->hasFile('file_putusan')) {
            if ($client->file_putusan) Storage::disk('public')->delete($client->file_putusan);
            $path = $request->file('file_putusan')->store('putusan', 'public');
            $client->update(['file_putusan' => $path, 'updated_by' => Auth::id()]);
        }
        return redirect()->back()->with('success', 'Dokumen putusan berhasil diunggah!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'jadwal_konsultasi' => 'required_if:status,Konsultasi Offline|nullable|date',
            'catatan_admin' => 'nullable|string'
        ]);

        $client = Client::findOrFail($id); // ✅ Perbaikan: Menggunakan short-import namespace

        $catatanBaru = "Status: {$request->status}";
        if (!empty($request->catatan_admin)) {
            $catatanBaru .= " - Keterangan: {$request->catatan_admin}";
        }

        // Update database
        $client->update([
            'status' => $request->status,
            'catatan_admin' => $catatanBaru,
            'jadwal_konsultasi' => $request->jadwal_konsultasi,
        ]);

        // Kirim notifikasi ke Advokat jika sudah ditunjuk
        if (!empty($client->advokat_id)) {
            try {
                $advokat = User::find($client->advokat_id);
                if ($advokat) {
                    $keterangan = !empty($request->catatan_admin) ? $request->catatan_admin : '-';
                    $pesan = "🔄 Perubahan Status Kasus #{$client->reg_code}\nNama Klien: {$client->nama}\nStatus baru: {$request->status}\nKeterangan: {$keterangan}";
                    $url = route('advokat.clients.show', $client->id);

                    // Notifikasi lonceng
                    $advokat->notify(new NotifikasiAdmin($pesan, $url));

                    // Kirim WA ke Advokat (dengan normalisasi format nomor nomor telepon)
                    if (!empty($advokat->phone)) {
                        $phoneClean = preg_replace('/[^0-9]/', '', $advokat->phone);
                        $waAdvokat = str_starts_with($phoneClean, '0') ? '62' . substr($phoneClean, 1) : (str_starts_with($phoneClean, '62') ? $phoneClean : '62' . $phoneClean);
                        WhatsAppService::send($waAdvokat, $pesan);
                    }
                }
            } catch (\Exception $e) {
                Log::error("Notifikasi ke Advokat (updateStatus): " . $e->getMessage());
            }
        }

        // JIKA STATUS BERUBAH KE 'Konsultasi Offline', KIRIM NOTIFIKASI OTOMATIS KE KLIEN
        if ($request->status == 'Konsultasi Offline' && !empty($request->jadwal_konsultasi)) {
            $tanggalFormatted = Carbon::parse($request->jadwal_konsultasi)->translatedFormat('l, d F Y');
            $jamFormatted = Carbon::parse($request->jadwal_konsultasi)->format('H:i');

            $isiCatatan = !empty($request->catatan_admin)
                ? "\n📝 Keterangan: {$request->catatan_admin}"
                : "\n📝 Keterangan: Mohon hadir tepat waktu dan bawa dokumen asli.";

            $pesan = "Halo Bapak/Ibu {$client->nama},\n\n" .
                     "Pendaftaran Anda telah disetujui.\n" .
                     "Jadwal konsultasi tatap muka Anda di Posbakum:\n" .
                     "📅 Hari/Tanggal: {$tanggalFormatted}\n" .
                     "⏰ Pukul: {$jamFormatted} WIB\n" .
                     "📍 Tempat: Kantor Posbakum PW ‘Aisyiyah DKI Jakarta\n" .
                     "Jl. Tebet Timur Dalam No. 4, Jakarta Selatan\n" .
                     "{$isiCatatan}\n\n" .
                     "Terima kasih.";

            // Kirim WA Klien dengan normalisasi nomor
            try {
                if (!empty($client->phone)) {
                    $phoneClientClean = preg_replace('/[^0-9]/', '', $client->phone);
                    $waKlien = str_starts_with($phoneClientClean, '0') ? '62' . substr($phoneClientClean, 1) : (str_starts_with($phoneClientClean, '62') ? $phoneClientClean : '62' . $phoneClientClean);
                    WhatsAppService::send($waKlien, $pesan);
                }
            } catch (\Exception $e) {
                Log::error("Gagal kirim WA saat updateStatus: " . $e->getMessage());
            }

            // Kirim Email
            try {
                Mail::raw($pesan, function($message) use ($client) {
                    $message->to($client->email)->subject('Jadwal Konsultasi POSBAKUM');
                });
            } catch (\Exception $e) {
                Log::error("Gagal kirim Email saat updateStatus: " . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Perkara berhasil diperbarui & notifikasi telah dikirim.');
    }

    public function setKonsultasi(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $request->validate([
            'jadwal_konsultasi' => 'required|date',
        ]);

        $client->update([
            'status' => 'Konsultasi Offline',
            'jadwal_konsultasi' => $request->jadwal_konsultasi,
            'tahapan' => 'Menunggu Kedatangan Klien',
            'catatan_admin' => "Status: Konsultasi Offline - Jadwal: " . Carbon::parse($request->jadwal_konsultasi)->format('d/m/Y H:i')
        ]);

        // Notifikasi ke Advokat jika sudah ada
        if (!empty($client->advokat_id)) {
            try {
                $advokat = User::find($client->advokat_id);
                if ($advokat) {
                    $jadwal = Carbon::parse($request->jadwal_konsultasi)->format('d/m/Y H:i');
                    $pesan = "📅 Jadwal Konsultasi Diatur\nKasus #{$client->reg_code}: {$client->nama}\nTanggal: {$jadwal}";
                    $url = route('advokat.clients.show', $client->id);

                    $advokat->notify(new NotifikasiAdmin($pesan, $url));

                    if (!empty($advokat->phone)) {
                        $phoneClean = preg_replace('/[^0-9]/', '', $advokat->phone);
                        $waAdvokat = str_starts_with($phoneClean, '0') ? '62' . substr($phoneClean, 1) : (str_starts_with($phoneClean, '62') ? $phoneClean : '62' . $phoneClean);
                        WhatsAppService::send($waAdvokat, $pesan);
                    }
                }
            } catch (\Exception $e) {
                Log::error("Notifikasi ke Advokat (setKonsultasi): " . $e->getMessage());
            }
        }

        $tanggalFormatted = Carbon::parse($request->jadwal_konsultasi)->translatedFormat('l, d F Y');
        $jamFormatted = Carbon::parse($request->jadwal_konsultasi)->format('H:i');

        $isiCatatan = !empty($client->catatan_admin)
            ? "\n📝 Keterangan: {$client->catatan_admin}"
            : "\n📝 Keterangan: Mohon hadir tepat waktu dan bawa dokumen asli.";

        $pesan = "Halo Bapak/Ibu {$client->nama},\n\n" .
                 "Pendaftaran Anda telah disetujui.\n" .
                 "Jadwal konsultasi tatap muka Anda di Posbakum:\n" .
                 "📅 Hari/Tanggal: {$tanggalFormatted}\n" .
                 "⏰ Pukul: {$jamFormatted} WIB\n" .
                 "📍 Tempat: Kantor Posbakum PW ‘Aisyiyah DKI Jakarta\n" .
                 "Jl. Tebet Timur Dalam No. 4, Jakarta Selatan\n" .
                 "{$isiCatatan}\n\n" .
                 "Terima kasih.";

        // Kirim WA Klien dengan normalisasi nomor
        try {
            if (!empty($client->phone)) {
                $phoneClientClean = preg_replace('/[^0-9]/', '', $client->phone);
                $waKlien = str_starts_with($phoneClientClean, '0') ? '62' . substr($phoneClientClean, 1) : (str_starts_with($phoneClientClean, '62') ? $phoneClientClean : '62' . $phoneClientClean);
                WhatsAppService::send($waKlien, $pesan);
            }
        } catch (\Exception $e) {
            Log::error("Gagal kirim WA saat setKonsultasi: " . $e->getMessage());
        }

        // Kirim Email
        try {
            Mail::raw($pesan, function($message) use ($client) {
                $message->to($client->email)->subject('Jadwal Konsultasi Offline POSBAKUM');
            });
        } catch (\Exception $e) {
            Log::error("Gagal kirim Email saat setKonsultasi: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Jadwal konsultasi berhasil diatur & notifikasi (WA+Email) telah dikirim.');
    }

    public function showVerifikasi($id)
    {
        $client = Client::findOrFail($id);

        $urutanTahap = [
            'Upload Berkas',
            'Verifikasi 1',
            'Konsultasi Offline',
            'Proses',
            'Tunjuk Advokat',
            'Konfirmasi Kasus',
            'Mediasi',
            'Pendaftaran E-Court',
            'Proses Persidangan',
            'Selesai'
        ];

        $posisiSekarang = array_search($client->status, $urutanTahap);
        $posisiMulai = array_search('Konsultasi Offline', $urutanTahap);

        if ($posisiSekarang === false || $posisiSekarang < $posisiMulai) {
            return redirect()->back()->with('error', 'Perkara ini belum masuk tahap verifikasi.');
        }

        return view('admin.verifikasi_tahap_dua', compact('client'));
    }

    public function verifikasiTahapDua(Request $request, $id)
    {
        $request->validate([
            'pilihan'         => 'required|in:Proses,Rujuk,Tidak Lanjut,Selesai',
            'catatan_admin'   => 'required|string',
            'tujuan_rujuk'    => 'required_if:pilihan,Rujuk|nullable|string|max:255',
            'alamat_tujuan'   => 'required_if:pilihan,Rujuk|nullable|string',
        ]);

        $client = Client::findOrFail($id);

        $urutanTahap = [
            'Upload Berkas',
            'Verifikasi 1',
            'Konsultasi Offline',
            'Proses',
            'Tunjuk Advokat',
            'Konfirmasi Kasus',
            'Mediasi',
            'Pendaftaran E-Court',
            'Proses Persidangan',
            'Selesai'
        ];

        $posisiSekarang = array_search($client->status, $urutanTahap);
        $posisiMulai = array_search('Konsultasi Offline', $urutanTahap);

        if ($posisiSekarang === false || $posisiSekarang < $posisiMulai) {
            return redirect()->back()->with('error', 'Perkara ini belum masuk tahap verifikasi.');
        }

        $status = $request->pilihan;

        $tahapan = match($status) {
            'Proses'       => 'Tunjuk Advokat',
            'Rujuk'        => 'Rujuk',
            'Tidak Lanjut' => 'Tidak Lanjut',
            'Selesai'      => 'KASUS SELESAI (ARSIP)'
        };

        $catatanBaru = match($status) {
            'Proses'       => "Status: Proses - {$request->catatan_admin}",
            'Rujuk'        => "Status: Dirujuk ke {$request->tujuan_rujuk} - {$request->catatan_admin}",
            'Tidak Lanjut' => "Status: Tidak Lanjut - {$request->catatan_admin}",
            'Selesai'      => "Status: SELESAI - {$request->catatan_admin}"
        };

        $pesanWa = match($status) {
            'Proses'       => "Halo {$client->nama}, pendaftaran Anda berlanjut ke tahap pendampingan hukum. Akan segera ditunjuk Advokat untuk menangani perkara Anda. Catatan: {$request->catatan_admin}",
            'Rujuk'        => "Halo {$client->nama}, sesuai hasil pemeriksaan dan konsultasi, perkara Anda dirujuk ke lembaga/instansi:\n📌 {$request->tujuan_rujuk}\n📍 Alamat: {$request->alamat_tujuan}\n📝 Keterangan: {$request->catatan_admin}\nSilakan bawa surat rujukan yang diberikan untuk keperluan selanjutnya.",
            'Tidak Lanjut' => "Halo {$client->nama}, mohon maaf permohonan bantuan hukum Anda tidak dapat kami lanjutkan. Alasan: {$request->catatan_admin}",
            'Selesai'      => "Halo {$client->nama}, perkara Anda telah dinyatakan selesai. Catatan: {$request->catatan_admin}"
        };

        $client->update([
            'status'         => $status,
            'tahapan'        => $tahapan,
            'catatan_admin'  => $catatanBaru,
            'tujuan_rujuk'   => $request->tujuan_rujuk,
            'alamat_tujuan'  => $request->alamat_tujuan,
            'updated_by'     => Auth::id(),
        ]);

        // Notifikasi ke Advokat jika sudah ditunjuk
        if (!empty($client->advokat_id)) {
            try {
                $advokat = User::find($client->advokat_id);
                if ($advokat) {
                    $pesan = "📋 Keputusan Verifikasi Kasus #{$client->reg_code}\nNama: {$client->nama}\nHasil: {$status}\nKeterangan: {$request->catatan_admin}";
                    $url = route('advokat.clients.show', $client->id);

                    $advokat->notify(new NotifikasiAdmin($pesan, $url));

                    if (!empty($advokat->phone)) {
                        $phoneClean = preg_replace('/[^0-9]/', '', $advokat->phone);
                        $waAdvokat = str_starts_with($phoneClean, '0') ? '62' . substr($phoneClean, 1) : (str_starts_with($phoneClean, '62') ? $phoneClean : '62' . $phoneClean);
                        WhatsAppService::send($waAdvokat, $pesan);
                    }
                }
            } catch (\Exception $e) {
                Log::error("Notifikasi ke Advokat (verifikasiTahapDua): " . $e->getMessage());
            }
        }

        // Kirim Notifikasi WA dan Email ke Klien
        try {
            if (!empty($client->phone)) {
                $phoneClientClean = preg_replace('/[^0-9]/', '', $client->phone);
                $waKlien = str_starts_with($phoneClientClean, '0') ? '62' . substr($phoneClientClean, 1) : (str_starts_with($phoneClientClean, '62') ? $phoneClientClean : '62' . $phoneClientClean);
                WhatsAppService::send($waKlien, $pesanWa);
            }

            if (!empty($client->email)) {
                $clientEmail = $client->email;
                Mail::raw($pesanWa, function($message) use ($clientEmail) {
                    $message->to($clientEmail)->subject('Informasi Status Perkara - POSBAKUM');
                });
            }
        } catch (\Exception $e) {
            Log::error("Gagal kirim notifikasi Verifikasi 2: " . $e->getMessage());
        }

        return redirect()->route('admin.clients.show', $client->id)->with('success', 'Keputusan berhasil disimpan & notifikasi telah dikirim.');
    }

    public function suratRujukan(Request $request, $id)
    {
        /** @var \App\Models\Client $client */
        $client = Client::findOrFail($id);

        abort_if($client->status !== 'Rujuk', 403, 'Surat rujukan belum tersedia.');

        if ($request->isMethod('POST') && Auth::user()->role === 'admin') {
            $request->validate([
                'nama_penandatangan' => 'nullable|string|max:255',
                'nip_penandatangan'  => 'nullable|string|max:100',
            ]);

            $client->update([
                'nama_penandatangan' => $request->nama_penandatangan,
                'nip_penandatangan'  => $request->nip_penandatangan,
            ]);

            return redirect()->route('admin.clients.show', $client->id)
                ->with('success', 'Data penandatangan berhasil disimpan!');
        }

        return view('admin.surat_rujukan', compact('client'));
    }

    public function tunjukAdvokat(Request $request, $id)
    {
        $request->validate([
            'advokat_id' => 'required|exists:users,id',
        ]);

        $client = Client::findOrFail($id);

        if ($client->advokat_id !== null) {
            return redirect()->back()->with('error', 'Perkara ini sudah ditangani oleh advokat lain.');
        }

        $advokat = User::findOrFail($request->advokat_id);

        $client->update([
            'advokat_id'        => $request->advokat_id,
            'status'            => 'Tunjuk Advokat',
            'tahapan'           => 'Tunjuk Advokat',
            'updated_by'        => Auth::id(),
            'catatan_admin'     => $request->filled('catatan_admin') ? $request->catatan_admin : null,
            'jadwal_konsultasi' => null,
        ]);

        $pesanAdvokat = "📌 PENUNJUKAN KASUS BARU\n\n" .
                        "Kode Perkara: {$client->reg_code}\n" .
                        "Nama Klien: {$client->nama}\n" .
                        "Layanan: {$client->layanan}\n" .
                        "Status: Siap ditangani\n\n" .
                        "Silakan cek detail kasus di dashboard Anda.";

        $urlAdvokat = route('advokat.clients.show', $client->id);

        // 1. Notifikasi lonceng (sistem)
        try {
            $advokat->notify(new NotifikasiAdmin($pesanAdvokat, $urlAdvokat));
        } catch (\Exception $e) {
            Log::error("Notifikasi lonceng gagal: " . $e->getMessage());
        }

        // 2. Kirim WhatsApp ke Advokat dengan format nomor aman
        try {
            if (!empty($advokat->phone)) {
                $phoneClean = preg_replace('/[^0-9]/', '', $advokat->phone);
                $waAdvokat = str_starts_with($phoneClean, '0') ? '62' . substr($phoneClean, 1) : (str_starts_with($phoneClean, '62') ? $phoneClean : '62' . $phoneClean);
                WhatsAppService::send($waAdvokat, $pesanAdvokat);
            }
        } catch (\Exception $e) {
            Log::error("Notifikasi WA ke advokat gagal: " . $e->getMessage());
        }

        // 3. Kirim Email ke Advokat
        try {
            if (!empty($advokat->email)) {
                $advokatEmail = $advokat->email;
                Mail::raw($pesanAdvokat, function($message) use ($advokatEmail) {
                    $message->to($advokatEmail)->subject("Penunjukan Kasus Baru - POSBAKUM AISYIYAH");
                });
            }
        } catch (\Exception $e) {
            Log::error("Notifikasi Email ke advokat gagal: " . $e->getMessage());
        }

        // Notifikasi ke Klien
        $pesanKlien = "Halo {$client->nama},\n\n✅ Perkara Anda telah ditunjuk untuk ditangani.\n" .
                      "👨‍⚖️ Advokat: {$advokat->name}\n" .
                      "Kami akan menghubungi Anda kembali untuk langkah selanjutnya.";

        try {
            if (!empty($client->phone)) {
                $phoneClientClean = preg_replace('/[^0-9]/', '', $client->phone);
                $waKlien = str_starts_with($phoneClientClean, '0') ? '62' . substr($phoneClientClean, 1) : (str_starts_with($phoneClientClean, '62') ? $phoneClientClean : '62' . $phoneClientClean);
                WhatsAppService::send($waKlien, $pesanKlien);
            }

            if (!empty($client->email)) {
                $clientEmail = $client->email;
                Mail::raw($pesanKlien, function($message) use ($clientEmail) {
                    $message->to($clientEmail)->subject("Penunjukan Advokat - POSBAKUM");
                });
            }
        } catch (\Exception $e) {
            Log::error("Notifikasi ke klien gagal: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Advokat berhasil ditunjuk, semua notifikasi telah dikirim!');
    }

    public function approve($id)
    {
        $client = Client::findOrFail($id);

        $client->update([
            'status' => 'PENINJAUAN BERKAS',
            'tahapan' => 'PENINJAUAN BERKAS',
            'catatan_admin' => "Status: Berkas telah diverifikasi, sedang dalam tahap peninjauan.",
            'updated_by' => Auth::id()
        ]);

        if ($client->kategori_layanan == 'UMUM') {
            Income::updateOrCreate(
                ['client_id' => $client->id],
                ['amount' => 300000, 'description' => 'Pembayaran klien ' . $client->nama, 'date' => now()]
            );
        }

        return redirect()->back()->with('success', 'Berkas berhasil diverifikasi, status sekarang: PENINJAUAN BERKAS.');
    }

    public function reject(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $request->validate(['catatan_admin' => 'required|string']);

        $client->update([
            'status'        => 'Ditolak',
            'catatan_admin' => "Status: Ditolak - {$request->catatan_admin}",
            'tahapan'       => 'Proses Selesai'
        ]);

        return redirect()->back()->with('success', 'Permohonan klien telah ditolak.');
    }

    public function markPaymentIncomplete(Request $request, $id)
    {
        $request->validate(['catatan_admin' => 'required|string|max:500']);
        $client = Client::findOrFail($id);
        $client->update([
            'status'        => 'Perlu Perbaikan',
            'catatan_admin' => "Status: Perlu Perbaikan - {$request->catatan_admin}",
            'tahapan'       => 'Perlu Perbaikan',
            'updated_by'    => Auth::id()
        ]);
        return redirect()->back()->with('success', 'Catatan kekurangan berhasil dikirim.');
    }

    public function galleryIndex()
    {
        $galleries = Gallery::latest()->get();
        return view('admin.gallery', compact('galleries'));
    }

    public function galleryStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,webp|max:20480',
        ]);

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $path = $file->store('gallery', 'public');

                Gallery::create([
                    'title' => $request->title,
                    'image' => $path,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Semua foto galeri berhasil ditambahkan!');
    }

    public function galleryDestroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        if ($gallery->image) {
            Storage::disk('public')->delete($gallery->image);
        }

        $gallery->delete();

        return redirect()->back()->with('success', 'Foto galeri berhasil dihapus!');
    }

    public function printIdentity($id)
    {
        $client = Client::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'admin') {
            return view('admin.print_identity', compact('client'));
        }

        if ($user->role === 'advokat' && (int)$client->advokat_id === (int)$user->id) {
            return view('admin.print_identity', compact('client'));
        }

        if ($user->role === 'klien' && isset($client->user_id) && (int)$client->user_id === (int)$user->id) {
            return view('admin.print_identity', compact('client'));
        }

        abort(403, 'Anda tidak memiliki akses untuk mencetak dokumen ini.');
    }

    public function ajaxKirimWA(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        try {
            if (!$request->target_phone || !$request->pesan) {
                return response()->json(['success' => false, 'message' => 'Data tidak lengkap.'], 400);
            }

            WhatsAppService::send($request->target_phone, $request->pesan);

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi WhatsApp berhasil terkirim otomatis!'
            ]);
        } catch (Exception $e) {
            Log::error("Gagal kirim WA via AJAX: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}

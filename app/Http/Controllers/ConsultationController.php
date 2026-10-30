<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ConsultationReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    // 1. Menampilkan Form Laporan Baru
    public function create($client_id)
    {
        $client = Client::findOrFail($client_id);

        // ✅ Cek hak akses
        if (Auth::user()->role === 'advokat' && $client->advokat_id != Auth::id()) {
            abort(403, 'Anda tidak berhak mengakses kasus ini.');
        }

        // Hitung pertemuan ke berapa secara otomatis
        $pertemuanKe = $client->consultationReports()->count() + 1;

        return view('admin.consultation.create', compact('client', 'pertemuanKe'));
    }

    // 2. Menyimpan Data Laporan ke Database
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'pertemuan_ke' => 'required|integer',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'tempat' => 'required|string',
            'permasalahan_klien' => 'required',
            'harapan_klien' => 'required',
            'legal_advice' => 'required',
            'rencana_tindak_lanjut' => 'required',
        ]);

        $client = Client::findOrFail($request->client_id);

        // ✅ Cek akses sebelum simpan
        if (Auth::user()->role === 'advokat' && $client->advokat_id != Auth::id()) {
            abort(403, 'Anda tidak berhak menyimpan laporan ini.');
        }

        // ✅ Simpan laporan dengan logika yang sudah disesuaikan
// Simpan laporan
ConsultationReport::create([
    'client_id'              => $request->client_id,
    'no_dokumen'             => $request->no_dokumen,
    'no_reg'                 => $client->reg_code,
    'nama_klien'             => $client->nama,
    'tanggal'                => $request->tanggal,
    'waktu'                  => $request->waktu,
    'tempat'                 => $request->tempat,
    'pertemuan_ke'           => $request->pertemuan_ke,
    'kasus'                  => $request->kasus,

    // ✅ LOGIKA BARU:
    // - Kalau Advokat: otomatis pakai namanya sendiri
    // - Kalau Admin: bisa pilih/ketik nama advokat, atau kalau kosong otomatis tulis "Admin"
    'advokat_nama'           => Auth::user()->role === 'advokat'
                                ? Auth::user()->name
                                : ($request->advokat_nama ?? Auth::user()->name),

    'konsultan_tipe'         => $request->konsultan_tipe,
    'paralegal_nama'         => $request->paralegal_nama,
    'supervisor_nama'        => $request->supervisor_nama,
    'koordinator_nama'       => $request->koordinator_nama,
    'permasalahan_klien'     => $request->permasalahan_klien,
    'harapan_klien'          => $request->harapan_klien,
    'legal_advice'           => $request->legal_advice,
    'rencana_tindak_lanjut'  => $request->rencana_tindak_lanjut,
    'catatan_supervisi'      => $request->catatan_supervisi,
    'dibuat_oleh_role'       => Auth::user()->role,
    'user_id'                => Auth::id(),
]);

        // Redirect sesuai peran pengguna
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.clients.show', $client->id)
                ->with('success', 'Laporan Konsultasi Pertemuan ke-' . $request->pertemuan_ke . ' berhasil disimpan!');
        } else {
            return redirect()->route('advokat.clients.show', $client->id)
                ->with('success', 'Laporan Konsultasi Pertemuan ke-' . $request->pertemuan_ke . ' berhasil disimpan!');
        }
    }

    /**
     * 3. Method Cetak Laporan
     */
    public function print($id)
    {
        // Ambil data laporan beserta data kliennya
        $report = ConsultationReport::with('client')->findOrFail($id);

        // ✅ Cek hak akses sebelum melihat/mencetak
        if (Auth::user()->role === 'advokat' && $report->client->advokat_id != Auth::id()) {
            abort(403, 'Anda tidak berhak melihat laporan ini.');
        }

        // Tampilkan halaman cetak
        return view('admin.consultation.print', compact('report'));
    }
}

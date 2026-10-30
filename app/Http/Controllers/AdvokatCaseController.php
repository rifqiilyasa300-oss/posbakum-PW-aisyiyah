<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User; // ✅ Ditambahkan agar pemanggilan User:: tidak error
use App\Models\ConsultationReport;
use App\Services\WhatsAppService;
use App\Notifications\NotifikasiAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdvokatCaseController extends Controller
{
    /**
     * Dashboard Advokat
     */
    public function index()
    {
        $advokat_id = Auth::id();

        // ✅ Kasus yang TETAP di Dashboard Utama: baru ditunjuk, belum dikonfirmasi
        $clients = Client::where('advokat_id', $advokat_id)
            ->whereNotIn('status', ['Selesai', 'Tidak Lanjut', 'Ditolak', 'KASUS SELESAI (ARSIP)'])
            ->whereNotIn('tahapan', [ // ✅ Kecualikan yang sudah masuk tahap lanjut
                'KONFIRMASI KASUS (TLP/OFFLINE)',
                'MEDIASI (NON-LITIGASI)',
                'TERTUNDA',
                'PENDAFTARAN E-COURT (LITIGASI)',
                'PROSES PERSIDANGAN'
            ])
            ->latest()
            ->get();

        $totalAktif = $clients->count();

        $totalSelesai = Client::where('advokat_id', $advokat_id)
            ->whereIn('status', ['Selesai', 'Tidak Lanjut', 'Ditolak', 'KASUS SELESAI (ARSIP)'])
            ->count();

        return view('advokat.dashboard', compact('clients', 'totalAktif', 'totalSelesai'));
    }

    /**
     * Riwayat Kasus Aktif
     */
    public function riwayat()
    {
        $advokat_id = Auth::id();

        $tahapanDiRiwayat = [
            'KONFIRMASI KASUS (TLP/OFFLINE)',
            'MEDIASI (NON-LITIGASI)',
            'TERTUNDA',
            'PENDAFTARAN E-COURT (LITIGASI)',
            'PROSES PERSIDANGAN'
        ];

        $clients = Client::where('advokat_id', $advokat_id)
                         ->whereIn('tahapan', $tahapanDiRiwayat)
                         ->latest()
                         ->get();

        $title = "Riwayat Kasus Aktif";
        return view('advokat.riwayat', compact('clients', 'title'));
    }

    /**
     * Detail Kasus
     */
    public function show($id)
    {
        $client = Client::where('id', $id)
                        ->where('advokat_id', Auth::id())
                        ->firstOrFail();

        foreach (Auth::user()->unreadNotifications as $notification) {
            if (isset($notification->data['url']) && str_contains($notification->data['url'], '/advokat/kasus/' . $id)) {
                $notification->markAsRead();
            }
        }

        return view('advokat.show', compact('client'));
    }

    /**
     * Simpan Laporan Konsultasi
     */
    public function storeLaporan(Request $request, $id)
    {
        $request->validate([
            'isi_laporan' => 'required|string',
            'saran_hukum' => 'required|string',
        ]);

        ConsultationReport::create([
            'client_id'   => $id,
            'user_id'     => Auth::id(),
            'isi_laporan' => $request->isi_laporan,
            'saran_hukum' => $request->saran_hukum,
            'tanggal'     => now(),
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil disimpan.');
    }

    /**
     * Update Progres Kasus + Kirim Semua Notifikasi
     */
    public function updateStatus(Request $request, $id)
    {
        $client = Client::where('id', $id)->where('advokat_id', Auth::id())->firstOrFail();

        $request->validate([
            'tahapan' => 'required|in:KONFIRMASI KASUS (TLP/OFFLINE),MEDIASI (NON-LITIGASI),PENDAFTARAN E-COURT (LITIGASI),PROSES PERSIDANGAN,TERTUNDA,KASUS SELESAI (ARSIP)',
            'legal_advice' => 'required|string|max:1500'
        ]);

        $catatanBaru = "Status: {$request->tahapan} - Keterangan: {$request->legal_advice}";

        if ($request->tahapan === 'KASUS SELESAI (ARSIP)') {
            $statusBaru = 'KASUS SELESAI (ARSIP)';
        } elseif ($request->tahapan === 'TERTUNDA') {
            $statusBaru = 'TERTUNDA';
        } else {
            $statusBaru = trim(str_replace(["\n", "\r"], "", $request->tahapan));
        }

        $riwayat = $client->riwayat_tahapan ?? [];
        if (!in_array($request->tahapan, $riwayat)) {
            $riwayat[] = $request->tahapan;
        }

        $client->update([
            'status'          => $statusBaru,
            'tahapan'         => $request->tahapan,
            'legal_advice'    => $request->legal_advice,
            'catatan_admin'   => $catatanBaru,
            'riwayat_tahapan' => $riwayat
        ]);

        $pesan = "Halo {$client->nama},\n\n📢 Status perkara Anda berubah menjadi: {$request->tahapan}\n📝 Keterangan: {$request->legal_advice}";

        try {
            if (!empty($client->phone)) {
                $waKlien = ltrim($client->phone, '0');
                $waKlien = '62' . $waKlien;
                WhatsAppService::send($waKlien, $pesan);
            }

            if (!empty($client->email)) {
                Mail::raw($pesan, function($message) use ($client) {
                    $message->to($client->email)->subject('Pembaruan Status Perkara - POSBAKUM');
                });
            }

            if ($client->user_id && $client->user) {
                $urlKlien = route('dashboard');
                $client->user->notify(new NotifikasiAdmin($pesan, $urlKlien));
            }

            // ✅ PERBAIKAN: Kirim notifikasi sistem ke SEMUA Admin, bukan cuma satu
            $admins = User::where('role', 'admin')->get();
            $pesanAdmin = "⚖️ Advokat " . Auth::user()->name . " memperbarui Kasus #{$client->reg_code} menjadi: {$request->tahapan}";
            $urlAdmin = route('admin.clients.show', $client->id);

            foreach ($admins as $admin) {
                $admin->notify(new NotifikasiAdmin($pesanAdmin, $urlAdmin));
            }

        } catch (\Exception $e) {
            Log::error("Gagal kirim notifikasi update status: " . $e->getMessage());
        }

        return redirect()->route('advokat.clients.show', $id)
                         ->with('success', 'Tahapan diperbarui, riwayat tersimpan, dan Admin telah diberi tahu.');
    }

    /**
     * Finalisasi Kasus
     */
    public function finalize($id)
    {
        $client = Client::where('id', $id)
                        ->where('advokat_id', Auth::id())
                        ->firstOrFail();

        $catatanSelesai = "Status: KASUS SELESAI (ARSIP) - Perkara telah selesai ditangani oleh Advokat";

        // ✅ DISAMAKAN: Menggunakan string 'KASUS SELESAI (ARSIP)' agar konsisten dengan filter arsip Anda
        $client->update([
            'status'         => 'KASUS SELESAI (ARSIP)',
            'tahapan'        => 'KASUS SELESAI (ARSIP)',
            'closed_at'      => now(),
            'catatan_admin'  => $catatanSelesai
        ]);

        $pesan = "Halo {$client->nama},\n\n✅ Perkara Anda telah dinyatakan SELESAI.\nTerima kasih telah mempercayakan pendampingan hukum kepada kami.";

        try {
            if (!empty($client->phone)) {
                $waKlien = ltrim($client->phone, '0');
                $waKlien = '62' . $waKlien;
                WhatsAppService::send($waKlien, $pesan);
            }

            if (!empty($client->email)) {
                Mail::raw($pesan, function($message) use ($client) {
                    $message->to($client->email)->subject('Perkara Selesai - POSBAKUM');
                });
            }

            if ($client->user_id && $client->user) {
                $urlKlien = route('dashboard');
                $client->user->notify(new NotifikasiAdmin($pesan, $urlKlien));
            }

            // ✅ TAMBAHAN: Kirim notifikasi ke Admin juga saat kasus di-finalize
            $admins = User::where('role', 'admin')->get();
            $pesanAdmin = "✅ Kasus #{$client->reg_code} telah diselesaikan oleh Advokat " . Auth::user()->name;
            $urlAdmin = route('admin.clients.show', $client->id);

            foreach ($admins as $admin) {
                $admin->notify(new NotifikasiAdmin($pesanAdmin, $urlAdmin));
            }

        } catch (\Exception $e) {
            Log::error("Notifikasi Selesai: " . $e->getMessage());
        }

        return redirect()->route('advokat.arsip')->with('success', 'Kasus berhasil diselesaikan dan diarsipkan.');
    }

    /**
     * Arsip Kasus
     */
    public function arsip()
    {
        $clients = Client::where('advokat_id', Auth::id())
                        ->whereIn('status', [
                            'Selesai',
                            'KASUS SELESAI (ARSIP)',
                            'Ditolak',
                            'Tidak Lanjut',
                            'Rujuk'
                        ])
                        ->latest()
                        ->get();

        return view('advokat.arsip', compact('clients'));
    }
}

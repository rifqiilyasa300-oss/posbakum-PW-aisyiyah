<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminVerificationController;
use App\Http\Controllers\AdvokatCaseController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\Auth\VerificationController;
use App\Models\Gallery;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Halaman Landing Utama
Route::get('/', function () {
    $galleries = Gallery::latest()->get();
    return view('welcome', compact('galleries'));
});

// RUTE VERIFIKASI OTP
Route::get('/verify-otp', [VerificationController::class, 'showForm'])->name('verify.otp.form');
Route::post('/verify-otp', [VerificationController::class, 'verify'])->name('verify.otp.post');

// 2. Grup Rute yang Wajib Login (Auth)
Route::middleware('auth')->group(function () {

    // --- RUTE PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * RUTE DOKUMEN & UPLOAD (Shared/Global)
     */
    Route::get('/dokumen/lihat/{id}', [ClientController::class, 'showDocuments'])
        ->name('clients.showDocuments');

    Route::get('/dokumen/file/{id}/{type}', [ClientController::class, 'viewFile'])
        ->name('clients.viewFile');

    Route::post('/admin/clients/{id}/upload', [ClientController::class, 'uploadAdditional'])
        ->name('admin.clients.uploadAdditional');

    Route::post('/advokat/kasus/{id}/upload', [ClientController::class, 'uploadAdditional'])
        ->name('advokat.clients.uploadAdditional');

    Route::post('/admin/clients/{id}/upload-putusan', [AdminController::class, 'uploadPutusan'])
        ->name('admin.clients.uploadPutusan');

    Route::get('/admin/clients/{id}/print', [AdminController::class, 'printIdentity'])
        ->name('admin.clients.print');

    Route::get('/admin/clients/{id}/pdf', [AdminController::class, 'printIdentity'])
        ->name('admin.clients.pdf');

    // --- Rute cetak ---
    Route::get('/consultation/print/{id}', [ConsultationController::class, 'print'])
        ->name('consultation.print');


    // ==========================================
    // AREA KHUSUS KLIEN (Role: klien)
    // ==========================================
    Route::middleware(['role:klien'])->group(function () {

        Route::get('/dashboard', [ClientController::class, 'tracking'])->name('dashboard');

        Route::get('/daftar', function () {
            return view('clients.index');
        })->name('client.register');

        Route::post('/daftar/simpan', [ClientController::class, 'store'])->name('client.store');
        Route::get('/tracking', [ClientController::class, 'tracking'])->name('client.tracking');

        Route::get('/perkara/{id}', [ClientController::class, 'show'])->name('client.perkara.show');

        // ✅ RUTE BARU: Cetak Surat Rujukan untuk Klien
        Route::get('/perkara/{id}/surat-rujukan', [ClientController::class, 'cetakSuratRujukan'])->name('client.surat.rujukan');

        // --- RUTE EDIT & UPDATE KLIEN ---
        Route::get('/perkara/{id}/edit', [ClientController::class, 'edit'])->name('client.edit');
        Route::patch('/perkara/{id}/update', [ClientController::class, 'update'])->name('client.update');
    });


    // ==========================================
    // AREA KHUSUS ADMIN (Role: admin)
    // ==========================================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        // --- Dashboard & Kelola Kasus ---
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // RUTE VERIFIKASI 1-KLIK
        Route::get('/verifikasi-user/{id}', [AdminVerificationController::class, 'verify'])
             ->name('verifikasi-user');

        // RUTE PEMASUKAN
        Route::get('/incomes', [AdminController::class, 'indexIncomes'])->name('incomes.index');

        Route::get('/kelola-kasus', [AdminController::class, 'kelolaKasus'])->name('kelola-kasus');
        Route::get('/clients/{id}', [AdminController::class, 'show'])->name('clients.show');

        // Rute Tunjuk Advokat
        Route::post('/clients/{id}/tunjuk-advokat', [AdminController::class, 'tunjukAdvokat'])->name('clients.tunjuk-advokat');

        // Halaman form Verifikasi 2
        Route::get('/verifikasi-tahap-dua/{id}', [AdminController::class, 'showVerifikasi'])->name('show.verifikasi');
        // Aksi simpan hasil Verifikasi 2
        Route::post('/verifikasi-tahap-dua/{id}', [AdminController::class, 'verifikasiTahapDua'])->name('verifikasi.dua');

        // ✅ RUTE SURAT RUJUKAN (Tetap ada untuk Admin)
        Route::match(['GET', 'POST'], '/clients/{id}/surat-rujukan', [AdminController::class, 'suratRujukan'])->name('surat.rujukan');

        Route::patch('/clients/{id}/update-status', [AdminController::class, 'updateStatus'])->name('clients.updateStatus');
        Route::patch('/clients/{id}/approve', [AdminController::class, 'approve'])->name('clients.approve');

        Route::patch('/clients/{id}/mark-payment', [AdminController::class, 'markPaymentIncomplete'])->name('clients.markPayment');

        Route::patch('/clients/{id}/reject', [AdminController::class, 'reject'])->name('clients.reject');

        // RUTE AJAX KIRIM WA OTOMATIS
        Route::post('/clients/ajax-kirim-wa', [AdminController::class, 'ajaxKirimWA'])->name('clients.ajaxKirimWA');

        // --- MANAJEMEN STAFF ---
        Route::get('/staff', [AdminController::class, 'indexStaff'])->name('staff.index');
        Route::get('/staff/create', [AdminController::class, 'createStaff'])->name('staff.create');
        Route::post('/staff/store', [AdminController::class, 'storeStaff'])->name('staff.store');
        Route::get('/staff/{id}/edit', [AdminController::class, 'editStaff'])->name('staff.edit');
        Route::patch('/staff/{id}', [AdminController::class, 'updateStaff'])->name('staff.update');

        Route::delete('/staff/{id}', [AdminController::class, 'destroyStaff'])->name('staff.destroy');

        // --- FITUR GALERI ---
        Route::get('/gallery', [AdminController::class, 'galleryIndex'])->name('gallery.index');
        Route::post('/gallery/store', [AdminController::class, 'galleryStore'])->name('gallery.store');
        Route::delete('/gallery/{id}', [AdminController::class, 'galleryDestroy'])->name('gallery.destroy');

        // --- Laporan Konsultasi ---
        Route::get('/clients/{client_id}/consultation/create', [ConsultationController::class, 'create'])
            ->name('consultation.create');
        Route::post('/consultation/store', [ConsultationController::class, 'store'])
            ->name('consultation.store');

        // --- Arsip ---
        Route::get('/arsip', [AdminController::class, 'arsipIndex'])->name('arsip');
    });


    // ==========================================
    // AREA KHUSUS ADVOKAT (Role: advokat)
    // ==========================================
    Route::middleware(['role:advokat'])->prefix('advokat')->name('advokat.')->group(function () {
        Route::get('/dashboard', [AdvokatCaseController::class, 'index'])->name('dashboard');
        Route::get('/riwayat', [AdvokatCaseController::class, 'riwayat'])->name('riwayat');
        Route::get('/arsip', [AdvokatCaseController::class, 'arsip'])->name('arsip');
        Route::get('/kasus/{id}', [AdvokatCaseController::class, 'show'])->name('clients.show');
        Route::patch('/kasus/{id}/update-status', [AdvokatCaseController::class, 'updateStatus'])->name('clients.updateStatus');
        Route::patch('/kasus/{id}/finalize', [AdvokatCaseController::class, 'finalize'])->name('clients.finalize');

        // --- RUTE KONSULTASI ---
        Route::get('/kasus/{client_id}/consultation/create', [ConsultationController::class, 'create'])
            ->name('consultation.create');
        Route::post('/consultation/store', [ConsultationController::class, 'store'])
            ->name('consultation.store');

        // ✅ RUTE BARU: ATUR JADWAL KONSULTASI
        Route::patch('/kasus/{id}/atur-jadwal', [ClientController::class, 'aturJadwal'])
            ->name('clients.aturJadwal');
    });

    // --- RUTE NOTIFIKASI ---
    Route::get('/notifications/read/{id}', function ($id) {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
            return redirect($notification->data['url'] ?? '/dashboard');
        }
        return back();
    })->name('notifications.read');

});


require __DIR__.'/auth.php';

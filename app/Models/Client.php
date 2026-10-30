<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'user_id',
        'advokat_id',
        'reg_code',
        'nama',
        'nik',
        'email',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat_ktp',
        'alamat_domisili',
        'phone',
        'status_perkawinan',
        'agama',
        'pendidikan',
        'pekerjaan',
        'uraian_masalah',
        'harapan_klien',
        'ktp_path',
        'sktm_path',
        'bukti_bayar_path',
        'file_putusan',
        'status',
        'tahapan',
        'kategori_layanan',
        'catatan_admin',
        'jadwal_konsultasi',
        'updated_by',
        'tujuan_rujuk',
        'alamat_tujuan',
        'nama_penandatangan',
        'nip_penandatangan',
        'legal_advice',
        'riwayat_tahapan' // ✅ DITAMBAHKAN: Supaya bisa disimpan ke database
    ];

    /**
     * Casting kolom JSON agar otomatis menjadi Array saat dipanggil di Blade
     */
    protected $casts = [
        'jadwal_konsultasi' => 'datetime',
        'riwayat_tahapan'   => 'array', // ✅ DITAMBAHKAN: Otomatis ubah JSON ke array dan sebaliknya
    ];

    /**
     * TAMBAHAN: Daftar pilihan status untuk aplikasi
     */
    public static function getStatusOptions()
    {
        return [
            'Menunggu Verifikasi' => 'Menunggu Verifikasi',
            'Pendaftaran Diterima' => 'Pendaftaran Diterima',
            'Perlu Perbaikan'     => 'Perlu Perbaikan',
            'PENINJAUAN BERKAS'   => 'Peninjauan Berkas',
            'Konsultasi Offline'  => 'Konsultasi Offline',
            'Tunjuk Advokat'      => 'Tunjuk Advokat',
            'Proses'              => 'Proses',
            'Rujuk'               => 'Rujuk',
            'Selesai'             => 'Selesai',
            'Tidak Lanjut'        => 'Tidak Lanjut',
        ];
    }

    /**
     * TAMBAHAN: Logika warna badge berdasarkan status
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'Selesai'            => 'bg-green-100 text-green-700 border-green-200',
            'Proses'             => 'bg-blue-100 text-blue-700 border-blue-200',
            'Rujuk'              => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            'Tidak Lanjut'       => 'bg-red-100 text-red-700 border-red-200',
            'Konsultasi Offline' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
            'PENINJAUAN BERKAS'  => 'bg-orange-100 text-orange-700 border-orange-200',
            'Perlu Perbaikan'    => 'bg-red-50 text-red-600 border-red-200',
            default              => 'bg-gray-100 text-gray-700 border-gray-200',
        };
    }

    /**
     * RELASI: Klien dimiliki oleh satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * RELASI: Klien ditangani oleh satu Advokat
     */
    public function advokat()
    {
        return $this->belongsTo(User::class, 'advokat_id');
    }

    public function consultationReports()
    {
        return $this->hasMany(ConsultationReport::class);
    }

    /**
     * RELASI: Klien memiliki banyak catatan pemasukan (Income)
     */
    public function incomes()
    {
        return $this->hasMany(Income::class, 'client_id');
    }

    /**
     * RELASI: Klien memiliki banyak dokumen tambahan (Tabel Terpisah)
     */
    public function additional_documents()
    {
        return $this->hasMany(ClientDocument::class, 'client_id');
    }

    public function docs()
    {
        return $this->hasMany(ClientDocument::class, 'client_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getTempatTanggalLahirAttribute()
    {
        if (!$this->tanggal_lahir) {
            return $this->tempat_lahir ?? '-';
        }

        $tanggal = Carbon::parse($this->tanggal_lahir)->translatedFormat('d F Y');
        return ($this->tempat_lahir ?? '-') . ', ' . $tanggal;
    }
}

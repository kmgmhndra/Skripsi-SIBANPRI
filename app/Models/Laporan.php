<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laporan extends Model
{
    protected $fillable = [
        'nama_laporan',
        'kecamatan',
        'tanggal_seleksi',
        'jumlah_kelompok_tani', 
        'jenis_tani',
        'tahun',
        'user_id'
    ];

    // Tambahkan relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke SubLaporan (sudah benar, tapi bisa lebih eksplisit)
    public function subLaporans(): HasMany
    {
        return $this->hasMany(SubLaporan::class, 'laporan_id');
    }

    // Jika perlu akses mudah ke kelompok tani melalui laporan
    public function kelompokTanis()
    {
        return $this->hasManyThrough(
            KelompokTani::class,
            SubLaporan::class,
            'laporan_id', // Foreign key pada SubLaporan
            'id', // Foreign key pada KelompokTani
            'id', // Local key pada Laporan
            'kelompok_tani_id' // Local key pada SubLaporan
        );
    }
}
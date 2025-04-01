<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    //
    protected $fillable = [
        'nama_laporan',
        'kecamatan',
        'tanggal_seleksi',
        'jumlah_kelompok_tani',
        'jenis_tani'



    ];

    public function subLaporans() // Changed to plural form (convention)
    {
        return $this->hasMany(SubLaporan::class, 'laporan_id'); // Explicitly specify foreign key
    }


}

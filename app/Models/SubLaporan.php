<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubLaporan extends Model
{
    protected $fillable = [
        'nama_kelompok_tani',
        'nama_ketua',
        'nama_desa',
        'laporan_id',
        'laporan_id',
        'nilai_wpm',
        'peringkat',
        'kelompok_tani_id'


    ];
    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

}

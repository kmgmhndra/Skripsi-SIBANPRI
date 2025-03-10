<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seleksi extends Model
{
    use HasFactory;

    // Tentukan kolom yang dapat diisi
    protected $fillable = [
        'kelompok_tani',
        'kecamatan_id',
        'nilai',
    ];

    // Relasi ke model KelompokTani
    public function kelompokTani()
    {
        return $this->belongsTo(KelompokTani::class);
    }

    // Relasi ke model Kecamatan
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}

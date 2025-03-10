<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokTani extends Model
{
    use HasFactory;

    protected $table = 'kelompok_tani';

    protected $fillable = [
        'nama',
        'desa',
        'ketua',
        'kecamatan_id',
        'simluhtan',
        'terpoligon',
        'bantuan_sebelumnya',
        'dpi',
        'provitas',
        'status',
    ];
        

    // Relasi ke tabel Kecamatan
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id');
    }
}
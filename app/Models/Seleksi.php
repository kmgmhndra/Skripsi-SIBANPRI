<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seleksi extends Model
{
    use HasFactory;

    protected $table = 'seleksi';

    protected $fillable = [
        'kecamatan_id',
        'nama_kelompok_tani',
        'ketua',
        'desa',
        'nilai_wpm',
        'peringkat',
        'terpilih',
        'jenis_tani'
    ];

    public $timestamps = true;
}

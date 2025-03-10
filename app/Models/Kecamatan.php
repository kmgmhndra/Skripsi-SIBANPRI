<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'kecamatan'; // Memastikan nama tabel

    protected $fillable = ['nama']; // Kolom yang bisa diisi secara massal

    public $timestamps = true; // Jika tidak butuh timestamps, ubah menjadi false

    /**
     * Relasi dengan model KelompokTani (One to Many)
     */
    public function kelompokTani()
    {
        return $this->hasMany(KelompokTani::class, 'kecamatan_id', 'id');
    }
}

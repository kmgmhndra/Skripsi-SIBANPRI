<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KriteriaValue extends Model
{
    //
    protected $fillable = ['kriteria_id', 'value', 'kelompok_tani_id']; // Tambahkan 'jenis'

}

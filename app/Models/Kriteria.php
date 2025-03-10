<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';
    protected $fillable = ['nama', 'urutan', 'bobot'];

    public static function hitungBobotROC()
    {
        $kriteria = self::orderBy('urutan')->get();
        $jumlah = $kriteria->count();

        foreach ($kriteria as $index => $item) {
            $bobotROC = 0;
            for ($j = $index + 1; $j <= $jumlah; $j++) {
                $bobotROC += 1 / $j;
            }
            $bobotROC /= $jumlah;

            $item->update(['bobot' => $bobotROC]);
        }
    }
}
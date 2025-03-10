<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Gerokgak'],
            ['nama' => 'Seririt'],
            ['nama' => 'Busungbiu'],
            ['nama' => 'Banjar'],
            ['nama' => 'Buleleng'],
            ['nama' => 'Sawan'],
            ['nama' => 'Kubutambahan'],
            ['nama' => 'Tejakula'],
        ];

        Kecamatan::insert($data);
    }
}

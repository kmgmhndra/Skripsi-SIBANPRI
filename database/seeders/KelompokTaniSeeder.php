<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelompokTaniSeeder extends Seeder
{
    public function run()
    {
        DB::table('kelompok_tani')->truncate();

        DB::table('users')->truncate();
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin'
        ]);
        DB::table('users')->insert([
            'name' => 'mahendra',
            'email' => 'mahendra@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'petugas'
        ]);


        DB::table('kelompok_tani')->insert([
            [
                'nama' => 'Subak Banyumala',
                'desa' => 'Desa Banyuasri',
                'ketua' => 'Made Suartana',
                'kecamatan_id' => 1, // Kecamatan Gerokgak
                'jenis_tani' => 'Padi',

                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Subak Gebang',
                'desa' => 'Desa Subuk',
                'ketua' => 'Ketut Seriada',
                'kecamatan_id' => 1, // Kecamatan Gerokgak
                'jenis_tani' => 'Padi',

                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Subak Tista',
                'desa' => 'Desa Tista',
                'ketua' => 'Made Sutama',
                'kecamatan_id' => 1, // Kecamatan Gerokgak
                'jenis_tani' => 'Padi',

                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Subak 1',
                'desa' => 'Desa 1',
                'ketua' => 'Made Gede',
                'kecamatan_id' => 1, // Kecamatan Gerokgak
                'jenis_tani' => 'Palawija',

                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Subak Keramas',
                'desa' => 'Desa Keramas',
                'ketua' => 'Made Surya',
                'kecamatan_id' => 2,
                'jenis_tani' => 'Palawija',

                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelompokTaniSeeder extends Seeder
{
    public function run()
    {
        DB::table('kelompok_tani')->delete();

        DB::table('users')->delete();
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin'
        ]);
        DB::table('users')->insert([
            'name' => 'Petugas',
            'email' => 'petugas@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'petugas'
        ]);

    }
}

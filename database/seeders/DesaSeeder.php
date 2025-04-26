<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;
use App\Models\Desa;

class DesaSeeder extends Seeder
{
    public function run(): void
    {
        $kecamatans = Kecamatan::all();

        $data = [
            'Banjar' => [
                'Banjar',
                'Banjar Tegeha',
                'Banyuatis',
                'Banyuseri',
                'Cempaga',
                'Dencarik',
                'Gesing',
                'Gobleg',
                'Kaliasem',
                'Kayuputih',
                'Munduk',
                'Pedawa',
                'Sidetapa',
                'Tampekan',
                'Temukus',
                'Tigawasa',
                'Tirtasari'
            ],
            'Buleleng' => [
                'Alasangker',
                'Anturan',
                'Bakti Seraga',
                'Jinengdalem',
                'Kalibukbuk',
                'Nagasepaha',
                'Pemaron',
                'Penglatan',
                'Petandakan',
                'Poh Bergong',
                'Sari Mekar',
                'Tukadmungga',
                'Astina',
                'Banjar Bali',
                'Banjar Jawa',
                'Banjar Tegal',
                'Banyuasri',
                'Banyuning',
                'Beratan',
                'Kaliuntu',
                'Kampung Anyar',
                'Kampung Baru',
                'Kampung Bugis',
                'Kampung Kajanan',
                'Kampung Singaraja',
                'Kendran',
                'Liligundi',
                'Paket Agung',
                'Penarukan'
            ],
            'Busungbiu' => [
                'Bengkel',
                'Bongancina',
                'Busung Biu',
                'Kedis',
                'Kekeran',
                'Pelapuan',
                'Pucaksari',
                'Sepang',
                'Sepang Kelod',
                'Subuk',
                'Telaga',
                'Tinggarsari',
                'Tista',
                'Titab',
                'Umejero'
            ],
            'Gerokgak' => [
                'Banyupoh',
                'Celukanbawang',
                'Gerokgak',
                'Musi',
                'Patas',
                'Pejarakan',
                'Pemuteran',
                'Pengulon',
                'Penyabangan',
                'Sanggalangit',
                'Sumberklampok',
                'Sumberklima',
                'Tinga-Tinga',
                'Tukadsumaga'
            ],
            'Kubutambahan' => [
                'Bengkala',
                'Bila',
                'Bontihing',
                'Bukti',
                'Bulian',
                'Depeha',
                'Kubutambahan',
                'Mengening',
                'Pakisan',
                'Tajun',
                'Tambakan',
                'Tamblang',
                'Tunjung'
            ],
            'Sawan' => [
                'Bebetin',
                'Bungkulan',
                'Galungan',
                'Giri Emas',
                'Jagaraga',
                'Kerobokan',
                'Lemukih',
                'Menyali',
                'Sangsit',
                'Sawan',
                'Sekumpul',
                'Sinabun',
                'Sudaji',
                'Suwug'
            ],
            'Seririt' => [
                'Seririt',
                'Banjar Asem',
                'Bestala',
                'Bubunan',
                'Gunungsari',
                'Joanyar',
                'Kalianget',
                'Kalisada',
                'Lokapaksa',
                'Mayong',
                'Munduk Bestala',
                'Pangkung Paruk',
                'Patemon',
                'Pengastulan',
                'Rangdu',
                'Ringdikit',
                'Sulanyah',
                'Tangguwisia',
                'Ularan',
                'Umeanyar',
                'Unggahan'
            ],
            'Sukasada' => [
                'Sukasada',
                'Ambengan',
                'Git Git',
                'Kayu Putih',
                'Padang Bulia',
                'Pancasari',
                'Panji',
                'Panji Anom',
                'Pegadungan',
                'Pegayaman',
                'Sambangan',
                'Selat',
                'Silangjana',
                'Tegal Linggah',
                'Wanagiri'
            ],
            'Tejakula' => [
                'Bondalem',
                'Julah',
                'Les',
                'Madenan',
                'Pacung',
                'Penuktukan',
                'Sambirenteng',
                'Sembiran',
                'Tejakula',
                'Tembok'
            ]
        ];

        foreach ($data as $kecamatanName => $desas) {
            $kecamatan = $kecamatans->firstWhere('nama', $kecamatanName);

            if ($kecamatan) {
                $desaData = [];
                foreach ($desas as $desaName) {
                    $desaData[] = [
                        'kecamatan_id' => $kecamatan->id,
                        'nama' => $desaName,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                Desa::insert($desaData);
            }
        }
    }
}
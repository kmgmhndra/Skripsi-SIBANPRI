<?php

namespace App\Imports;

use App\Models\KelompokTani;
use App\Models\Kriteria;
use App\Models\KriteriaValue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;

class KelompokTaniImport implements ToModel, WithHeadingRow, WithValidation
{
    private $kriterias;
    private $kecamatanId;
    private $tahun;
    private $jenisTani;
    private $criteriaColumns = [];

    public function __construct($kecamatanId, $jenisTani, $tahun)
    {
        $this->kecamatanId = $kecamatanId;
        $this->jenisTani = $jenisTani;
        $this->tahun = $tahun;

        // Load all criteria from database and create search variations
        $this->kriterias = Kriteria::all()->mapWithKeys(function ($item) {
            $normalizedName = strtolower(trim($item->nama));
            return [$normalizedName => $item];
        });

        // Generate all possible column name variations for criteria
        $this->criteriaColumns = Kriteria::all()->flatMap(function ($kriteria) {
            $name = $kriteria->nama;
            return [
                $name,
                str_replace(' ', '_', $name),
                strtolower($name),
                strtolower(str_replace(' ', '_', $name))
            ];
        })->unique()->toArray();

        Log::debug('Kriteria tersedia:', $this->kriterias->keys()->toArray());
        Log::debug('Kolom kriteria yang dicari:', $this->criteriaColumns);
    }

    public function model(array $row)
    {
        Log::debug('Memproses baris:', array_keys($row));

        // Buat Kelompok Tani
        $kelompokTani = KelompokTani::create([
            'nama' => $this->getValue($row, 'nama'),
            'desa' => $this->getValue($row, 'desa'),
            'jenis_tani' => $this->jenisTani,
            'tahun' => $this->tahun,
            'status' => $this->getValue($row, 'status') ?? 'tidak_terpilih',
            'kecamatan_id' => $this->kecamatanId,
            'ketua' => $this->getValue($row, 'ketua'),
        ]);

        // Process all possible criteria columns
        foreach ($this->criteriaColumns as $criteriaName) {
            $value = $this->getValue($row, $criteriaName);
            if (!is_null($value)) {
                $this->saveCriteriaValue($kelompokTani, $criteriaName, $value);
            }
        }

        return $kelompokTani;
    }

    private function getValue($row, $key)
    {
        if (isset($row[$key])) {
            return $row[$key];
        }
        return null;
    }

    private function saveCriteriaValue($kelompokTani, $excelColName, $value)
    {
        $normalizedColName = strtolower(trim(str_replace('_', ' ', $excelColName)));

        if (isset($this->kriterias[$normalizedColName])) {
            $kriteria = $this->kriterias[$normalizedColName];

            KriteriaValue::create([
                'kriteria_id' => $kriteria->id,
                'kelompok_tani_id' => $kelompokTani->id,
                'value' => is_numeric($value) ? $value : null
            ]);

            Log::debug('KriteriaValue created:', [
                'kriteria' => $kriteria->nama,
                'value' => $value
            ]);
        }
    }

    public function rules(): array
    {
        $baseRules = [
            'nama' => 'required|string',
            'desa' => 'required|string',
            'status' => 'nullable|in:terpilih,tidak_terpilih',
            'ketua' => 'required|string',
        ];

        // Add validation rules for all criteria (all optional and numeric)
        foreach ($this->kriterias as $kriteria) {
            $variations = [
                $kriteria->nama,
                str_replace(' ', '_', $kriteria->nama),
                strtolower($kriteria->nama),
                strtolower(str_replace(' ', '_', $kriteria->nama))
            ];

            foreach ($variations as $variation) {
                $baseRules[$variation] = 'nullable|numeric';
            }
        }

        return $baseRules;
    }

    public function headingRow(): int
    {
        return 1;
    }
}
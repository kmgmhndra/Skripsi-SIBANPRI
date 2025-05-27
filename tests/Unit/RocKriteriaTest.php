<?php

namespace Tests\Unit;

use App\Models\Kriteria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RocKriteriaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_calculate_roc_weights_for_3_kriteria()
    {
        Kriteria::create(['nama' => 'Kriteria A', 'urutan' => 1, 'jenis' => 'benefit']);
        Kriteria::create(['nama' => 'Kriteria B', 'urutan' => 2, 'jenis' => 'benefit']);
        Kriteria::create(['nama' => 'Kriteria C', 'urutan' => 3, 'jenis' => 'cost']);

        Kriteria::hitungBobotROC();

        $kriteria = Kriteria::orderBy('urutan')->get();

        $this->assertEqualsWithDelta(0.6111, $kriteria[0]->bobot, 0.0001);
        $this->assertEqualsWithDelta(0.2778, $kriteria[1]->bobot, 0.0001);
        $this->assertEqualsWithDelta(0.1111, $kriteria[2]->bobot, 0.0001);
    }

    /** @test */
    public function it_should_calculate_roc_weights_for_5_kriteria()
    {
        Kriteria::create(['nama' => 'Kriteria A', 'urutan' => 1, 'jenis' => 'benefit']);
        Kriteria::create(['nama' => 'Kriteria B', 'urutan' => 2, 'jenis' => 'benefit']);
        Kriteria::create(['nama' => 'Kriteria C', 'urutan' => 3, 'jenis' => 'cost']);
        Kriteria::create(['nama' => 'Kriteria D', 'urutan' => 4, 'jenis' => 'benefit']);
        Kriteria::create(['nama' => 'Kriteria E', 'urutan' => 5, 'jenis' => 'cost']);

        Kriteria::hitungBobotROC();

        $kriteria = Kriteria::orderBy('urutan')->get();

        $this->assertEqualsWithDelta(0.4567, $kriteria[0]->bobot, 0.0001);
        $this->assertEqualsWithDelta(0.2567, $kriteria[1]->bobot, 0.0001);
        $this->assertEqualsWithDelta(0.1567, $kriteria[2]->bobot, 0.0001);
        $this->assertEqualsWithDelta(0.0900, $kriteria[3]->bobot, 0.0001);
        $this->assertEqualsWithDelta(0.0400, $kriteria[4]->bobot, 0.0001);
    }

    /** @test */
    public function it_should_calculate_roc_weights_for_7_kriteria()
    {
        Kriteria::create(['nama' => 'K1', 'urutan' => 1, 'jenis' => 'benefit']);
        Kriteria::create(['nama' => 'K2', 'urutan' => 2, 'jenis' => 'benefit']);
        Kriteria::create(['nama' => 'K3', 'urutan' => 3, 'jenis' => 'cost']);
        Kriteria::create(['nama' => 'K4', 'urutan' => 4, 'jenis' => 'benefit']);
        Kriteria::create(['nama' => 'K5', 'urutan' => 5, 'jenis' => 'benefit']);
        Kriteria::create(['nama' => 'K6', 'urutan' => 6, 'jenis' => 'benefit']);
        Kriteria::create(['nama' => 'K7', 'urutan' => 7, 'jenis' => 'cost']);

        Kriteria::hitungBobotROC();

        $kriteria = Kriteria::orderBy('urutan')->get();

        $this->assertEqualsWithDelta(0.3704, $kriteria[0]->bobot, 0.0001);
        $this->assertEqualsWithDelta(0.2276, $kriteria[1]->bobot, 0.0001);
        $this->assertEqualsWithDelta(0.1561, $kriteria[2]->bobot, 0.0001);
        $this->assertEqualsWithDelta(0.1085, $kriteria[3]->bobot, 0.0001);
        $this->assertEqualsWithDelta(0.0728, $kriteria[4]->bobot, 0.0001);
        $this->assertEqualsWithDelta(0.0442, $kriteria[5]->bobot, 0.0001);
        $this->assertEqualsWithDelta(0.0204, $kriteria[6]->bobot, 0.0001);
    }

}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('kriteria', function (Blueprint $table) {
            $table->enum('jenis', ['benefit', 'cost'])->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('kriteria', function (Blueprint $table) {
            $table->enum('jenis', ['benefit', 'cost'])->default('benefit')->change();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kriteria', 10)->unique();
            $table->string('nama_kriteria', 100)->unique();
            $table->string('tipe', 10); // benefit / cost
            $table->decimal('bobot_global', 7, 6)->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('kriteria');
    }
};

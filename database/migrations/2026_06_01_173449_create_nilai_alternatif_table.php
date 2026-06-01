<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('nilai_alternatif', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolah')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria')->onDelete('cascade');
            $table->decimal('skor_parameter', 5, 2);

            // Constraint unik agar tidak ada duplikasi kriteria pada sekolah yang sama
            $table->unique(['sekolah_id', 'kriteria_id'], 'unique_sekolah_kriteria');
        });
    }

    public function down(): void {
        Schema::dropIfExists('nilai_alternatif');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jawaban_kuesioner', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kriteria_id_1')->constrained('kriteria')->onDelete('cascade');
            $table->foreignId('kriteria_id_2')->constrained('kriteria')->onDelete('cascade');
            $table->decimal('nilai_saaty', 5, 4);

            // Constraint unik berpasangan per responden
            $table->unique(['user_id', 'kriteria_id_1', 'kriteria_id_2'], 'unique_user_pasangan');
        });
    }

    public function down(): void {
        Schema::dropIfExists('jawaban_kuesioner');
    }
};

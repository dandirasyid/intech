<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('dosen_matakuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->nullable()->constrained('dosen')->onDelete('cascade');
            $table->foreignId('matakuliah_id')->nullable()->constrained('matakuliah')->onDelete('cascade');
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('dosen_matakuliah');
    }
};

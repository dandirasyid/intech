<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('ujians', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->integer('durasi');
            $table->dateTime('deadline');
            $table->text('deskripsi');
            $table->text('image')->nullable();
            $table->foreignId('matakuliah_id')->constrained('matakuliah')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('ujians');
    }
};

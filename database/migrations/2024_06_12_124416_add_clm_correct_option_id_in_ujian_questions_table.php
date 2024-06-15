<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('ujian_questions', function (Blueprint $table) {
            $table->foreignId('correct_option_id')->nullable()->constrained('ujian_options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('ujian_questions', function (Blueprint $table) {
            $table->dropColumn('correct_option_id');
        });
    }
};

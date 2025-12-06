<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas'); // contoh: "1A", "2B", "6C"
            $table->string('tingkat');    // contoh: 1,2,3,4,5,6
            // Setiap kelas punya tiga guru:
            $table->foreignId('wali_kelas_id')
                ->nullable()
                ->constrained('teachers')
                ->onDelete('set null');

            $table->foreignId('guru_ngaji_id')
                ->nullable()
                ->constrained('teachers')
                ->onDelete('set null');

            $table->foreignId('guru_olahraga_id')
                ->nullable()
                ->constrained('teachers')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};

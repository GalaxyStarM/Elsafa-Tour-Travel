<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jamaah_id')
                  ->constrained('jamaah')
                  ->onDelete('cascade'); // Hapus jamaah → hapus semua dokumennya

            $table->enum('jenis_dokumen', [
                'KTP',            // Dewasa
                'KK',             // Dewasa & Anak
                'Paspor',         // Dewasa & Anak
                'Akta Kelahiran', // Anak
            ]);

            $table->string('file_path');       // path di storage/public
            $table->string('nama_file')->nullable(); // nama file asli (opsional)
            $table->timestamps();

            // Satu jamaah hanya boleh punya 1 dokumen per jenis
            $table->unique(['jamaah_id', 'jenis_dokumen']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jamaah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('kontak')->nullable();
            $table->foreignId('paket_id')->constrained('pakets')->onDelete('restrict');
            $table->enum('jenis_jamaah', ['Mandiri', 'Mitra']);
            $table->foreignId('mitra_id')->nullable()->constrained('mitras')->onDelete('set null');
            $table->enum('status_jamaah', ['Akan Berangkat', 'Selesai'])->default('Akan Berangkat');
            $table->enum('kategori_usia', ['Dewasa', 'Anak-anak'])->default('Dewasa');
            $table->string('foto_profil')->nullable();
            // ⚠️ TIDAK ADA kolom file_ktp, file_kk, file_paspor di sini
            // Dokumen disimpan di tabel 'dokumen' (terpisah)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jamaah');
    }
};
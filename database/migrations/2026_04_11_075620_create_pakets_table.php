<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pakets', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jenis', ['haji', 'umroh'])->default('umroh');
            $table->text('deskripsi')->nullable();
            $table->bigInteger('harga')->default(0);
            $table->date('tanggal_keberangkatan')->nullable();
            $table->integer('kuota')->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'selesai'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('pakets'); }
};
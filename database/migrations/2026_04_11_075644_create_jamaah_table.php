<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jamaah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('kontak', 20)->nullable();
            $table->foreignId('paket_id')->nullable()->constrained('pakets')->nullOnDelete();
            $table->enum('jenis_jamaah', ['mandiri', 'mitra'])->default('mandiri');
            $table->foreignId('mitra_id')->nullable()->constrained('mitras')->nullOnDelete();
            $table->enum('status_jamaah', ['akan_berangkat', 'sudah_berangkat'])->default('akan_berangkat');
            $table->enum('kategori_usia', ['dewasa', 'anak'])->default('dewasa');
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('jamaah'); }
};
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jamaah_id')->constrained('jamaah')->cascadeOnDelete();
            $table->enum('jenis', ['ktp', 'kk', 'paspor', 'akta_lahir']);
            $table->string('file'); // path ke storage
            $table->timestamps();

            // Satu jamaah hanya boleh punya satu file per jenis dokumen
            $table->unique(['jamaah_id', 'jenis']);
        });
    }
    public function down(): void { Schema::dropIfExists('dokumen'); }
};
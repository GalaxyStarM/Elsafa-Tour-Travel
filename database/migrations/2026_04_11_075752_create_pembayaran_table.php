<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jamaah_id')->constrained('jamaah')->cascadeOnDelete();
            $table->date('tanggal');
            $table->bigInteger('jumlah');
            $table->enum('metode', ['transfer_bank', 'tunai', 'cek', 'lainnya'])->default('transfer_bank');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('pembayaran'); }
};
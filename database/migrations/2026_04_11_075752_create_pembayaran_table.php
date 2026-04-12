<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jamaah_id')->constrained('jamaah')->onDelete('cascade');
            $table->date('tanggal_bayar');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->enum('metode', ['Transfer Bank', 'Tunai', 'QRIS', 'Lainnya']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
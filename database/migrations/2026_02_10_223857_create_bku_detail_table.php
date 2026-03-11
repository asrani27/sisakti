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
        Schema::create('bku_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bku_id')->constrained('bku')->onDelete('cascade');
            $table->date('tanggal')->nullable();
            $table->string('nomor_bukti')->nullable();
            $table->text('uraian')->nullable();
            $table->bigInteger('penerimaan')->default(0);
            $table->bigInteger('pengeluaran')->default(0);
            $table->bigInteger('saldo')->default(0);
            $table->string('status_ocr')->default('Jelas');
            $table->timestamps();
            
            $table->index('bku_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bku_detail');
    }
};
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
        Schema::create('rekening_koran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_skpd');
            $table->string('bulan');
            $table->string('tahun');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('kode_skpd')
                ->references('kode_skpd')
                ->on('skpd')
                ->onDelete('cascade');

            // Add index for faster lookups
            $table->index(['kode_skpd', 'bulan', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekening_koran');
    }
};
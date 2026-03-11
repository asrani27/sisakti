<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clean up kode_skpd values by removing carriage returns and other whitespace
        DB::statement("UPDATE skpd SET kode_skpd = TRIM(BOTH '\r' FROM TRIM(kode_skpd))");
        DB::statement("UPDATE skpd SET kode_skpd = TRIM(BOTH '\n' FROM kode_skpd)");
        DB::statement("UPDATE skpd SET kode_skpd = TRIM(BOTH ' ' FROM kode_skpd)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this migration
    }
};
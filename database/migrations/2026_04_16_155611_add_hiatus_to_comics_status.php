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
        Schema::table('comics_status', function (Blueprint $table) {
            DB::statement("ALTER TABLE comics MODIFY COLUMN status ENUM('on-going', 'completed', 'dropped', 'dikapak', 'hiatus') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comics_status', function (Blueprint $table) {
            //
        });
    }
};

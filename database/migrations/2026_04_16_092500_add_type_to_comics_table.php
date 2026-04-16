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
        Schema::table('comics', function (Blueprint $table) {
            // Menambahkan kolom type setelah kolom author
            $table->enum('type', ['manga', 'manhwa', 'manhua', 'oneshot'])
                ->default('manga')
                ->after('author');
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::table('comics', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};

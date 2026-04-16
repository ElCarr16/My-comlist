<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('comics', function (Blueprint $table) {
        // Menambahkan total_volume setelah total_chapter
        $table->integer('total_volume')->nullable()->after('total_chapter');
    });
}

public function down(): void
{
    Schema::table('comics', function (Blueprint $table) {
        $table->dropColumn('total_volume');
    });
}
};

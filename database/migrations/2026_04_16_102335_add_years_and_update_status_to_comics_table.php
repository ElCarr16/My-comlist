<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comics', function (Blueprint $table) {
            // Menambah kolom tahun
            $table->year('release_year')->nullable()->after('type');
            $table->year('finish_year')->nullable()->after('release_year');

            // Memperbarui opsi enum status (menambah 'dropped' dan 'dikapak')
            // Catatan: Laravel 11+ mendukung perubahan enum secara native
            $table->enum('status', ['on-going', 'completed', 'dropped', 'dikapak'])
                ->default('on-going')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('comics', function (Blueprint $table) {
            $table->dropColumn(['release_year', 'finish_year']);
            // Kembalikan ke enum awal jika perlu
            $table->enum('status', ['pre-release', 'on-going', 'stopped', 'completed'])
                ->change();
        });
    }
};

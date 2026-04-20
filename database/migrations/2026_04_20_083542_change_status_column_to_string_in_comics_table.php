<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('comics', function (Blueprint $table) {
            // Ubah tipe kolom menjadi string (VARCHAR) agar bisa menampung teks apa saja
            $table->string('status')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('comics', function (Blueprint $table) {
            // (Opsional) Kembalikan ke format awalmu jika di-rollback
            // Misalnya awalnya string dengan panjang 50:
            $table->string('status', 50)->nullable()->change();
        });
    }
};

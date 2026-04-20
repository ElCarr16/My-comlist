<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('comics', function (Blueprint $table) {
            // Ubah tipe kolom menjadi string agar bisa menampung 'Novel', 'One-shot', 'Light Novel', dll
            $table->string('type')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('comics', function (Blueprint $table) {
            // Kembalikan ke format sebelumnya jika di-rollback (sesuaikan jika perlu)
            $table->string('type', 50)->nullable()->change();
        });
    }
};

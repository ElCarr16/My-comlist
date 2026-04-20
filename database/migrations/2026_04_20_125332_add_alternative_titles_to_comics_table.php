<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('comics', function (Blueprint $table) {
            // Menambahkan kolom setelah kolom title
            $table->text('alternative_titles')->nullable()->after('title');
        });
    }

    public function down()
    {
        Schema::table('comics', function (Blueprint $table) {
            $table->dropColumn('alternative_titles');
        });
    }
};

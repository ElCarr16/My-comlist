<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('comics', function (Blueprint $table) {
            // Tambahkan kolom mal_id setelah id. Nullable karena komik manual tidak punya mal_id.
            $table->integer('mal_id')->nullable()->unique()->after('id');
        });
    }

    public function down()
    {
        Schema::table('comics', function (Blueprint $table) {
            $table->dropColumn('mal_id');
        });
    }
};

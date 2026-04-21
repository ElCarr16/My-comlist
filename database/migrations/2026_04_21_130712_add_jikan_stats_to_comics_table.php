<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('comics', function (Blueprint $table) {
            $table->decimal('mal_score', 3, 2)->nullable()->after('status');
            $table->integer('mal_favorites')->default(0)->after('mal_score');
        });
    }

    public function down()
    {
        Schema::table('comics', function (Blueprint $table) {
            $table->dropColumn(['mal_score', 'mal_favorites']);
        });
    }
};

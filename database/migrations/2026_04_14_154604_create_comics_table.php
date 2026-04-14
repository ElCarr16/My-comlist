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
        Schema::create('comics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // Tambahan untuk URL SEO-friendly
            $table->text('synopsis')->nullable();
            $table->string('author')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('status', ['pre-release', 'on-going', 'stopped', 'completed'])->default('pre-release');
            $table->integer('total_chapter')->default(0); // Angka 0 tanpa tanda kutip
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comics');
    }
};

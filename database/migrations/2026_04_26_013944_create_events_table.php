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
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->string('nama_event', 150);
        $table->date('tanggal');
        $table->string('gambar')->nullable();
        $table->string('slug')->unique();
        $table->text('deskripsi')->nullable();
        $table->time('waktu')->nullable();
        $table->time('waktu_selesai')->nullable();
        $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
        $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

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
    Schema::create('attendees', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_detail_id')->constrained()->cascadeOnDelete();
        $table->string('kode_tiket', 255);
        $table->enum('status_checkin', ['belum', 'sudah'])->default('belum');
        $table->dateTime('waktu_checkin')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendees');
    }
};

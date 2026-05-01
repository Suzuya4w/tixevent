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
    Schema::create('vouchers', function (Blueprint $table) {
        $table->id();
        $table->string('kode_voucher', 20);
        $table->integer('potongan');
        $table->integer('kuota');
        $table->string('slug')->unique();
        $table->dateTime('tanggal_mulai')->nullable();
        $table->dateTime('tanggal_berakhir')->nullable();
        $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
